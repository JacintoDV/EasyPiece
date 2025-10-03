<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM trabajador");
        $data = [];
        while($row = $result->fetch_assoc()) { $data[] = $row; }
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $codigo = $input['codigo'];
        $dir = $input['direccion'] ?? "NULL";
        $cont = $input['contrasena'];
        $nombre = $input['nombre'];
        $cor = $input['correo'];
        $tel = $input['telefono'];
        $drog = $input['drogueria'];

        $conn->query("INSERT INTO trabajador (codigo, contraseña, nombre, correo, direccion, telefono, drogueria) 
            VALUES ($codigo, '$cont', '$nombre', '$cor'," . ($dir === "NULL" ? "NULL" : $dir) . ", $tel, $drog)");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $cod = $input['codigo']; 
        $cont = $input['contrasena'];
        $nombre = $input['nombre'];
        $correo = $input['correo']; 
        $tel = $input['telefono'];
        $drog = $input['drogueria'];

        $sql = "UPDATE trabajador 
                SET contraseña = '$cont', nombre = '$nombre', correo= '$correo', telefono=$tel, drogueria = $drog 
                WHERE codigo = $cod";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
    
    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $cod = $input['codigo'];

        $sql = "DELETE FROM trabajador WHERE codigo = $cod";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;
}

?>
