<?php
// 1. Iniciamos sesión para saber quién es el usuario
@session_start();

require_once __DIR__ . "/../Clases/Registro.php";

header('Content-Type: application/json');

// 2. Identificamos al usuario (Sesión o el ID de Jacinto por defecto)
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 123454200;

try {
    // 3. Instanciamos la clase y llamamos al método listar
    $modeloRegistro = new Registro();
    $respuesta = $modeloRegistro->listar($id_usuario);

    // 4. Verificamos la respuesta
    // Nota: Manejamos si la API devuelve el array directo o envuelto en 'datos'
    $datos = isset($respuesta['datos']) ? $respuesta['datos'] : $respuesta;

    if (is_array($datos)) {
        echo json_encode([
            "success" => true,
            "datos"   => $datos
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "No se encontraron registros para este usuario.",
            "datos"   => []
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error"   => "Error en el Service: " . $e->getMessage()
    ]);
}