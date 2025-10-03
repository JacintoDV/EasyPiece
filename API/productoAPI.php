<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM producto");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $cod = $input['codigo'];
        $cant = $input['cantidad'];
        $precio = $input['precio'];
        $nombre = $input['nombre'];
        $marca = $input['marca'];
        $presentacion = $input['presentacion'];
        $dosis = $input['dosis'] ;
        $descripcion = $input['descripcion'];
        $conn->query("INSERT INTO producto (codigo, cantidad, precio, nombre, marca, presentacion, dosis, descripccion) 
            VALUES ($cod, $cant, $precio, '$nombre', '$marca', '$presentacion', '$dosis', '$descripcion')");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $precio = $input['precio'];
        $codigo = $input['codigo'];

        $sql = "UPDATE producto 
                SET precio = $precio
                WHERE codigo = $codigo";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $codigo = $input['codigo'];

        $sql = "DELETE FROM producto WHERE codigo = $codigo";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>