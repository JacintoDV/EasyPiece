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
            
            $stmt = $conn->prepare("SELECT codigo, nombre, contrasena, rol FROM clientes WHERE correo = ?");
            $stmt->bind_param("s", $correoProporcionado);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                echo json_encode([
                    "existe" => true,
                    "mensaje" => "El correo ya está registrado",
                    "datos" => $usuario 
                ]);
            } else {
                echo json_encode(["existe" => false, "mensaje" => "Correo disponible"]);
            }
            exit; 
        } else {
            // ESTO ES LO QUE TE FALTA PARA VER LOS DATOS EN THUNDER CLIENT
            // Si no hay correo en la URL, listamos TODO.
            $sql = "SELECT * FROM clientes";
            $res = $conn->query($sql);

            if ($res) {
                $todos = $res->fetch_all(MYSQLI_ASSOC);
                echo json_encode($todos);
            } else {
                echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
            }
            exit;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(["success" => false, "mensaje" => "No se recibieron datos (JSON vacío)"]);
            exit;
        }

        
        if (isset($data['accion']) && $data['accion'] === 'login') {
            if (!isset($data['correo']) || !isset($data['contrasena'])) {
                echo json_encode(["success" => false, "mensaje" => "Faltan credenciales para el login"]);
                exit;
            }

            $clienteObj = new Cliente([], $conn); 
            $usuario = $clienteObj->autenticarDirecto($data['correo'], $data['contrasena']);

            if ($usuario) {
                $_SESSION['usuario_id']     = $usuario['codigo'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol']    = $usuario['rol'];

                echo json_encode([
                    "success" => true,
                    "mensaje" => "Bienvenido " . $usuario['nombre'],
                    "usuario" => $usuario
                ]);
            } else {
                echo json_encode(["success" => false, "mensaje" => "Correo o contraseña incorrectos"]);
            }
            exit;
        }

        if (!isset($data['codigo']) || empty($data['codigo'])) {
            echo json_encode(["success" => false, "error" => "Error de Registro: El campo 'codigo' es obligatorio."]);
            exit;
        }

        $passHash = $data['contrasena'];
        $rol = $data['rol'] ?? 'cliente';

        $sql = "INSERT INTO clientes (contrasena, codigo, nombre, correo, direccion, telefono, registro, fecha_nacimiento, rol) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("sisssisss", 
            $passHash, 
            $data['codigo'], 
            $data['nombre'], 
            $data['correo'], 
            $data['direccion'], 
            $data['telefono'], 
            $data['registro'], 
            $data['fecha_nacimiento'],
            $rol
        );

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $data['codigo'];
            $_SESSION['usuario_nombre'] = $data['nombre'];
            $_SESSION['usuario_rol'] = $rol;

            echo json_encode([
                "success" => true, 
                "mensaje" => "Cliente registrado y sesión iniciada",
                "session" => $_SESSION 
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "Error en DB: " . $stmt->error]);
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