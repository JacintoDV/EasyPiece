<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../config/conexion.php";

$input = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER['REQUEST_METHOD']) {

   case 'GET':
        // Recibe el código por la URL: ?cliente=12345
        $codigo = $_GET['cliente'] ?? null;

        if ($codigo) {
            // Busca en la tabla 'registro' donde la columna 'cliente' es igual al código
            $stmt = $conn->prepare("SELECT * FROM registro WHERE cliente = ?");
            $stmt->bind_param("s", $codigo); 
            $stmt->execute();
            $result = $stmt->get_result();
            
            $lista = [];
            while($row = $result->fetch_assoc()) { 
                $lista[] = $row; 
            }
            echo json_encode($lista);
        } else {
            echo json_encode(["error" => "No se recibio el codigo"]);
        }
        break;
    case 'POST':
        if (!$input) {
            echo json_encode(["success" => false, "error" => "No se recibieron datos JSON"]);
            break;
        }

        $cliente = (!empty($input['cliente']) && $input['cliente'] !== "NULL") ? intval($input['cliente']) : "NULL";
        $total   = isset($input['total']) ? floatval($input['total']) : 0;
        $metodo  = isset($input['metodo_pago']) ? $conn->real_escape_string($input['metodo_pago']) : 'Efectivo';
        $estado  = isset($input['estado'])      ? $conn->real_escape_string($input['estado'])      : 'Pendiente';
        $fac     = (!empty($input['factura']) && $input['factura'] !== "NULL") ? intval($input['factura']) : "NULL";

        $sql = "INSERT INTO registro (cliente, total, metodo_pago, estado, factura) 
                VALUES ($cliente, $total, '$metodo', '$estado', $fac)";

        if ($conn->query($sql)) {
            echo json_encode([
                "success" => true, 
                "id" => $conn->insert_id,
                "mensaje" => "Registro creado correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "error" => $conn->error, 
                "sql_ejecutado" => $sql
            ]);
        }
        break;

    case 'DELETE':
        $id = $input['id'] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
            break;
        }

        $sql = "DELETE FROM registro WHERE id = $id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
}

$conn->close();
?>