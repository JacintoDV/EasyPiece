<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM registro");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];
        $cliente = $input['cliente'] ?? "NULL";
        $fecha = $input['fecha'];
        $fac = $input['factura'] ?? "NULL";
        $conn->query("INSERT INTO registro (id, cliente, factura, fecha) 
            VALUES ($id," . ($cliente === "NULL" ? "NULL" : $cliente) . "," . ($fac === "NULL" ? "NULL" : $fac) . ", '$fecha')");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];

        $sql = "DELETE FROM registro WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>
