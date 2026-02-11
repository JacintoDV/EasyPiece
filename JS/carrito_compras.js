document.addEventListener("DOMContentLoaded", () => {
    const listaCarrito = document.getElementById("lista-carrito");
    const detalleFactura = document.getElementById("detalle-factura");
    
    // Usamos el nombre de tu clave en sessionStorage
    const productos = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];

    if (productos.length === 0) {
        listaCarrito.innerHTML = "<h2 style='text-align:center; margin-top:50px;'>El carrito está vacío</h2>";
        return;
    }

    listaCarrito.innerHTML = "";
    detalleFactura.innerHTML = ""; 

    productos.forEach(p => {
        // Tarjeta izquierda (Visual)
        listaCarrito.innerHTML += `
            <div class='Contenedor-producto'>
                <h2 class='Titulo'>${p.nombre}</h2>
                <img src='img/medicamentos/${p.imagen}' alt='${p.nombre}' class='imagen-medicamentos'>
                <div class='info'>
                    <span class='titulo-nombre'>Nombre</span>
                    <span class='info-nombre'>${p.nombre}</span>
                    <span class='titulo-cantidad'>Presentación</span>
                    <span class='info-cantidad'>${p.presentacion}</span>
                    <span class='titulo-laboratorio'>Laboratorio</span>
                    <span class='info-laboratorio'>${p.marca}</span>
                    <span class='titulo-precio'>Precio</span>
                    <span class='info-precio'>${p.precio_formateado} COP</span>  
                </div> 
            </div>`;

        // Detalle factura derecha (Lógica de cantidades)
        detalleFactura.innerHTML += `
            <div class='Factura-productos'>
                <span class='nombres-presentacion' style="font-weight: bold;">Producto:</span>
                <span class='nombres-presentacion'>${p.nombre}</span>
                <span class='cantidad' style="font-weight: bold;">Cantidad:</span>
                <button type="button" onclick="cambiarCant('${p.codigo}', -1)" style="width:20px; cursor:pointer;">-</button>
                <span class='cantidad' id="cant-${p.codigo}">1</span>
                <button type="button" onclick="cambiarCant('${p.codigo}', 1)" style="width:20px; cursor:pointer;">+</button>
                <span class='valor-total' style="font-weight: bold;">Total:</span>   
                <span class='valor-total' id="total-${p.codigo}">${p.precio_formateado}</span>
            </div>`;
    });
});

// --- FUNCIÓN PARA ACTUALIZAR CANTIDADES Y TOTALES VISUALES ---
function cambiarCant(codigo, cambio) {
    const spanCant = document.getElementById(`cant-${codigo}`);
    const spanTotal = document.getElementById(`total-${codigo}`);
    
    if (!spanCant || !spanTotal) return;

    let cantidadActual = parseInt(spanCant.innerText);
    let nuevaCantidad = cantidadActual + cambio;

    if (nuevaCantidad < 1) return; // No permitir menos de 1

    spanCant.innerText = nuevaCantidad;

    const productos = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];
    const producto = productos.find(p => p.codigo == codigo);

    if (producto) {
        // El total visual se calcula con el precio numérico del storage
        const nuevoTotal = producto.precio * nuevaCantidad;
        spanTotal.innerText = `$${nuevoTotal.toLocaleString('es-CO')}.00`;
    }
}

// --- EVENTO PRINCIPAL DE PAGO ---
document.querySelector(".boton-pagar").addEventListener("click", async () => {
    const detalleFactura = document.getElementById("detalle-factura");
    const filas = detalleFactura.querySelectorAll(".Factura-productos");
    
    if (filas.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    // Obtenemos los productos del storage para sacar los precios reales
    const productosSession = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];
    const datosVenta = [];

    filas.forEach(fila => {
        const spanCant = fila.querySelector("span[id^='cant-']");
        if (spanCant) {
            // Extraemos el código del ID del span (cant-101 -> 101)
            const codigoProducto = spanCant.id.replace("cant-", "");
            const cantidad = parseInt(spanCant.innerText);
            
            // Buscamos la info del producto en el storage para obtener el precio
            const infoProd = productosSession.find(p => p.codigo == codigoProducto);

            datosVenta.push({
                producto: parseInt(codigoProducto),
                cantidad: cantidad,
                precio: infoProd ? infoProd.precio : 0, // ¡Importante para el total en PHP!
                iva: 16 
            });
        }
    });

    console.log("🛒 Datos a enviar al servidor:", datosVenta);

    try {
        const respuesta = await fetch("../services/procesarFactura.php", {
            method: "POST",
            body: JSON.stringify(datosVenta),
            headers: { "Content-Type": "application/json" }
        });

        // Obtenemos la respuesta como texto primero para detectar errores de PHP
        const textoRespuesta = await respuesta.text();
        let resultado;
        
        try {
            resultado = JSON.parse(textoRespuesta);
        } catch (e) {
            console.error("❌ Error parseando JSON. El servidor respondió:", textoRespuesta);
            alert("Error crítico: El servidor no envió una respuesta válida.");
            return;
        }

        if (resultado.success) {
            alert("✅ " + resultado.mensaje); 
            // Limpiamos el carrito tras la compra exitosa
            sessionStorage.removeItem('carrito_farmacia'); 
            window.location.href = "Pagina_principal.php"; 
        } else {
            alert("❌ Error: " + (resultado.error || "No se pudo procesar el pago"));
            if (resultado.detalles_error) console.table(resultado.detalles_error);
        }

    } catch (error) {
        console.error("❌ Error en la comunicación fetch:", error);
        alert("No se pudo conectar con el servidor de facturación.");
    }
});