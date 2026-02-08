<?php
require_once '../Clases/Cliente.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    $cliente = new Cliente([
        "contrasena" => $data['contrasena'],
        "nombre"     => $data['nombres'] . " " . $data['apellidos'], 
        "correo"     => $data['correo'],
        "telefono"   => $data['telefono'],
        "fecha_nacimiento" => $data['fecha'], // Formato d/m/Y desde el JS
        "codigo"     => $data['id'] 
    ]);

    
    $urlBusqueda = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php"; 

    if ($cliente->verificarCorreoCoincide($urlBusqueda)) { 
        echo json_encode(["success" => false, "error" => "El correo ya está registrado en el sistema"]);
        exit;
    }

    $cliente->generarCodigoPersonalizado($data['id']);

    $urlApi = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php";
    $respuesta = $cliente->enviarALaApi($urlApi);
    
    echo json_encode($respuesta);

} else {
    echo json_encode(["success" => false, "error" => "No se recibieron datos"]);
}
?>