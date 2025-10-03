<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM direccion");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $calle = $input['calle'];
        $id = $input['id'];
        $crra = $input['carrera'];
        $brrio = $input['barrio'];
        $ciudad = $input['ciudad'];
        $dept = $input['departamento'];
        $pais = $input['pais'] ;
        $desc = $input['descripcion'];
        $conn->query("INSERT INTO direccion (calle, id, carrera, barrio, ciudad, departamento, pais, descripcion) 
            VALUES ('$calle', $id, '$crra', '$brrio', '$ciudad', '$dept', '$pais', '$desc')");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $calle = $input['calle'];
        $id = $input['id'];
        $crra = $input['carrera'];
        $brrio = $input['barrio'];
        $ciudad = $input['ciudad'];
        $dept = $input['departamento'];
        $pais = $input['pais'];
        $desc = $input['descripcion'];

        $sql = "UPDATE direccion 
                SET calle = '$calle', carrera = '$crra', barrio = '$brrio', ciudad= '$ciudad', departamento = '$dept', pais= '$pais', descripcion='$desc'
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

        $sql = "DELETE FROM direccion WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>