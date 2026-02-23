document.addEventListener("DOMContentLoaded", () => {
    const msg = document.getElementById("msg");
    const boton = document.getElementById('btnRegistrar');

    // Inicializar calendario
    flatpickr("#fecha", {
        dateFormat: "Y-m-d", // Formato estándar MySQL
        allowInput: true,
        locale: { firstDayOfWeek: 1 }
    });

    // Función para mostrar/ocultar contraseña
    function togglePassword(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        if(!input || !btn) return;

        btn.addEventListener("click", () => {
            input.type = (input.type === "password") ? "text" : "password";
        });
    }

    togglePassword("contrasena", "mostrarPW");
    togglePassword("contrasenados", "mostrarPWdos");

    // Recolectar datos del formulario
    function obtenerDatos() {
        return {
            accion: "registro", // Indispensable para que el Service use POST
            nombres: document.getElementById('nombre').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            correo: document.getElementById('correo').value.trim(),
            id: document.getElementById('id').value.trim(),
            telefono: document.getElementById('telefono').value.trim(), 
            contrasena: document.getElementById('contrasena').value,
            contrasenados: document.getElementById('contrasenados').value,
            fecha: document.getElementById('fecha').value
        };
    }

    function validarEspacioBlancos(d) {
        if (Object.values(d).some(value => value === "")) {
            alert("Por favor, complete todos los campos");
            return false;
        }
        return true;
    }

    function compararInputs(d) {
        if (d.contrasena !== d.contrasenados) {
            msg.textContent = "Las contraseñas no coinciden";
            msg.style.color = "red";
            return false;
        }
        msg.textContent = "";
        return true;
    }

    function validarFormatoCorreo(correo) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(correo)) {
            alert("El formato del correo es inválido");
            return false;
        }
        return true;
    }

    async function enviarDatosAlServidor(datos) {
        try {
            const response = await fetch('/EasyPiece_Nuevo/services/enviarDatosUsers.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            // Manejo de respuesta no-JSON (Errores 500 o texto)
            const texto = await response.text();
            let resultado;
            try {
                resultado = JSON.parse(texto);
            } catch (e) {
                console.error("Respuesta no válida del servidor:", texto);
                throw new Error("El servidor respondió con un formato incorrecto.");
            }
            
            if (resultado.success) {
                alert("¡Registro exitoso!");
                // Opcional: window.location.href = "login.php";
            } else {
                alert("Error: " + (resultado.error || resultado.mensaje));
            }
        } catch (error) {
            console.error("Error crítico:", error);
            alert("Hubo un problema al conectar con el servidor.");
        }
    }

    // Evento principal
    boton.addEventListener('click', async () => {
        const datos = obtenerDatos();

        if (validarEspacioBlancos(datos)) {
            if (compararInputs(datos)) {
                if (validarFormatoCorreo(datos.correo)) {
                    
                    // Bloqueo de UI
                    boton.disabled = true;
                    boton.textContent = "Registrando...";

                    await enviarDatosAlServidor(datos);

                    // Desbloqueo de UI
                    boton.disabled = false;
                    boton.textContent = "Registrar Cliente";
                }
            }
        }
    });
});