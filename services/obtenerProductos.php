<?php
// services/obtenerProductos.php
header("Content-Type: application/json");
require_once "../Clases/Producto.php"; // Subimos un nivel para buscar la carpeta Clases
require_once __DIR__ . "/../config/conexion.php";

// 2. Consulta a la tabla (usamos la variable $conn que vive dentro de conexion.php)
$sql = "SELECT * FROM productos WHERE estado = 1";
$result = $conn->query($sql);

$productosObjetos = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        // 3. Instanciamos la clase Producto para cada fila
        $prod = new Producto($row);
        
        // 4. Preparamos el array con los datos formateados de la clase
        $item = $prod->toArray();
        $item['precio_formateado'] = $prod->getPrecioFormateado();
        $item['texto_stock'] = $prod->obtenerEstadoStock();
        
        $productosObjetos[] = $item;
    }
}

// 5. Devolvemos el JSON final
echo json_encode($productosObjetos);

// 6. Cerramos la conexión (opcional, pero buena práctica)
if (isset($conn)) {
    $conn->close();
}
?>