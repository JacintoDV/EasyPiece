document.addEventListener("DOMContentLoaded", () => {

    // Hacer la función visible en HTML
    window.iniciarSesion = async function () {

        const correo = document.getElementById("usuario").value.trim();
        const contrasena = document.getElementById("contraseña").value.trim();

        if (!correo || !contrasena) {
            alert("Por favor complete todos los campos");
            return;
        }

        try {
            // 1️⃣ VERIFICAR SI EL CORREO EXISTE
            const responseCorreo = await fetch(`../API/clientesAPI.php?correo=${encodeURIComponent(correo)}`);
            const dataCorreo = await responseCorreo.json();

            const correoExiste =
                dataCorreo === true ||
                dataCorreo === "true" ||
                dataCorreo === 1 ||
                (dataCorreo.existe !== undefined &&
                 (dataCorreo.existe === true || dataCorreo.existe === "true" || dataCorreo.existe === 1));

            if (!correoExiste) {
                alert("El correo no está registrado");
                return;
            }

            // 2️⃣ VERIFICAR CONTRASEÑA
            const responsePass = await fetch(`../API/clientesAPI.php?correo=${encodeURIComponent(correo)}&contrasena=${encodeURIComponent(contrasena)}`);
            const dataPass = await responsePass.json();

            const passwordCorrecta =
                dataPass === true ||
                dataPass === "true" ||
                dataPass === 1 ||
                (dataPass.correcto !== undefined &&
                 (dataPass.correcto === true || dataPass.correcto === "true" || dataPass.correcto === 1));

            if (!passwordCorrecta) {
                alert("La contraseña no es correcta");
                return;
            }

            // 3️⃣ SI TODO ES CORRECTO → REDIRIGIR
            window.location.href = "../UI/Pagina_principal.php";

        } catch (error) {
            alert("Error de conexión con el servidor");
            console.error("Error:", error);
        }
    };

});
