<?php
header("Content-Type: application/json");
require_once "../Clases/Producto.php"; // IMPORTANTE: Conectar con el archivo de la clase

$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $result = $conn->query("SELECT * FROM productos WHERE estado = 1");
        $productosObjetos = [];

        while($row = $result->fetch_assoc()) {
            // Instanciamos la clase para cada fila de la DB
            $prod = new Producto($row);
            
            // Creamos un array que incluya los datos de la DB + los métodos de la clase
            $item = $prod->toArray();
            $item['precio_formateado'] = $prod->getPrecioFormateado();
            $item['texto_stock'] = $prod->obtenerEstadoStock();
            
            $productosObjetos[] = $item;
        }
        
        echo json_encode($productosObjetos);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Usamos la clase para validar o procesar datos antes de insertar
        $nuevoProd = new Producto($input);
        
        $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, marca, presentacion, dosis, imagen) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        // Usamos los getters de la clase para asegurar que los datos estén limpios
        $nombre = $nuevoProd->getNombre();
        $desc   = $nuevoProd->getDescripcion();
        $prec   = $nuevoProd->getPrecio();
        $cant   = $nuevoProd->getCantidad();
        $marc   = $nuevoProd->getMarca();
        $pres   = $nuevoProd->getPresentacion();
        $dosis  = $nuevoProd->getDosis();
        $img    = $nuevoProd->getImagen();

        $stmt->bind_param("ssdissss", $nombre, $desc, $prec, $cant, $marc, $pres, $dosis, $img);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "id" => $conn->insert_id]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        break;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);
            
            
            $sql = "UPDATE productos SET cantidad = cantidad - ? WHERE codigo = ? AND cantidad >= ?";
            
            $stmt = $conn->prepare($sql);
            $codigo = $input['codigo'];
            $cantidad = $input['cantidad'];

            // Pasamos la cantidad dos veces: una para restar y otra para la validación del WHERE
            $stmt->bind_param("iii", $cantidad, $codigo, $cantidad);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(["success" => true, "mensaje" => "Stock actualizado"]);
                } else {
                    echo json_encode(["success" => false, "error" => "Stock insuficiente o código no encontrado"]);
                }
            } else {
                echo json_encode(["success" => false, "error" => $stmt->error]);
            }
            break;
}
$conn->close();
?>