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
            
            if (valor === "") {
            // Campo vacío → no mostrar errores
            imgCorreo.style.display = "none";
            imgInfo.style.display = "none";
            input.classList.remove("input-error");}

            else if (valor.endsWith("@gmail.com") || valor.endsWith("@hotmail.com")) {
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

    validarCampoSoloLetras("nombre", "img-nombres", "img-invalido-nombres");
    validarCampoSoloLetras("apellidos", "img-apellidos", "img-invalido-apellidos");

    function validarCampoSoloNumeros(idInput, idImgError, idImgInvalido) {
        const campo = document.getElementById(idInput);
        const imgError = document.getElementById(idImgError);
        const imgInvalido = document.getElementById(idImgInvalido);

        if (campo && imgError && imgInvalido) {
            campo.addEventListener("input", () => {
                const valor = campo.value.trim();
                const soloNumeros = /^[0-9]*$/;

                if (valor && !soloNumeros.test(valor)) {
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

    validarCampoSoloNumeros("id", "img-id", "img-invalido-id");
    validarCampoSoloNumeros("telefono", "img-telefono", "img-invalido-telefono");

    notificaciones.forEach(notif => {
        notif.addEventListener("click", () => {
            const titulo = notif.querySelector(".titulo-notificacion").innerText;
            const info = notif.querySelector(".info-notificacion").innerText;
            alert("Has clic en: " + titulo + "\n" + info);

            notif.classList.toggle("expanded");
            notif.classList.toggle("collapsed");
        });
    });

    


});

const imgBuscar = document.getElementById('buscar-img');
const inputBuscar = document.getElementById('buscador'); 
let mostrar = false;

if (imgBuscar && inputBuscar) { // Verificamos que AMBOS existan
    imgBuscar.addEventListener('click', function() {
    mostrar = !mostrar;
        if (mostrar===true) {
            inputBuscar.style.visibility = "visible";
            inputBuscar.style.zIndex = "999"; 
        } else {
            inputBuscar.style.visibility = "hidden";
        }
        //console.log("¿Buscador visible?:", mostrar); Sirve para verificar la funcionalidad
    });
}
const imgNotificaciones= document.getElementById('notificaciones');
const imgCarrito = document.getElementById('carrito');
const imgContactanos= document.getElementById('contactanos');
const imgPrincipal = document.getElementById ('principal')

if(imgNotificaciones && imgCarrito && imgContactanos && imgPrincipal){
    imgNotificaciones.addEventListener('click', function(){
        window.location.href = "Notificaciones.php";
    })

    imgCarrito.addEventListener('click', function(){
        window.location.href ="Carro_compras.php";
    })

    imgContactanos.addEventListener('click', function(){
        alert("Este falta");
    })

    imgPrincipal.addEventListener ('click', function (){
        window.location.href = "Pagina_principal.php";
    })
}

const usuario = document.getElementById('usuario');
const info = document.querySelector('.opciones-usuario');

if (usuario && info) {
    usuario.addEventListener('click', function() {
        info.classList.toggle('activo');
    });
} 

const registroUno = document.getElementById('info-usuario-span')
const registroDos = document.getElementById('info-registro-span')

if (registroDos && registroUno){
    registroUno.addEventListener ('click', function(){
        window.location.href = "Perfil.php";
    })
}
const btnCerrar = document.getElementById('btn-cerrar-sesion');

if (btnCerrar) {
    btnCerrar.addEventListener('click', function() {
        // 1. Avisamos al servidor que destruya la sesión
        fetch('../Services/logout.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 2. Limpiamos el historial para que no puedan volver atrás
                    window.history.pushState(null, null, window.location.href);
                    window.onpopstate = function () {
                        window.history.go(1);
                    };
                    
                    // 3. Redirigimos al Login
                    window.location.replace('Inicio_sesion_EasyPiece.php'); 
                }
            })
            .catch(error => console.error("Error al cerrar sesión:", error));
    });
}