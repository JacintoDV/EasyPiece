<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM cupon");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];
        $fac = $input['factura'] ?? "NULL";
        $desc = $input['descuento'];
        $conn->query("INSERT INTO cupon (id, factura, descuento) 
            VALUES ($id, " . ($fac === "NULL" ? "NULL" : $fac) . ", $desc)");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $desc = $input['descuento'];
        $codigo = $input['codigo'];

        $sql = "UPDATE cupon 
                SET descuento = $desc
                WHERE id = $codigo";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $codigo = $input['codigo'];

        $sql = "DELETE FROM cupon WHERE id = $codigo";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>