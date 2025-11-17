<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['correo']) && !isset($_GET['contrasena'])) {
        
        // 👉 SOLO VERIFICAR CORREO
        $correo = $_GET['correo'];

        $stmt = $conn->prepare("SELECT 1 FROM clientes WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        echo json_encode(["existe" => ($result->num_rows > 0)]);
    
    } elseif (isset($_GET['correo']) && isset($_GET['contrasena'])) {
        
        // 👉 CORREO + CONTRASEÑA → VALIDAR LOGIN
        $correo = $_GET['correo'];
        $contrasena = $_GET['contrasena'];

        $stmt = $conn->prepare("SELECT contrasena FROM clientes WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(["correcto" => false]); // correo no existe
            exit;
        }

        $row = $result->fetch_assoc();
        $passwordDB = $row["contrasena"];

        // 👉 SI TUS CONTRASEÑAS NO ESTÁN ENCRIPTADAS:
        if ($contrasena === $passwordDB) {
            echo json_encode(["correcto" => true]);
        } else {
            echo json_encode(["correcto" => false]);
        }

    } else {
        // 👉 SIN PARÁMETROS → devolver todos los clientes
        $result = $conn->query("SELECT * FROM clientes");
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
    break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $contrasena = $input['contrasena'];
        $codigo = $input['codigo'];
        $nombre = $input['nombre'];
        $correo = $input['correo'];
        $dir = $input['direccion'] ?? "NULL";
        $telefono = $input['telefono'];
        $registro = $input['registro'] ?? "NULL";
        $fecha_nacimiento = $input['nacimiento'];
        $conn->query("INSERT INTO clientes (contrasena, codigo, nombre, correo, direccion, telefono, registro, fecha_nacimiento) VALUES ('$contrasena', $codigo, '$nombre', '$correo', " . ($dir === "NULL" ? "NULL" : $dir) . ", $telefono, " . ($registro === "NULL" ? "NULL" : $registro) . ", '$fecha_nacimiento')");
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $codigo = $input['codigo']; 
        $nombre = $input['nombre'];
        $correo = $input['correo'];
        $telefono = $input['telefono'];

        $sql = "UPDATE clientes 
                SET nombre = '$nombre', correo = '$correo', telefono = $telefono
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

        $sql = "DELETE FROM clientes WHERE codigo = $codigo";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        break;


}

?>
