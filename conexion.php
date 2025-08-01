<?php
$host = "localhost";
$usuario = "root";
$contrasena = "#J4c1nt0";
$bd = "EasyPiece";

$conexion = new mysqli($host, $usuario, $contrasena, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


