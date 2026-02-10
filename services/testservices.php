<?php
session_start();

// 1. Datos que vamos a enviar al Service (Simulando un formulario)
$urlService = "http://localhost/EasyPiece_Nuevo/Services/validarUser.php";
$datosLogin = [
    "correo" => "jacinto1@gmail.com", // Pon un correo real de tu DB
    "contrasena" => "1"      // Pon la clave real
];

// 2. Configuramos cURL para llamar al SERVICE
$ch = curl_init($urlService);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosLogin));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

// IMPORTANTE: Para que la sesión se guarde en este archivo, 
// necesitamos capturar las cookies que el Service nos devuelva.
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt'); 

$respuestaRaw = curl_exec($ch);
curl_close($ch);

$respuesta = json_decode($respuestaRaw, true);

// 3. Mostramos los resultados del Test
header('Content-Type: application/json');

if (isset($respuesta['success']) && $respuesta['success'] === true) {
    echo json_encode([
        "paso_1" => "Llamada al Service EXITOSA",
        "mensaje_service" => $respuesta['mensaje'],
        "paso_2" => "Verificando si el Service activó la sesión...",
        "sesion_actual" => $_SESSION // Aquí veremos si se llenó
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "error" => "El Service rechazó la petición",
        "detalle" => $respuesta
    ], JSON_PRETTY_PRINT);
}