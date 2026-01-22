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
    <div class="Contenedor-izquierda">
        <?php
            for ($i = 1; $i <= 100; $i++) {
                echo "
                <div class='Contenedor-producto'>
                    <h2 class='Titulo'>Acetamimaifriend {$i}</h2>
                    <img src='img/medicinas.png' alt='medicina' class='imagen-medicamentos'>
                    <div class= 'info'>
                        <span class='titulo-nombre'>Nombre</span>
                        <span class='info-nombre'>Acteaminofen</span>
                        <span class='titulo-cantidad'>Cantidad</span>
                        <span class='info-cantidad'>160mg/5ml-Jarabe</span>
                        <span class='titulo-laboratorio'>Laboratorio</span>
                        <span class='info-laboratorio'>MK</span>
                        <span class='titulo-precio'>Precio</span>
                        <span class='info-precio'>7000 COP</span>  
                    </div> 
                </div>
                ";
            }
        ?>
    </div> 
    <div class="Contenedor-derecha"> 
        <span class='titulo-factura'>SISTEMA DE FACTURACION EASYPIECE</span>
        <span class='nombre-farmacia'>NOMBRE FARMACIA</span>
        <span class='direccion-farmacia'>Direccion:</span>
        <span class='telefono-farmacia'>Telefono:</span>
        <span class='correo-farmacia'>Correo</span>
        <span class='numero-factura'>FACTURA NRO 120921</span>
        <span class='fecha'>Fecha:</span>
        <span class='cliente'>Cliente:</span>
        <span class='cedula'>Identificacion:</span>
        <span class='direccion'>Direccion:</span>
        <span class='telefono'>Telefono:</span>

        <?php
            for ($i = 1; $i <= 30; $i++) {
                echo "
                <div class='Factura-productos'>   
                    <span class='nombres-presentacion'>Nombre + Presentacion</span>
                    <span class='cantidad'>Cantidad</span>
                    <span class='valor-total'>Valor total</span>
                </div>
                ";
            }
        ?>
    

        <div class="Contenedor-derecha-inferior">
            <button type="button" class="boton-descuento" >Aplicar Descuento</button>
            <button type="button" class="boton-pagar" >Pagar</button>  
        </div> 
    </div>

    <div class="opciones-usuario" id = "op-usuario">
            <span>Informacion Usario</span> 
            <span>Registro</span>
    </div>


    <script src="../JS/main.js"></script>
</body>
</html>
