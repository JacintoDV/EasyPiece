<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => $conn->connect_error]));
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT 
                    f.id AS factura_id, 
                    f.id_usuario,
                    p.nombre AS medicamento, 
                    f.cantidad, 
                    p.precio AS precio_unitario,
                    f.iva,
                    f.cupon
                FROM factura f
                INNER JOIN productos p ON f.producto = p.codigo";

        $result = $conn->query($sql);
        $data = [];

        if ($result) {
            while($row = $result->fetch_assoc()) { 
                $data[] = $row; 
            }
            echo json_encode($data);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        
        // 1. Validaciones: Ahora incluimos 'id_usuario' como obligatorio
        if (!isset($input['producto']) || empty($input['producto'])) {
            echo json_encode(["success" => false, "error" => "El campo 'producto' es obligatorio."]);
            break;
        }
        if (!isset($input['id_usuario']) || empty($input['id_usuario'])) {
            echo json_encode(["success" => false, "error" => "El campo 'id_usuario' es obligatorio."]);
            break;
        }

        $usuario = (int)$input['id_usuario']; // El ID de Jacinto (123454200)
        $prod    = (int)$input['producto'];
        $cant    = isset($input['cantidad']) ? (int)$input['cantidad'] : 1;
        $iva     = isset($input['iva']) ? (float)$input['iva'] : 0;
        $cupon   = isset($input['cupon']) && $input['cupon'] !== null ? (int)$input['cupon'] : "NULL";

        // 2. Insertamos incluyendo el id_usuario
        $sql = "INSERT INTO factura (id_usuario, cupon, producto, cantidad, iva) 
                VALUES ($usuario, $cupon, $prod, $cant, $iva)";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true, 
                "id" => $conn->insert_id,
                "mensaje" => "Factura creada correctamente para el usuario $usuario"
            ]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['id'])) {
            echo json_encode(["success" => false, "error" => "ID de factura no proporcionado"]);
            break;
        }
        $id = (int)$input['id'];
        $sql = "DELETE FROM factura WHERE id = $id";
        if ($conn->query($sql)) {
            if ($conn->affected_rows > 0) {
                echo json_encode(["success" => true, "mensaje" => "Factura eliminada"]);
            } else {
                echo json_encode(["success" => false, "error" => "No se encontró la factura con ID $id"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Error al eliminar la factura."]);
        }
        break;
}
$conn->close();
?>