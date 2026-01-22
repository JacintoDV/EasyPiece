<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "#J4c1nt0", "EasyPiece");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['correo']) && !isset($_GET['contrasena'])) {
        
        // SOLO VERIFICAR CORREO
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

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        // 1. Validar que la decodificación fue exitosa
        if ($input === null) {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "error" => "Datos JSON inválidos"]);
            break;
        }
        
        // 2. Extraer y sanear los datos (mejor usando un operador de fusión null)
        $contrasena = $input['contrasena'] ?? '';
        $codigo = $input['codigo'] ?? 0;     // Si es un número, usamos null o 0
        $nombre = $input['nombre'] ?? '';
        $correo = $input['correo'] ?? '';
        $telefono = $input['telefono'] ?? null; // Si es un número, usamos null o 0
        $fecha_nacimiento = $input['nacimiento'] ?? '';

        // 3. Usar Sentencias Preparadas
        $sql = "INSERT INTO clientes (contrasena, codigo, nombre, correo, telefono, fecha_nacimiento) 
                VALUES (?, ?, ?, ?, ?, ?)";
                
        // Prepara la sentencia
        $stmt = $conn->prepare($sql);

        // Enlaza los parámetros (Tipos: s=string, i=integer, d=double, b=blob)
        // Asumo que codigo y telefono son INT (i) y el resto son STRING (s)
        $stmt->bind_param("sisiss", 
            $contrasena, 
            $codigo, 
            $nombre, 
            $correo, 
            $telefono, 
            $fecha_nacimiento
        );
        
        // Ejecuta la sentencia
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "id" => $stmt->insert_id]);
        } else {
            // En caso de fallo de inserción (ej. llave duplicada, tipo incorrecto)
            http_response_code(500); // Internal Server Error
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmt->close();
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $codigo = (int)$input['codigo']; 
        $nombre = $input['nombre'];
        $correo = $input['correo'];
        $telefono = (int)$input['telefono'];

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

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;

}

?>
