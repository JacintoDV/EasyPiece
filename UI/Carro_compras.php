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
    <link rel="stylesheet" href="CSS/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/carritocompras.css?v=<?php echo time(); ?>">

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
            
            <h1 class="header-title-pagina-principal">Carro de Compras</h1>
            <input type="text" id="buscador" placeholder="Buscar">

            <div class="header-derecha">
                <img src="img/Buscar.png" alt="Buscar" class="header-buscar" id = "buscar-img">
                <img src="img/Notificaciones.png" alt="Notificaciones" class="header-notificaciones" id="notificaciones">
                <img src="img/CarroCompras.png" alt="Carrito" class="header-carrito"id ="carrito">
                <img src="img/IconoContactanos.png" alt="Icono" class="header-contactanos" id= "contactanos">
            </div>    
        </div> 
    </header>
    <div class="Contenedor-izquierda" id="lista-carrito">
    <p style="padding: 20px;">Cargando productos seleccionados...</p>
</div>

    <div class="Contenedor-derecha"> 
        <div class="factura-header-info" style="display: flex; flex-direction: column; margin-bottom: 20px;">
            <span class='titulo-factura' style="font-size: 1.5em; font-weight: bold; text-align: center;">SISTEMA DE FACTURACION EASYPIECE</span>
            <span class='nombre-farmacia' style="font-weight: bold; margin-top: 10px;">NOMBRE FARMACIA</span>
            <span class='direccion-farmacia'>Dirección: Calle Falsa 123</span>
            <span class='telefono-farmacia'>Teléfono: 300 000 0000</span>
            <span class='correo-farmacia'>Correo: contacto@farmacia.com</span>
        </div>

        <div class="factura-cliente-info" style="display: flex; flex-direction: column; gap: 5px; border-top: 1px solid #ccc; padding-top: 10px;">
            <span class='numero-factura' style="font-weight: bold;">FACTURA NRO: 120921</span>
            <span class='fecha'>Fecha: <?php echo date("d/m/Y"); ?></span>
            <span class='cliente'>Cliente: Jacinto Pérez</span>
            <span class='cedula'>Identificación: 12345678</span>
            <span class='direccion'>Dirección: Carrera 10 #20-30</span>
            <span class='telefono'>Teléfono: 311 111 1111</span>
        </div>

        <div id="detalle-factura" style="min-height: 100px;">
            </div>

        <div class="Contenedor-derecha-inferior" style="margin-top: auto; padding-top: 20px; display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="boton-descuento">Aplicar Descuento</button>
            <button type="button" class="boton-pagar">Pagar</button>  
        </div> 
    </div>

    <div class="opciones-usuario" id = "op-usuario">
            <span id= "info-usuario-span">Informacion Usario</span> 
            <span id= "info-registro-span">Registro</span>
            <button id="btn-cerrar-sesion">Cerrar Sesion</button>
    </div>


    <script src="../JS/main.js"></script>
    <script src="../JS/carrito_compras.js"></script>
</body>
</html>
