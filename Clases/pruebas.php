<?php
require_once 'Cliente.php'; // Asegúrate que la ruta sea correcta

$datos = [
    "contrasena" => "12345",
    "nombre"     => "Prueba Mozilla",
    "correo"     => "test@correo.com",
    "telefono"   => 987654321,
    "fecha_nacimiento" => "1990-01-01"
];

$cliente = new Cliente($datos);
$cliente->generarCodigoPersonalizado(5000);

// Usamos la IP 127.0.0.1 para evitar problemas de resolución de nombres
$url = "http://127.0.0.1/EasyPiece_Nuevo/API/clientesAPI.php";

echo "<h3>Iniciando envío...</h3>";
$res = $cliente->enviarALaApi($url);

echo "<pre>";
if (isset($res['error_curl'])) {
    echo "❌ ERROR DE CCONEXIÓN: " . $res['error_curl'];
} elseif (isset($res['error_api_crudo'])) {
    echo "⚠️ LA API RESPONDIÓ CON ERROR DE CÓDIGO:\n";
    echo htmlspecialchars($res['error_api_crudo']);
} else {
    echo "✅ RESPUESTA EXITOSA:\n";
    print_r($res);
}
echo "</pre>";