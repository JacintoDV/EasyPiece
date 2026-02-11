// 1. ESTADO GLOBAL DEL CARRITO
let carrito = [];

// 2. FUNCIÓN DE SELECCIÓN (LOGICA Y VISUAL)
function toggleSeleccion(elemento, producto) {
    elemento.classList.toggle('seleccionado');
    const index = carrito.findIndex(item => item.codigo === producto.codigo);

    if (index > -1) {
        carrito.splice(index, 1);
    } else {
        carrito.push(producto);
    }
    sessionStorage.setItem('carrito_farmacia', JSON.stringify(carrito));
    console.log("Guardado en sesión:", carrito);
    console.log("Contenido del carrito:", carrito);
}

// 3. FUNCIÓN PRINCIPAL DE CARGA
async function cargarPantallaPrincipal() {
    const contenedor = document.getElementById("contenedor-productos");

    try {
        const respuesta = await fetch("../services/obtenerProductos.php");
        
        if (!respuesta.ok) {
            throw new Error("No se pudo conectar con el servidor");
        }

        const productos = await respuesta.json();
        contenedor.innerHTML = "";

        if (productos.length === 0) {
            contenedor.innerHTML = "<p>No hay productos disponibles.</p>";
            return;
        }

        // RENDERIZADO DE PRODUCTOS
        productos.forEach(p => {
            // Importante: añadimos la clase 'card-producto' y el 'data-codigo'
            contenedor.innerHTML += `
                <div class='contenedor-izquierda card-producto' data-codigo='${p.codigo}'>
                    <h2 class='Titulo'>${p.nombre}</h2>
                    <div class='contenedor-abajo'>
                        <img src='img/medicamentos/${p.imagen}' 
                            alt='${p.nombre}' 
                            class='imagen-medicamentos' 
                            onerror="this.onerror=null; this.src='img/medicamentos/default.png';">
                        
                        <div class='texto-derecha'>
                            <p class='titulo-nombre'>Nombre</p>
                            <p class='info-nombre'>${p.nombre}</p>
                            
                            <p class='titulo-cantidad'>Presentación</p>
                            <p class='info-cantidad'>${p.presentacion} (${p.dosis})</p>
                            
                            <p class='titulo-laboratorio'>Laboratorio</p>
                            <p class='info-laboratorio'>${p.marca}</p>
                            
                            <p class='titulo-precio'>Precio</p>
                            <p class='info-precio'>${p.precio_formateado} COP</p>

                            <p class='info-cantidad' style='color: #2c3e50; font-weight: bold;'>
                                Estado: ${p.texto_stock}
                            </p>
                        </div>
                    </div>      
                </div>
            `;
        });

        // --- MANEJO DE EVENTOS (CLIC EN PRODUCTO) ---
        contenedor.addEventListener("click", (e) => {
            // Buscamos la tarjeta más cercana al clic
            const tarjeta = e.target.closest(".card-producto");
            
            if (tarjeta) {
                const codigo = tarjeta.getAttribute("data-codigo");
                // Buscamos los datos del producto en el array que vino del fetch
                const productoData = productos.find(p => p.codigo == codigo);
                
                if (productoData) {
                    toggleSeleccion(tarjeta, productoData);
                }
            }
        });

        // --- BUSCADOR EN TIEMPO REAL ---
        const buscador = document.getElementById("buscador");
        if(buscador) {
            buscador.addEventListener("keyup", (e) => {
                const texto = e.target.value.toLowerCase();
                const cards = document.querySelectorAll(".card-producto");

                cards.forEach(card => {
                    const nombre = card.querySelector(".Titulo").textContent.toLowerCase();
                    card.style.display = nombre.includes(texto) ? "block" : "none";
                });
            });
        }

    } catch (error) {
        console.error("Error en el fetch:", error);
        contenedor.innerHTML = "<p>Error al cargar el catálogo de productos.</p>";
    }
}

// 4. INICIALIZACIÓN
document.addEventListener("DOMContentLoaded", () => {
    cargarPantallaPrincipal();
});