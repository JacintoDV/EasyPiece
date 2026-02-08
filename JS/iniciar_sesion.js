document.addEventListener('DOMContentLoaded', () => {
    const botonEnviar = document.getElementById('btnEnviar');

    if (botonEnviar) {
        // 1. Recibimos el parámetro 'e' (evento)
        botonEnviar.addEventListener('click', (e) => {
            
            // 2. IMPORTANTE: Evita que la página se recargue y limpie los datos
            e.preventDefault(); 

            const inputUsuario = document.getElementById('usuario');
            const inputContrasena = document.getElementById('contrasena');

            if (!inputUsuario || !inputContrasena) {
                console.error("Error: No se encuentran los inputs con ID 'usuario' o 'contrasena'");
                alert("Error técnico: IDs de formulario no encontrados");
                return;
            }

            const correoLimpio = inputUsuario.value.trim();
            const passLimpia = inputContrasena.value.trim();

            if (correoLimpio === "" || passLimpia === "") {
                alert("Por favor, no dejes campos vacíos");
                return;
            }

            const payload = {
                correo: correoLimpio,
                contrasena: passLimpia
            };

            console.log("Enviando datos:", payload); // Para que veas en consola qué sale

            fetch('../Services/validarUser.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            // 3. Mejoramos la captura de errores por si el PHP manda algo que no es JSON
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en el servidor: " + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log("Respuesta recibida:", data);
                if (data.success) {
                    // Si el login es correcto, redirigimos
                    window.location.href = "Pagina_principal.php";
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error en la petición:", error);
                alert("Hubo un fallo en la conexión con el servidor");
            });
        });
    }
});