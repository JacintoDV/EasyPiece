// ¿Ejecuta cuando carga la página
document.addEventListener("DOMContentLoaded", () => {

    // Validar campos solo letras

    function validarCampoSoloLetras(idInput, idImgError, idImgInvalido) {
        const campo = document.getElementById(idInput);
        const imgError = document.getElementById(idImgError);
        const imgInvalido = document.getElementById(idImgInvalido);

        if (!campo) return;

        campo.addEventListener("input", () => {
            const soloLetras = /^[a-zA-Z\s]*$/;  // Solo letras + espacios
            const valor = campo.value.trim();
            const invalido = valor && !soloLetras.test(valor);

            // Muestra u oculta imágenes
            imgError.style.display = invalido ? "block" : "none";
            imgInvalido.style.display = invalido ? "block" : "none";

            campo.classList.toggle("input-error", invalido);
        });
    }

    validarCampoSoloLetras("nombres", "img-nombres", "img-invalido-nombres");
    validarCampoSoloLetras("apellidos", "img-apellidos", "img-invalido-apellidos");



    // Validar solo números en ID
    
    const inputID = document.getElementById("id");
    const imgID = document.getElementById("img-id");
    const imgInvalidoID = document.getElementById("img-invalido-id");

    if (inputID) {
        inputID.addEventListener("input", () => {
            const soloNumeros = /^[0-9]+$/;
            const valor = inputID.value.trim();
            const invalido = valor && !soloNumeros.test(valor);

            imgID.style.display = invalido ? "block" : "none";
            imgInvalidoID.style.display = invalido ? "block" : "none";

            inputID.classList.toggle("input-error", invalido);
        });
    }


    //Flatpickr Fecha
    if (typeof flatpickr !== "undefined") {
        flatpickr("#fecha", {
            dateFormat: "Y-m-d",   // Compatible MySQL
            allowInput: true,
            locale: {
                firstDayOfWeek: 1
            }
        });
    }

    const pass1 = document.getElementById("contrasena");
    const pass2 = document.getElementById("contrasenados");
    const msg = document.getElementById("msg");

    // función para validar
    function compararInputs() {
        const v1 = pass1.value.trim();
        const v2 = pass2.value.trim();

        // Si están vacías
        if (v1 === "" || v2 === "") {
            msg.textContent = "Complete ambas contraseñas";
            msg.style.color = "red";
            return false;
        }

        // No coinciden
        if (v1 !== v2) {
            msg.textContent = "Las contraseñas no coinciden";
            msg.style.color = "red";
            return false;
        }

        // Si coinciden
        msg.textContent = "";
        return true;
    }


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

    //Definición global para que sea visible desde el HTML
    window.verificarCorreo = async function () {
        const correo = document.getElementById("correo").value.trim();

        if (!correo) {
            alert("Por favor ingrese un correo");
            return;
        }

        if (!compararInputs()) {
        return; //DETIENE TODO
        }

        try {
            const response = await fetch(`../API/clientesAPI.php?correo=${encodeURIComponent(correo)}`);
            const data = await response.json();

            // Detectar si existe (cubre todos los formatos posibles)
            const existe = (
                data === true ||
                data === "true" ||
                data === 1 ||
                (data.existe !== undefined && (data.existe === true || data.existe === "true" || data.existe === 1))
            );

            // Si existe, no dejar continuar
            if (existe) {
                alert("El correo ya está registrado");
                return;
            }

            // Si NO existe
            if (compararInputs() === true) {

                // Unir nombre + apellido
                const nombre = document.getElementById("nombre").value.trim();
                const apellido = document.getElementById("apellidos").value.trim();
                const nombreCompleto = `${nombre} ${apellido}`;

                // Datos a enviar como JSON
                const datos = {
                    contrasena: document.getElementById("contrasena").value.trim(),
                    codigo: document.getElementById("id").value.trim(),
                    nombre: nombreCompleto,
                    correo: correo,
                    direccion: null,
                    telefono: document.getElementById("telefono").value.trim(),
                    registro: null,
                    nacimiento: document.getElementById("fecha").value.trim()
                };

                // Insertar en la API
                const responseInsert = await fetch("../API/clientesAPI.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify(datos)
                });

                const resultado = await responseInsert.json();

                if (resultado.success) {
                    alert("Usuario registrado correctamente");
                } else {
                    alert("Error al registrar el usuario");
                }
            }

        } catch (error) {
            alert("Error de conexión con el servidor");
            console.error("Error:", error);
        }
    };





});
