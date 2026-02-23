<?php
// config/conexion.php
$isLocal = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1');

if ($isLocal) {
    $host = "localhost";
    $user = "root";
    $pass = "#J4c1nt0";
    $db   = "easypiece";
} else {
    // Datos de Hostinger
    $host = "localhost";
    $db   = "u123456789_easypiece"; 
    $user = "u123456789_root";
    $pass = "TuClaveDeHostinger";
}

try {
    // 1. Conexión PDO (Para Notificaciones y Clases nuevas)
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['conexion'] = $conexion; // La mantenemos global por tu lógica de clases

    // 2. Conexión MySQLi (Para Cupones y Clientes antiguos)
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) { throw new Exception(); }
    $conn->set_charset("utf8");

} catch (Exception $e) {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "error" => "Fallo de conexion"]));
}

if ($isLocal) {
    // ... tus datos de db local ...
    $base_url = "http://localhost/EasyPiece_Nuevo"; 
} else {
    // ... tus datos de db hostinger ...
    $base_url = "https://tu-dominio-real.com"; // Lo cambias cuando lo compres
}

// Definimos una constante global para usarla en cualquier parte del sistema
define('BASE_URL', $base_url);