<?php
session_start();
require_once __DIR__ . "/../Clases/Cliente.php";
header('Content-Type: application/json');

$id_usuario = $_SESSION['usuario_id'] ?? null;

if (!$id_usuario) {
    echo json_encode([
        "success" => false, 
        "mensaje" => "Sesión no válida o expirada. Por favor, inicie sesión."
    ]);
    exit;
}

try {
    $objCliente = new Cliente();
    $resultado = $objCliente->obtenerPerfil($id_usuario);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error en el servidor: " . $e->getMessage()
    ]);
}