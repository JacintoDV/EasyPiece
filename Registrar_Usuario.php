<?php
include 'conexion.php';
include 'Clases/Cliente.php';

// Verifica que llegaron los datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente = Cliente::obtener_datos();

    if ($cliente) {
        if ($cliente->insertar_en_bd($conexion)) {
            echo "Usuario registrado con éxito";
        } else {
            echo "Error al registrar usuario en la base de datos";
        }
    } else {
        echo "Las contraseñas no coinciden.";
    }
} else {
    echo "No se recibieron datos";
}
?>

