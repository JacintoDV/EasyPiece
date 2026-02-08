<?php
header("Content-Type: application/json");
require_once "../Clases/Cliente.php";
// 1. CONFIGURACIÓN ÚNICA DE CONEXIÓN
$host = "localhost";
$user = "root";
$pass = "#J4c1nt0";
$db   = "EasyPiece";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Error de conexión: " . $conn->connect_error]));
}

// 2. DETECTAR EL MÉTODO HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        if (isset($_GET['correo'])) {
            $correoProporcionado = $_GET['correo'];
            
            // Agregamos 'nombre' y 'contrasena' a la consulta
            $stmt = $conn->prepare("SELECT codigo, nombre, contrasena FROM clientes WHERE correo = ?");
            $stmt->bind_param("s", $correoProporcionado);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                // Obtenemos los datos para enviarlos en el JSON
                $usuario = $resultado->fetch_assoc();

                echo json_encode([
                    "existe" => true,
                    "mensaje" => "El correo ya está registrado",
                    "datos" => $usuario // Esto es lo que el Service leerá para el login
                ]);
            } else {
                echo json_encode([
                    "existe" => false, 
                    "mensaje" => "Correo disponible"
                ]);
            }
            exit; 
        } else {
            // Listar todos (sigue funcionando igual)
            $res = $conn->query("SELECT * FROM clientes");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        }
        break;

    // --- POST: SET / AGREGAR (INSERTAR) ---
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        // YA NO USAMOS password_hash aquí, porque la Clase Cliente ya lo hizo.
        // Solo tomamos el hash que viene en el JSON.
        $passHash = $data['contrasena']; 

        $sql = "INSERT INTO clientes (contrasena, codigo, nombre, correo, direccion, telefono, registro, fecha_nacimiento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Se mantiene igual, pero ahora $passHash es el hash real ($2y$10...)
        $stmt->bind_param("sisssiis", 
            $passHash, 
            $data['codigo'], 
            $data['nombre'], 
            $data['correo'], 
            $data['direccion'], 
            $data['telefono'], 
            $data['registro'], 
            $data['fecha_nacimiento']
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "mensaje" => "Cliente agregado"]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        break;

    case 'DELETE':
        // 1. Leer el JSON para obtener el código del cliente a eliminar
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['codigo'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Se requiere el 'codigo' para eliminar el registro"]);
            break;
        }

        $codigo = (int)$data['codigo'];

        // 2. Preparar la sentencia DELETE
        $stmt = $conn->prepare("DELETE FROM clientes WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);

        if ($stmt->execute()) {
            // Verificar si el registro existía y fue borrado
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "mensaje" => "Cliente eliminado correctamente"]);
            } else {
                echo json_encode(["success" => false, "error" => "No se encontró ningún cliente con ese código"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        break;

    // --- PUT: UPDATE / ACTUALIZAR ---
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        $sql = "UPDATE clientes SET nombre = ?, correo = ?, direccion = ?, telefono = ?, fecha_nacimiento = ? 
                WHERE codigo = ?";
        
        $stmt = $conn->prepare($sql);
        // tipos: s, s, i, i, s, i
        $stmt->bind_param("ssiisi", 
            $data['nombre'], 
            $data['correo'], 
            $data['direccion'], 
            $data['telefono'], 
            $data['fecha_nacimiento'], 
            $data['codigo']
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "mensaje" => "Cliente actualizado"]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

$conn->close();
?>