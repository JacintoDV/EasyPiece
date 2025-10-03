<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM factura");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $cupon = $input['cupon'] ?? "NULL";
        $id = $input['id'];
        $prod = $input['producto'] ?? "NULL";
        $cant = $input['cantidad'];
        $iva = $input['iva'];
        $conn->query("INSERT INTO factura (cupon, id, producto, cantidad, iva) 
            VALUES ( " . ($cupon === "NULL" ? "NULL" : $cupon) . ", $id, " . ($prod === "NULL" ? "NULL" : $prod) . ", $cant, $iva)");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['id']; 
        $cant = $input['cantidad'];
        $iva = $input['iva'];

        $sql = "UPDATE factura 
                SET cantidad = $cant, iva = $iva
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

        $sql = "DELETE FROM factura WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>
