<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM pedido");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];
        $precio = $input['precio'];
        $receptor = $input['receptor'];
        $dir = $input['direccion'];
        $sol = $input['solicitante'] ?? "NULL";
        $estado = $input['estado'];
        $dom = $input['domiciliario'] ?? "NULL";
        $conn->query("INSERT INTO pedido (solicitante, id, precio, receptor, domiciliario, direccion, estado) 
            VALUES (" . ($sol === "NULL" ? "NULL" : $sol) . ", $id, $precio, '$receptor'," . ($dom === "NULL" ? "NULL" : $dom) . ", '$dir', '$estado')");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['id']; 
        $precio = $input['precio'];
        $rec = $input['receptor'];
        $dir = $input['direccion'];
        $estado = $input['estado'];

        $sql = "UPDATE pedido 
                SET precio = $precio, receptor = '$rec', direccion = '$dir', estado = '$estado'
                WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];

        $sql = "DELETE FROM pedido WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>
