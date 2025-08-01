<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPiece</title>
    <link rel="stylesheet" href="CSS/styles.css"> <!-- Enlace a la hoja de estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Chau+Philomene+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-box"> 
            <img src="img/EasyPieceLogo.png" alt="Logo" class="header-logo">
            <div class="title-container">  <!-- Contenedor para el título e imagen -->
                <h1 class="header-title">Contáctanos</h1> <!-- Título -->
                <img src="img/IconoContactanos.png" alt="Icono" class="header-icon"> <!-- Nueva imagen junto al título -->
            </div>
    </header>

    <main>
        <div class="decoracion">
            <img src="img/esquinas.png" class="esquina-inferior-izquierda" alt="Decoración">
            <img src="img/esquinas.png" class="esquina-inferior-derecha" alt="Decoración">
        </div>
        
        <section class="bienvenida">
            <p>Bienvenido a EasyPiece</p>
            <p>¿Eres nuevo/a? <span class="crear-usuario">Crear cuenta</span></p>

        </section>

        <div class="botones-contenedor">
            <button class="botones"><i class="fa-brands fa-facebook"></i>Usar Facebook</button>
            <button class="botones"><i class="fa-brands fa-google"></i>Usar Google</button>
        </div>

        <button class="botones boton-debajo"><i class="fa-brands fa-apple"></i>Usar Apple</button>
        
        <div class="linea"></div>

        <p class="iniciar-sesion">Iniciar sesion</p>

        <form id="formLogin" class="formulariouno" action="login.php" method="POST">
            <div style="position: relative;">
                <label for="usuario">Usuario</label>
                <div class=" input-icono-uno">
                    <input type="text" id="usuario" name="usuario" placeholder="Digita tu usuario" required>
                    <img src="img/equis.png" alt="Error" id="img-correo">
                    <img src="img/CaracterInvalido.png" alt="Invalido" id="img-invalido">
                </div>
                
            </div>

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contrasena" placeholder="Escribe tu contraseña" required>

            <button type="submit" class="boton-enviar">Enviar</button>

        </form>
        <p class="olvidaste-contraseña">¿Olvidaste tu contraseña?</p>

        

    </main>

    <footer>
    </footer>

    <script src="JS/main.js" defer></script> 

</body>
</html>
