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
    <link rel="stylesheet" href="CSS/styles.css?v=<?php echo time(); ?>">
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
                <a href="Notificaciones.php">
                    <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones">
                </a>
                <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos">
            </div>    
        </div> 
    </header>

         <?php
            echo "<div class='scroll-horizontal'>";
            for ($i = 1; $i <= 100; $i++) {
                echo "
                <div class='contenedor-izquierda'>
                    <h2 class='Titulo'>Acetamimaifriend {$i}</h2>
                     <div class='contenedor-abajo'>
                        <img src='img/medicinas.png' alt='medicina' class='imagen-medicamentos'>
                        <div class='texto-derecha'>
                            <p class='titulo-nombre'>Nombre</p>
                            <p class='info-nombre'>Acteaminofen</p>
                            <p class='titulo-cantidad'>Cantidad</p>
                            <p class='info-cantidad'>160mg/5ml-Jarabe</p>
                            <p class='titulo-laboratorio'>Laboratorio</p>
                            <p class='info-laboratorio'>MK</p>
                            <p class='titulo-precio'>Precio</p>
                            <p class='info-precio'>7000 COP</p>
                        </div>
                     </div>      
                </div>
                ";
            }
            echo "</div>";
        ?>
    <script src="../JS/main.js"></script>
    <script src="../JS/Pagina_principal.js"></script>
</body>
</html>
