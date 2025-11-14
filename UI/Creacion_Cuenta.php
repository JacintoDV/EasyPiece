<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece</title>
    <link rel="stylesheet" href="CSS/styles.css"> <!-- Enlace a la hoja de estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Chau+Philomene+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <header>
        <div class="header-box"> 
            <img src="img/EasyPieceLogo.png" alt="Logo" class="header-logo">
            <div class="title-container">  <!-- Contenedor para el título e imagen -->
                <h1 class="header-title">Contáctanos</h1> <!-- Título -->
                <img src="img/IconoContactanos.png" alt="Icono" class="header-icon"> <!-- Nueva imagen junto al título -->
            </div>
        </div>  
    </header>

    <p class="crear-cuenta">Crear Cuenta</p>
    
    <div class="linea"></div>

    <form id="formRegistro" class="formulario">
        <div class="contenedor-columnas">
          
          <!-- Columna izquierda -->
          <div class="columna">
            <div class="divs-paginados">
              <label for="nombres">Nombres</label>
              <div class=" input-icono">
                <input type="text" id="nombre" name="nombre" placeholder="Digite su nombre" required>
                <img src="img/equis.png" alt="Error" id="img-nombres">
                <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-nombres">
              </div>
            </div>
      
            <div class="divs-paginados">
              <label for="apellidos">Apellidos</label>
              <div class=" input-icono">
                <input type="text" id="apellidos" name="apellido" placeholder="Digite su apellido" required>
                <img src="img/equis.png" alt="Error" id="img-apellidos">
                <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-apellidos">
              </div>
            </div>
      
            <div class="divs-paginados">
              <label for="correo">Correo</label>
              <input type="text" id="correo" name="correo"  placeholder="Digite su correo electronico" required>
              <img src="img/equis.png" alt="Error" id="img-correo-uno">
              <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-correo">
            </div>

            <div class="divs-paginados">
              <label for="fecha-nacimiento">Fecha de nacimiento</label>
              <input type="text" id="fecha" name="nacimiento" placeholder="Dia/Mes/Año">
            </div>
          </div>
      
          <!-- Columna derecha -->
          <div class="columna">
            <div class="divs-paginados">
              <label for="identificacion">Identificación</label>
              <div class="input-icono">
                <input type="text" id="id" name="codigo" placeholder="Digite su ID" required>
                <img src="img/equis.png" alt="Error" id="img-id">
                <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-id">
              </div> 
            </div>

            <div class="divs-paginados">
              <label for="telefono">Telefono</label>
              <input type="text" id="telefono" name="telefono" placeholder="Digite su telefono" required>
              <img src="img/equis.png" alt="Error" id="img-telefono">
              <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-telefono">
            </div>
      
            <div class="divs-paginados">
              <label for="contrasena">Contraseña</label>
              <input type="password" id="contrasena" name="contrasena" placeholder="Digite su contraseña" required>
              <button type="button" id="mostrarPW" class="btnVer">👁️</button>
              <img src="img/equis.png" alt="Error" id="img-contrasena">
              <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-contrasena">
            </div>
      
            <div class="divs-paginados">
              <label for="contrasenados">Repita su contraseña</label>
              <input type="password" id="contrasenados" placeholder="Digite su contraseña nuevamente" required>
              <button type="button" id="mostrarPWdos" class="btnVerDos">👁️</button>
              <img src="img/equis.png" alt="Error" id="img-contrasenados">
              <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido-contrasenados">
            </div>
            <p id="msg"></p>
          </div>
      
        </div>
      
        <button type="button" class="boton-registrarse" onclick="verificarCorreo()">Registrarse</button>
    </form>
    
    <p class="regresar">Iniciar sesion</p>
    <!-- JS que funciona mostrando las imagenes de advertencia -->
    <script src="../JS/main.js"></script>
    <!-- JS para todo el back -->
    <script src="../JS/crear_cuenta.js"></script> 


</body>
</html>