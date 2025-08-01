document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("usuario");
    const imgCorreo = document.getElementById("img-correo");
    const imgInfo = document.getElementById("img-invalido");
    const crearCuenta = document.querySelector(".crear-usuario");
    const volver = document.querySelector(".regresar");

    // Solo ejecuta la validación si el input existe en esta página
    if (input && imgCorreo && imgInfo) {
        input.addEventListener("input", () => {
            const valor = input.value.trim().toLowerCase();

            if (valor.endsWith("@gmail.com") || valor.endsWith("@hotmail.com")) {
                imgCorreo.style.display = "none";
                imgInfo.style.display = "none";
                input.classList.remove("input-error");
            } else {
                imgCorreo.style.display = "block";
                imgInfo.style.display = "block";
                input.classList.add("input-error");
            }
        });
    }

    // Solo si existe el enlace a crear cuenta
    if (crearCuenta) {
        crearCuenta.addEventListener("click", () => {
            window.location.href = "Creacion_Cuenta.php";
        });
    }

    // Solo si existe el enlace a volver a inicio
    if (volver) {
        volver.addEventListener("click", () => {
            window.location.href = "Inicio_sesion_EasyPiece.php";
        });
    }

    function validarCampoSoloLetras(idInput, idImgError, idImgInvalido) {
        const campo = document.getElementById(idInput);
        const imgError = document.getElementById(idImgError);
        const imgInvalido = document.getElementById(idImgInvalido);

        if (campo && imgError && imgInvalido) {
            campo.addEventListener("input", () => {
                const valor = campo.value.trim();
                const soloLetras = /^[a-zA-Z\s]*$/;

                if (valor && !soloLetras.test(valor)) {
                    imgError.style.display = "block";
                    imgInvalido.style.display = "block";
                    campo.classList.add("input-error");
                } else {
                    imgError.style.display = "none";
                    imgInvalido.style.display = "none";
                    campo.classList.remove("input-error");
                }
            });
        }
    }

    validarCampoSoloLetras("nombres", "img-nombres", "img-invalido-nombres");
    validarCampoSoloLetras("apellidos", "img-apellidos", "img-invalido-apellidos");
    
    const inputID = document.getElementById("id");
    const imgID = document.getElementById("img-id");
    const imgInvalidoID = document.getElementById("img-invalido-id");
    
    if (inputID && imgID && imgInvalidoID) {
        inputID.addEventListener("input", () => {
            const soloNumeros = /^[0-9]+$/;
    
            if (soloNumeros.test(inputID.value.trim())) {
                imgID.style.display = "none";
                imgInvalidoID.style.display = "none";
                inputID.classList.remove("input-error");
            } else {
                imgID.style.display = "block";
                imgInvalidoID.style.display = "block";
                inputID.classList.add("input-error");
            }
        });
    }

    flatpickr("#fecha", {
        dateFormat: "Y-m-d", // ✅ Este formato es compatible directamente con MySQL
        allowInput: true,
        locale: {
        firstDayOfWeek: 1
        }
    });


});



  