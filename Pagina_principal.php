<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: inicio_sesion_EasyPiece.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Chau+Philomene+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <header>
        <div class="header-box-pp"> 
            <div class="header-izquierda">
                <img src="img/EasyPieceLogo.png" alt="Logo" class="header-logo-pp">
                <img src="img/IconoUsuario.png" alt="Usuario" class="header-usuario">
            </div>
            
            <h1 class="header-title-pagina-principal">Página Principal</h1>

            <div class="items-derecha">
                <img src="img/Buscar.png" alt="Buscar" class="header-buscar">
                <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones">
                <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos">
            </div>    
        </div> 
    </header>

    <div class="scroll-horizontal">
        <div class="contenedor-izquierda">
            <h2>Acetamimaifriend</h2>
            <p>Esta es la información que va dentro del contenedor. Puedes agregar texto, imágenes, botones, lo que quieras.</p>
        </div>
        <!-- Repite o reemplaza dinámicamente con PHP -->
    </div>

    <script src="JS/main.js"></script>
</body>
</html>
