<?php
session_start();
require_once '../Clases/Cliente.php';
require_once __DIR__ . "/../config/conexion.php";

header('Content-Type: application/json');

// 1. Capturar datos del JS
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$correoPost = $data['correo'] ?? null;
$passPost   = $data['contrasena'] ?? null;

if (!$correoPost || !$passPost) {
    echo json_encode(["success" => false, "error" => "Email y contraseña requeridos"]);
    exit;
}

// 2. Llamada a la API
$urlApi = BASE_URL . "/API/clientesAPI.php";
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
$curlError = curl_error($ch);
curl_close($ch);

// 3. RASTREADOR: Si la API no responde 200 o hay error de cURL
if ($httpCode !== 200) {
    echo json_encode([
        "success" => false, 
        "error" => "Error de API (Código $httpCode)",
        "detalle" => $response, // Esto te mostrará el error de PHP si lo hay
        "curl_error" => $curlError
    ]);
    exit;
}

$resData = json_decode($response, true);

// 4. Validar la lógica del login
if (isset($resData['success']) && $resData['success'] === true) {
    
    $usuario = $resData['usuario'];

    // Sincronizamos nombres de sesión con los de la API
    $_SESSION['usuario_id']     = $usuario['codigo'];
    $_SESSION['usuario_nombre'] = $usuario['nombre']; 
    $_SESSION['usuario_rol']    = $usuario['rol'] ?? 'cliente';

    echo json_encode([
        "success" => true, 
        "mensaje" => "Bienvenido " . $usuario['nombre'],
        "redireccion" => ($_SESSION['usuario_rol'] === 'admin') ? "admin.php" : "perfil.php"
    ]);
} else {
    echo json_encode([
        "success" => false, 
        "error" => $resData['mensaje'] ?? "Correo o contraseña incorrectos"
    ]);
}