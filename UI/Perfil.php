<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece - Perfil</title>
    <link rel="stylesheet" href="CSS/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/perfil.css?v=<?php echo time(); ?>">

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
            
            <h1 class="header-title-pagina-principal">Perfil</h1>
            <input type="text" id="buscador" placeholder="Buscar">

            <div class="items-derecha">
                <img src="img/Buscar.png" alt="Buscar" class="header-buscar" id="buscar-img">
                <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones" id="notificaciones">
                <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito" id="carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos" id="contactanos">
            </div>    
        </div> 
    </header>

    <div class="opciones-usuario-perfil" id="op-usuario-perfil">
        <span id="info-usuario-clic">Informacion de Usuario</span> 
        <span id="registro-perfil">Registro</span>
        <button id="btn-cerrar-sesion">Cerrar Sesion</button>
    </div>

    <div class="contenedor-informacion" id="cont-info">
        <div class="contenedor-izquierda">
            <label for="nombre">Nombre</label>
            <span>Nombre</span>
            <label for="apellido">Apellido</label>
            <span>Apellido</span>
            <label for="telefono">Telefono</label>
            <span id="editable-telefono">Telefono</span>
            <label for="correo">Correo</label>
            <span id="editable-correo">Correo</span>
        </div>

        <div class="contenedor-derecha">
            <label for="direccion">Direccion</label>
            <span id="editable-direccion">Direccion</span>
            <label for="codigo">Codigo Usuario</label>
            <span>Codigo Usuario</span>
            <label for="fecha">Fecha Nacimiento</label>
            <span>Fecha Nacimiento</span>
            <label for="tarjeta">Tarjeta Asociada</label>
            <span id="editable-trajeta">Tarjeta Asociada</span>
        </div>
        <button id="btn-editar">Editar</button>
    </div>

    <div class="scroll-registro" id="lista-registro" style="display: none;">
        <div style="padding: 20px; text-align: center; font-family: 'Chau Philomene One', sans-serif;">
            Cargando registros...
        </div>
    </div>
        
    <script src="../JS/main.js"></script>
    <script src="../JS/perfil.js"></script>
</body>
</html>