<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión, lo mandamos al login de inmediato
    header("Location: Inicio_sesion_EasyPiece.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece</title>
    <link rel="stylesheet" href="CSS/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/paginaprincipal.css?v=<?php echo time(); ?>">

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
                <img src="img/IconoUsuario.png" alt="Usuario" class="header-usuario" id = "usuario">
            </div>
            
            <h1 class="header-title-pagina-principal">Página Principal</h1>
            <input type="text" id="buscador" placeholder="Buscar">

            <div class="items-derecha">
                <img src="img/Buscar.png" alt="Buscar" class="header-buscar" id="buscar-img">
                <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones" id="notificaciones">
                 <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito" id="carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos" id="contactanos">
            </div>    
        </div> 
    </header>

         <div class='scroll-horizontal' id='contenedor-productos'>
            <p>Cargando productos...</p> 
        </div>
        <div class="opciones-usuario" id = "op-usuario">
            <span id= "info-usuario-span">Informacion Usario</span> 
            <span id= "info-registro-span">Registro</span>
            <button id="btn-cerrar-sesion">Cerrar Sesion</button>
        </div>
        
    <script src="../JS/main.js"></script>
    <script src="../JS/Pagina_principal.js"></script>
</body>
</html>
