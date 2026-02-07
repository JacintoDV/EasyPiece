// ¿Ejecuta cuando carga la página
document.addEventListener("DOMContentLoaded", () => {
    const msg = document.getElementById("msg");


    flatpickr("#fecha", {
        dateFormat: "Y-m-d",   // Compatible con MySQL
        allowInput: true,
        locale: {
            firstDayOfWeek: 1
        }
    });


    function togglePassword(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);

        btn.addEventListener("click", () => {
            if (input.type === "password") {
                input.type = "text";   // muesta la PW
            } else {
                input.type = "password"; // oculta la PW
            }
        });
    }

    togglePassword("contrasena", "mostrarPW");
    togglePassword("contrasenados", "mostrarPWdos");

    function obtenerDatos() {
    return {
        nombres: document.getElementById('nombre').value,
        apellidos: document.getElementById('apellidos').value,
        correo: document.getElementById('correo').value,
        id: document.getElementById('id').value,
        telefono: document.getElementById('telefono').value, 
        contrasena: document.getElementById('contrasena').value,
        contrasenados: document.getElementById('contrasenados').value,
        fecha: document.getElementById('fecha').value
    };
}


function validarEspacioBlancos() {
    const d = obtenerDatos();
    // Verificamos si alguno está vacío
    if (d.nombres.trim() === "" || d.apellidos.trim() === "" || d.correo.trim() === "" || 
        d.id.trim() === "" || d.telefono.trim() === "" || d.fecha.trim() === "" || 
        d.contrasena.trim() === "" || d.contrasenados.trim() === "") {
        
        alert("Por favor, complete todos los campos");
        return false;
    }
    return true;
}


function compararInputs() {
    const v1 = document.getElementById('contrasena').value.trim();
    const v2 = document.getElementById('contrasenados').value.trim();

    if (v1 === "" || v2 === "") {
        msg.textContent = "Complete ambas contraseñas";
        msg.style.color = "red";
        return false;
    }
    if (v1 !== v2) {
        msg.textContent = "Las contraseñas no coinciden";
        msg.style.color = "red";
        return false;
    }
    msg.textContent = ""; // Limpiamos mensaje si todo está bien
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

        const resultado = await response.json();
        
        if (resultado.success) {
            alert("¡Registro exitoso!");
            // Aquí puedes limpiar el formulario o redirigir
        } else {
            alert("Error: " + resultado.error);
        }
    } catch (error) {
        console.error("Error crítico:", error);
    }
}

const boton = document.getElementById('btnRegistrar');

boton.addEventListener('click', async () => {
    if (validarEspacioBlancos()) {
        if (compararInputs()) {
            const datos = obtenerDatos();
            if (validarFormatoCorreo(datos.correo)) {
                boton.disabled = true;
                boton.textContent = "Registrando...";

                // Llamamos a la función que definimos antes
                await enviarDatosAlServidor(datos);

                // Reabilitamos el botón después de terminar
                boton.disabled = false;
                boton.textContent = "Registrar Cliente";
                
            }
        }
    }
});

    

    


});

