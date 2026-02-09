document.addEventListener("DOMContentLoaded", async () => {
    await cargarPantallaPrincipal();
});

async function cargarPantallaPrincipal() {
    const contenedor = document.getElementById("contenedor-productos");

    try {
        // Como Pagina_principal.php está en UI/, subimos un nivel para ir a services/
        const respuesta = await fetch("../services/obtenerProductos.php");
        
        if (!respuesta.ok) {
            throw new Error("No se pudo conectar con el servidor");
        }

        const productos = await respuesta.json();

        // Limpiamos el mensaje de "Cargando productos..."
        contenedor.innerHTML = "";

        if (productos.length === 0) {
            contenedor.innerHTML = "<p>No hay productos disponibles.</p>";
            return;
        }

        productos.forEach(p => {
            // Usamos los nombres de variables que devuelve tu API/Clase Producto
            contenedor.innerHTML += `
                <div class='contenedor-izquierda'>
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

    } catch (error) {
        console.error("Error en el fetch:", error);
        contenedor.innerHTML = "<p>Error al cargar el catálogo de productos.</p>";
    }

    // Buscador en tiempo real
    document.getElementById("buscador").addEventListener("keyup", (e) => {
        const texto = e.target.value.toLowerCase();
        const productos = document.querySelectorAll(".contenedor-izquierda");

        productos.forEach(producto => {
            // Buscamos el nombre dentro del H2 de cada tarjeta
            const nombreProducto = producto.querySelector(".Titulo").textContent.toLowerCase();
            
            if (nombreProducto.includes(texto)) {
                producto.style.display = "block"; // Se muestra si coincide
            } else {
                producto.style.display = "none";  // Se oculta si no coincide
            }
        });
    });
}