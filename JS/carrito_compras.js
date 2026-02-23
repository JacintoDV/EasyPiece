document.addEventListener("DOMContentLoaded", () => {
    const listaCarrito = document.getElementById("lista-carrito");
    const detalleFactura = document.getElementById("detalle-factura");
    
    const productos = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];

    if (productos.length === 0) {
        listaCarrito.innerHTML = "<h2 style='text-align:center; margin-top:50px;'>El carrito está vacío</h2>";
        return;
    }

    listaCarrito.innerHTML = "";
    detalleFactura.innerHTML = ""; 

    productos.forEach(p => {
        // Usamos p.cantidad que es el nombre real en tu DB
        const stockReal = p.cantidad; 

        listaCarrito.innerHTML += `
            <div class='Contenedor-producto'>
                <h2 class='Titulo'>${p.nombre}</h2>
                <img src='img/medicamentos/${p.imagen}' alt='${p.nombre}' class='imagen-medicamentos'>
                <div class='info'>
                    <span class='titulo-nombre'>Nombre</span>
                    <span class='info-nombre'>${p.nombre}</span>
                    <span class='titulo-cantidad'>Presentación</span>
                    <span class='info-cantidad'>${p.presentacion}</span>
                    <span class='titulo-precio'>Precio</span>
                    <span class='info-precio'>${p.precio_formateado} COP</span>
                    <span class='titulo-laboratorio' style="color: #666;">Stock disponible</span>
                    <span class='info-laboratorio' style="font-weight: bold; color: ${stockReal < 5 ? 'red' : 'green'}">${stockReal} u.</span>
                </div> 
            </div>`;

        detalleFactura.innerHTML += `
            <div class='Factura-productos'>
                <span class='nombres-presentacion' style="font-weight: bold;">Producto:</span>
                <span class='nombres-presentacion'>${p.nombre}</span>
                <span class='cantidad' style="font-weight: bold;">Cantidad:</span>
                <div style="display: inline-flex; align-items: center; gap: 5px;">
                    <button type="button" onclick="cambiarCant('${p.codigo}', -1)" style="width:25px; cursor:pointer;">-</button>
                    <span class='cantidad' id="cant-${p.codigo}" style="min-width: 20px; text-align: center;">1</span>
                    <button type="button" onclick="cambiarCant('${p.codigo}', 1)" style="width:25px; cursor:pointer;">+</button>
                </div>
                <span class='valor-total' style="font-weight: bold;">Total:</span>   
                <span class='valor-total' id="total-${p.codigo}">${p.precio_formateado}</span>
            </div>`;
    });
});

function cambiarCant(codigo, cambio) {
    const spanCant = document.getElementById(`cant-${codigo}`);
    const spanTotal = document.getElementById(`total-${codigo}`);
    
    if (!spanCant || !spanTotal) return;

    const productos = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];
    const producto = productos.find(p => p.codigo == codigo);

    if (!producto) return;

    let cantidadActual = parseInt(spanCant.innerText);
    let nuevaCantidad = cantidadActual + cambio;

    if (nuevaCantidad < 1) return;

    // Validación usando la propiedad 'cantidad' del objeto
    if (nuevaCantidad > producto.cantidad) {
        alert(`⚠️ Solo hay ${producto.cantidad} unidades disponibles.`);
        return;
    }

    spanCant.innerText = nuevaCantidad;
    const nuevoTotal = producto.precio * nuevaCantidad;
    spanTotal.innerText = `$${nuevoTotal.toLocaleString('es-CO')}.00`;
}

document.querySelector(".boton-pagar").addEventListener("click", async () => {
    const detalleFactura = document.getElementById("detalle-factura");
    const filas = detalleFactura.querySelectorAll(".Factura-productos");
    
    if (filas.length === 0) return;

    const productosSession = JSON.parse(sessionStorage.getItem('carrito_farmacia')) || [];
    const datosVenta = [];

    filas.forEach(fila => {
        const spanCant = fila.querySelector("span[id^='cant-']");
        if (spanCant) {
            const codigoProducto = spanCant.id.replace("cant-", "");
            const cantidad = parseInt(spanCant.innerText);
            const infoProd = productosSession.find(p => p.codigo == codigoProducto);

            datosVenta.push({
                producto: parseInt(codigoProducto),
                cantidad: cantidad,
                precio: infoProd ? infoProd.precio : 0,
                iva: 16 
            });
        }
    });

    const btnPagar = document.querySelector(".boton-pagar");
    btnPagar.disabled = true;
    btnPagar.innerText = "Procesando...";

    try {
        const respuesta = await fetch("../services/procesarFactura.php", {
            method: "POST",
            body: JSON.stringify(datosVenta),
            headers: { "Content-Type": "application/json" }
        });

        const resultado = await respuesta.json();

        if (resultado.success) {
            alert("✅ " + resultado.mensaje); 
            sessionStorage.removeItem('carrito_farmacia'); 
            window.location.href = "Pagina_principal.php"; 
        } else {
            alert("❌ Error: " + (resultado.error || "No se pudo procesar el pago"));
            btnPagar.disabled = false;
            btnPagar.innerText = "Pagar";
        }

    } catch (error) {
        console.error("❌ Error:", error);
        alert("No se pudo conectar con el servidor.");
        btnPagar.disabled = false;
        btnPagar.innerText = "Pagar";
    }
});