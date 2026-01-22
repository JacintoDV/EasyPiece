<?php
/*session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: inicio_sesion_EasyPiece.php");
    exit;
}*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece</title>
    <link rel="stylesheet" href="CSS/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/notificaciones.css?v=<?php echo time(); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Chau+Philomene+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <header>
        <div class="header-box-pp"> 
            <div class="header-izquierda">
                <img src="img/EasyPieceLogo.png" alt="Logo" class="header-logo-pp" id="principal">
                <img src="img/IconoUsuario.png" alt="Usuario" class="header-usuario" id="usuario">
            </div>
            
            <h1 class="header-title-pagina-principal">Notificaciones</h1>
            <input type="text" id="buscador" placeholder="Buscar">

            <div class="header-derecha">
                <img src="img/Buscar.png" alt="Buscar" class="header-buscar" id = "buscar-img">   
                <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones" id="notificaciones">
                <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito" id ="carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos" id ="contactanos">
            </div>    
        </div> 
    </header>

    <?php
    echo '<div class="scroll-notificaciones">';
    for ($i = 1; $i <= 100; $i++) {
        echo '
        <div class="contenedor-notificaciones collapsed" >
            <h2 class="titulo-notificacion">Notificación ' . $i . '</h2>
            <p class="info-notificacion">
                Esta es la información número ' . $i . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>';
    }
    echo '</div>';
    ?>

    <div class="opciones-usuario" id = "op-usuario">
            <span>Informacion Usario</span> 
            <span>Registro</span>
    </div>

    <script src="../JS/main.js"></script>
    <script src="../JS/notificaciones.js"></script>
</body>
</html>
