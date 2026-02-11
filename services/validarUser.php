<?php
session_start();
require_once '../Clases/Cliente.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$correoPost = isset($data['correo']) ? trim($data['correo']) : (isset($_GET['correo']) ? trim($_GET['correo']) : null);
$passPost   = isset($data['contrasena']) ? trim($data['contrasena']) : (isset($_GET['contrasena']) ? trim($_GET['contrasena']) : null);

if (!$correoPost || !$passPost) {
    echo json_encode(["success" => false, "error" => "Por favor, completa todos los campos"]);
    exit;
}

$urlApi = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php";
$payload = json_encode([
    "accion" => "login",
    "correo" => $correoPost,
    "contrasena" => $passPost
]);


$ch = curl_init($urlApi);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


$resData = json_decode($response, true);

if ($httpCode === 200 && isset($resData['success']) && $resData['success'] === true) {
    
    $usuario = $resData['usuario'];

    $_SESSION['id_usuario'] = $usuario['codigo'];
    $_SESSION['usuario']    = $usuario['nombre']; 
    $_SESSION['usuario_rol']= $usuario['rol'] ?? 'cliente';

    echo json_encode([
        "success" => true, 
        "mensaje" => $resData['mensaje']
    ]);
}else {
    echo json_encode([
        "success" => false, 
        "error" => $resData['mensaje'] ?? "Error en la autenticación"
    ]);
}