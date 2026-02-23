<?php
// 1. Iniciamos sesión
@session_start();

require_once __DIR__ . "/../Clases/Registro.php";

header('Content-Type: application/json');

// 2. Verificación de seguridad
$id_usuario = $_SESSION['usuario_id'] ?? null;

if (!$id_usuario) {
    echo json_encode([
        "success" => false, 
        "mensaje" => "Sesión no válida o expirada",
        "datos" => [] 
    ]);
    exit;
}

try {
    $modeloRegistro = new Registro();
    $respuesta = $modeloRegistro->listar($id_usuario);

    // 3. Manejo inteligente de la respuesta de la API
    // Si la API devolvió un error (como el que vimos en el test)
    if (isset($respuesta['error'])) {
        echo json_encode([
            "success" => false,
            "mensaje" => $respuesta['error'],
            "datos" => []
        ]);
    } 
    // Si la respuesta es un array (vacío o con datos), fue un éxito
    elseif (is_array($respuesta)) {
        echo json_encode([
            "success" => true,
            "datos"   => $respuesta
        ]);
    } 
    else {
        throw new Exception("La API devolvió un formato desconocido");
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Error en el Service: " . $e->getMessage(),
        "datos" => []
    ]);
}