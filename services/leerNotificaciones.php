<?php
session_start();
require_once '../Clases/Notificacion.php';

// 1. Empezamos a atrapar cualquier salida (errores, echos, warnings)
ob_start(); 

header('Content-Type: application/json');

$idUsuario = $_SESSION['usuario_id'] ?? null;

if ($idUsuario) {
    // Al instanciar, la Clase llama a la API. 
    // Si la API tira un error 400, ob_start lo captura y no lo manda al JS.
    $notif = new Notificacion(); 
    $datos = $notif->leerPorUsuario($idUsuario);

    // 2. Limpiamos TODA la basura que capturó ob_start
    ob_end_clean(); 

    // 3. Mandamos el JSON puro
    echo json_encode([
        "success" => true,
        "datos" => $datos
    ]);
} else {
    ob_end_clean();
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "No hay sesion"]);
}