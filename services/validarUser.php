<?php
session_start();
require_once '../Clases/Cliente.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$correoPost = isset($data['correo']) ? trim($data['correo']) : null;
$passPost = isset($data['contrasena']) ? trim($data['contrasena']) : null;

if (!$correoPost || !$passPost) {
    echo json_encode(["success" => false, "error" => "Credenciales incompletas"]);
    exit;
}

$urlApi = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php";

$ch = curl_init($urlApi . "?correo=" . urlencode($correoPost));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$raw_response = curl_exec($ch);
curl_close($ch);

$resData = json_decode($raw_response, true);

if (isset($resData['existe']) && $resData['existe'] === true) {
    // EXTRAER HASH
    $hashBD = $resData['datos']['contrasena'] ?? null;
    $nombreUser = $resData['datos']['nombre'] ?? 'Usuario';
    
    // --- LIMPIEZA EXTREMA DEL HASH ---
    if ($hashBD) {
        $hashBD = trim($hashBD);           // Quita espacios
        $hashBD = trim($hashBD, '"');      // Quita comillas dobles si el JSON las pegó
        $hashBD = stripslashes($hashBD);   // Quita barras invertidas
        $hashBD = str_replace('\\/', '/', $hashBD); // Limpia escapes de URL/JSON
    }

    // COMPARACIÓN
    if ($hashBD && password_verify($passPost, $hashBD)) {
        $_SESSION['usuario'] = $correoPost;
        $_SESSION['nombre'] = $nombreUser;
        echo json_encode(["success" => true, "mensaje" => "Bienvenido " . $nombreUser]);
    } else {
        // DEPUREMOS: Si falla, enviaremos el hash que recibimos para que lo veas
        echo json_encode([
            "success" => false, 
            "error" => "Contraseña incorrecta",
            "debug_hash_recibido" => $hashBD // Esto te dirá qué está leyendo PHP
        ]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Correo no registrado"]);
}