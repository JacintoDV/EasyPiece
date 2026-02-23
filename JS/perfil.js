// --- 1. SELECCIÓN DE ELEMENTOS ---
const usuarioP = document.getElementById('info-usuario-clic');
const registro = document.getElementById('registro-perfil');
const listaRegistro = document.getElementById('cont-info'); // Contenedor info personal
const contenedor = document.getElementById('lista-registro'); // Contenedor historial compras

// --- 2. LÓGICA DE NAVEGACIÓN (Tabs) ---
if (usuarioP && registro && listaRegistro && contenedor) {
    usuarioP.addEventListener('click', function() {
        // Mostrar Info Personal, Ocultar Historial
        contenedor.classList.remove('activo');
        contenedor.classList.add('inactivo');
        listaRegistro.classList.add('activo');
        listaRegistro.classList.remove('inactivo');
    });

    registro.addEventListener('click', function() {
        // Mostrar Historial, Ocultar Info Personal
        listaRegistro.classList.remove('activo');
        listaRegistro.classList.add('inactivo');
        contenedor.classList.add('activo');
        contenedor.classList.remove('inactivo');

        // Cargar los datos desde el servidor
        cargarDatosReales();
    });
}

// --- 3. CONSUMO DE API (FETCH) ---
function cargarDatosReales() {
    console.log("Iniciando carga de historial...");
    
    // Feedback visual mientras carga
    contenedor.innerHTML = "<p style='text-align:center; padding:20px;'>Cargando tus compras...</p>";

    // Usamos ruta relativa al Service
    fetch('../services/obtenerRegistro.php')
        .then(res => {
            if (!res.ok) throw new Error("No se pudo conectar con el servicio");
            return res.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data);
            
            if (data.success) {
                contenedor.innerHTML = ""; // Limpiar mensaje de carga
                
                if (!data.datos || data.datos.length === 0) {
                    contenedor.innerHTML = "<p style='text-align:center; padding:20px;'>Aún no tienes registros de compra.</p>";
                    return;
                }

                let htmlFinal = "";
                data.datos.forEach(reg => {
                    // Convertir a número para evitar errores en el formateador
                    const valor = parseFloat(reg.total) || 0;
                    
                    const totalFmt = new Intl.NumberFormat('es-CO', {
                        style: 'currency', 
                        currency: 'COP', 
                        minimumFractionDigits: 0
                    }).format(valor);

                    htmlFinal += `
                        <div class="contenedor-registro collapsed">
                            <h2 class="titulo-registro">Compra #${reg.id} - ${totalFmt}</h2>
                            <p class="info-registro">
                                <b>Fecha:</b> ${reg.fecha}<br>
                                <b>Método:</b> ${reg.metodo_pago}<br>
                                <b>Estado:</b> <span class="estado-${reg.estado.toLowerCase()}">${reg.estado}</span><br>
                                <b>Factura:</b> ${reg.factura ? reg.factura : 'Pendiente'}
                            </p>
                        </div>`;
                });
                contenedor.innerHTML = htmlFinal;
            } else {
                throw new Error(data.mensaje || "Error desconocido");
            }
        })
        .catch(err => {
            console.error("Error crítico:", err);
            contenedor.innerHTML = `<p style='color:red; text-align:center; padding:20px;'>
                Error: ${err.message}
            </p>`;
        });
}

// --- 4. LÓGICA DE EDICIÓN DE PERFIL ---
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

// --- 4. LÓGICA DE EDICIÓN DE PERFIL ---

// ... (mantén tus funciones transformarAInput y transformarASpan igual) ...

const botonEditar = document.querySelector('.contenedor-informacion button');

if (botonEditar) {
    botonEditar.addEventListener('click', function() {
        const ids = ['editable-telefono', 'editable-correo', 'editable-direccion', 'editable-trajeta'];

        if (this.innerText === "Editar") {
            // CAMBIO A MODO EDICIÓN
            ids.forEach(id => {
                const span = document.getElementById(id);
                transformarAInput(span);
            });
            this.innerText = "Guardar Cambios";
        } else {
            // GUARDAR CAMBIOS
            
            // 1. Recolectamos los datos de los INPUTS antes de transformarlos a spans
            const datosParaGuardar = {
                telefono: document.getElementById('editable-telefono').value,
                correo: document.getElementById('editable-correo').value,
                direccion: document.getElementById('editable-direccion').value,
                tarjeta: document.getElementById('editable-trajeta').value
            };

            console.log("Enviando datos a actualizarDatos.php:", datosParaGuardar);

            // 2. Llamamos al Service que ya probamos
            fetch('../services/actualizarDatos.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datosParaGuardar)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    alert("✅ Perfil actualizado correctamente");
                } else {
                    alert("❌ Error al guardar: " + (response.error || response.mensaje));
                    // Opcional: Podrías recargar para revertir los cambios visuales si falló la DB
                    // location.reload();
                }
            })
            .catch(err => {
                console.error("Error en la petición:", err);
                alert("Hubo un fallo de conexión con el servidor.");
            });

            // 3. Transformamos los inputs de vuelta a SPAN para la vista
            ids.forEach(id => {
                const input = document.getElementById(id);
                transformarASpan(input);
            });

            this.innerText = "Editar";
        }
    });
}

// --- 5. EVENT DELEGATION (Acordeón) ---
// Se usa document.addEventListener porque las cajas se crean dinámicamente
document.addEventListener("click", (e) => {
    const notificacion = e.target.closest(".contenedor-registro");
    if (!notificacion) return;
    
    notificacion.classList.toggle("expanded");
    notificacion.classList.toggle("collapsed");
});

// --- NUEVA FUNCIÓN: CARGAR PERFIL PERSONAL ---
function cargarPerfilPersonal() {
    fetch('../services/obtenerDatosUser.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const u = data.datos;
                
                let nombreCompleto = u.nombre || '';
                let partes = nombreCompleto.trim().split(/\s+/); // Divide por espacios
                
                let nombreFinal = "";
                let apellidoFinal = "";

                if (partes.length >= 3) {
                    // Si tiene 3 o más palabras, tomamos las últimas 2 como apellido
                    apellidoFinal = partes.slice(-2).join(' ');
                    nombreFinal = partes.slice(0, -2).join(' ');
                } else if (partes.length === 2) {
                    // Si solo tiene 2 palabras, 1 nombre y 1 apellido
                    nombreFinal = partes[0];
                    apellidoFinal = partes[1];
                } else {
                    // Si solo hay una palabra
                    nombreFinal = partes[0] || 'N/A';
                    apellidoFinal = '---';
                }

                document.getElementById('perfil-nombre').innerText = nombreFinal;
                document.getElementById('perfil-apellido').innerText = apellidoFinal; 
                
                document.getElementById('editable-telefono').innerText = u.telefono || 'No registrado';
                document.getElementById('editable-correo').innerText = u.correo || '';
                document.getElementById('editable-direccion').innerText = u.direccion || 'No registrada';
                document.getElementById('perfil-codigo').innerText = u.codigo || '';
                
                document.getElementById('perfil-fecha').innerText = u.fecha_nacimiento || 'N/A';
                document.getElementById('editable-trajeta').innerText = u.tarjeta || 'No vinculada';
            } else {
                console.error("Error al obtener perfil:", data.mensaje);
            }
        })
        .catch(err => console.error("Error en fetch perfil:", err));
}

document.addEventListener('DOMContentLoaded', () => {
    cargarPerfilPersonal();
});