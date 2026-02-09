<?php
// services/obtenerProductos.php
header("Content-Type: application/json");
require_once "../Clases/Producto.php"; // Subimos un nivel para buscar la carpeta Clases

// 1. Conexión a la base de datos
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

// Verificar si hay error de conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// 2. Consulta a la tabla
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

$conn->close();
?>