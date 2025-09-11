<?php
session_start(); 
include "conexion.php";

$correo = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Buscar usuario por correo
$sql = "SELECT * FROM clientes WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    // Comparar contraseñas
    if (password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['usuario'] = $correo;
        header("Location: Pagina_principal.php");
        echo "Credenciales correctas";
        exit;
    }
    else {
       header("Location: Creacion_Cuenta.php");
    }
}


$conexion->close();
?>
