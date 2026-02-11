const usuarioP = document.getElementById('info-usuario-clic');
const registro = document.getElementById('registro-perfil');
const listaRegistro = document.getElementById('cont-info'); // Contenedor info
const contenedor = document.getElementById('lista-registro'); // Contenedor historial

if (usuarioP && registro && listaRegistro && contenedor) {
    usuarioP.addEventListener('click', function() {
        contenedor.classList.remove('activo');
        contenedor.classList.add('inactivo');
        listaRegistro.classList.add('activo');
        listaRegistro.classList.remove('inactivo');
    });

    registro.addEventListener('click', function() {
        listaRegistro.classList.remove('activo');
        listaRegistro.classList.add('inactivo');
        contenedor.classList.add('activo');
        contenedor.classList.remove('inactivo');

        // --- NUEVO: Cargar los datos reales cuando se hace clic en Registro ---
        cargarDatosReales();
    });
}

// Función para traer los datos del Service
function cargarDatosReales() {
    console.log("Intentando conectar con el servicio...");

    // Usamos la ruta absoluta para evitar errores de carpeta
    fetch('/EasyPiece_Nuevo/services/obtenerRegistro.php')
        .then(res => {
            console.log("Respuesta del servidor (Status):", res.status);
            if (!res.ok) throw new Error("Archivo no encontrado en la ruta");
            return res.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data); // Mira esto en la consola (F12)
            
            if (data.success) {
                contenedor.innerHTML = ""; 
                
                if (!data.datos || data.datos.length === 0) {
                    contenedor.innerHTML = "<p style='text-align:center; padding:20px;'>No hay registros de compra.</p>";
                    return;
                }

                let htmlFinal = "";
                data.datos.forEach(reg => {
                    const totalFmt = new Intl.NumberFormat('es-CO', {
                        style: 'currency', currency: 'COP', minimumFractionDigits: 0
                    }).format(reg.total);

                    htmlFinal += `
                        <div class="contenedor-registro collapsed">
                            <h2 class="titulo-registro">Compra #${reg.id} - ${totalFmt}</h2>
                            <p class="info-registro">
                                <b>Fecha:</b> ${reg.fecha}<br>
                                <b>Método:</b> ${reg.metodo_pago}<br>
                                <b>Estado:</b> ${reg.estado}<br>
                                <b>Factura:</b> ${reg.factura ? reg.factura : 'Sin factura'}
                            </p>
                        </div>`;
                });
                contenedor.innerHTML = htmlFinal;
            } else {
                console.error("La API respondió success: false", data);
            }
        })
        .catch(err => {
            console.error("Error crítico en el fetch:", err);
            contenedor.innerHTML = `<p style='color:red; text-align:center; padding:20px;'>
                Error al cargar: ${err.message}. <br> Revisa la consola (F12).
            </p>`;
        });
}

// --- Tu lógica de Edición (Se mantiene igual) ---
function transformarAInput(spanElemento) {
    if (spanElemento) {
        const nuevoInput = document.createElement('input');
        nuevoInput.value = spanElemento.innerText;
        nuevoInput.className = spanElemento.className;
        nuevoInput.id = spanElemento.id; 
        spanElemento.replaceWith(nuevoInput);
    }
}

function transformarASpan(inputElemento) {
    if (inputElemento) {
        const nuevoSpan = document.createElement('span');
        nuevoSpan.innerText = inputElemento.value;
        nuevoSpan.className = inputElemento.className;
        nuevoSpan.id = inputElemento.id;
        inputElemento.replaceWith(nuevoSpan);
    }
}

const botonEditar = document.querySelector('.contenedor-informacion button');

if (botonEditar) {
    botonEditar.addEventListener('click', function() {
        const ids = ['editable-telefono', 'editable-correo', 'editable-direccion', 'editable-trajeta'];

        if (this.innerText === "Editar") {
            ids.forEach(id => {
                const span = document.getElementById(id);
                transformarAInput(span);
            });
            this.innerText = "Guardar Cambios";
        } else {
            ids.forEach(id => {
                const input = document.getElementById(id);
                transformarASpan(input);
            });
            this.innerText = "Editar";
            // Aquí podrías añadir un fetch para guardar los cambios en la DB
        }
    });
}

// Lógica de acordeón para las cajas cargadas dinámicamente
document.addEventListener("click", (e) => {
    const notificacion = e.target.closest(".contenedor-registro");
    if (!notificacion) return;
    notificacion.classList.toggle("expanded");
    notificacion.classList.toggle("collapsed");
});