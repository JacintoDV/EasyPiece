<?php
session_start();
require_once __DIR__ . "/../Clases/Cliente.php";

header('Content-Type: application/json');

$id_usuario = $_SESSION['usuario_id'] ?? null;

if (!$id_usuario) {
    echo json_encode(["success" => false, "mensaje" => "Sesión no válida"]);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["success" => false, "mensaje" => "No se recibieron datos"]);
    exit;
}

$data['codigo'] = $id_usuario;

try {
    $objCliente = new Cliente();
    $resultado = $objCliente->actualizarPerfil($data);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error en el servicio: " . $e->getMessage()
    ]);
}