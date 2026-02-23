<?php
// 1. Configuración de cabeceras y errores
header("Content-Type: application/json; charset=UTF-8");
session_start();

// Ocultamos errores para que no ensucien el JSON, pero los registramos internamente
error_reporting(E_ALL);
ini_set('display_errors', 0); 

// Iniciamos buffer para limpiar cualquier salida accidental (espacios, warnings)
ob_start();

try {
    // 2. Cargar Clase (Usamos ruta absoluta para evitar fallos de carpeta)
    require_once __DIR__ . "/../Clases/Cliente.php";
    require_once __DIR__ . "/../config/conexion.php";

    $metodo = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"), true);
    $respuesta = [];

    switch ($metodo) {
        case 'GET':
    // Buscar por el código de sesión
    if (isset($_GET['codigo'])) {
        $stmt = $conn->prepare("SELECT codigo, nombre, correo, telefono, direccion, fecha_nacimiento, rol FROM clientes WHERE codigo = ?");
        $stmt->bind_param("i", $_GET['codigo']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($usuario = $resultado->fetch_assoc()) {
            $respuesta = ["success" => true, "datos" => $usuario];
        } else {
            $respuesta = ["success" => false, "mensaje" => "Usuario no encontrado"];
        }
    } 
    // Opción B: Tu lógica anterior de verificar si un correo ya existe (para el registro)
    else if (isset($_GET['correo'])) {
        $stmt = $conn->prepare("SELECT codigo, nombre, rol FROM clientes WHERE correo = ?");
        $stmt->bind_param("s", $_GET['correo']);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($usuario = $resultado->fetch_assoc()) {
            $respuesta = ["existe" => true, "mensaje" => "El correo ya está registrado", "datos" => $usuario];
        } else {
            $respuesta = ["existe" => false, "mensaje" => "Correo disponible"];
        }
    } 
    // Opción C: Listar todos (Solo si eres admin, por ejemplo)
    else {
        $res = $conn->query("SELECT codigo, nombre, correo, rol FROM clientes");
        $respuesta = $res ? $res->fetch_all(MYSQLI_ASSOC) : ["error" => $conn->error];
    }
    break;

        case 'POST':
            if (!$data) {
                $respuesta = ["success" => false, "mensaje" => "JSON vacío"];
                break;
            }

            if (isset($data['accion']) && $data['accion'] === 'login') {
                $clienteObj = new Cliente($conn, []); 
                $usuario = $clienteObj->autenticarDirecto($data['correo'] ?? '', $data['contrasena'] ?? '');
                if ($usuario) {
                    $_SESSION['usuario_id'] = $usuario['codigo'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_rol'] = $usuario['rol'];
                    $respuesta = ["success" => true, "mensaje" => "Bienvenido", "usuario" => $usuario];
                } else {
                    $respuesta = ["success" => false, "mensaje" => "Credenciales incorrectas"];
                }
            } else {
                // REGISTRO
                $dir = is_numeric($data['direccion']) ? (int)$data['direccion'] : null;
                $reg = is_numeric($data['registro']) ? (int)$data['registro'] : null;

                // CAMBIO: Hasheamos la contraseña antes de insertarla
                $passHasheada = password_hash($data['contrasena'], PASSWORD_DEFAULT);

                $sql = "INSERT INTO clientes (contrasena, codigo, nombre, correo, direccion, telefono, registro, fecha_nacimiento, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $rol = $data['rol'] ?? 'cliente';
                    // Se usa $passHasheada y mantenemos los tipos (s para correo asegura que no sea 0)
                    $stmt->bind_param("sissssiss", $passHasheada, $data['codigo'], $data['nombre'], $data['correo'], $dir, $data['telefono'], $reg, $data['fecha_nacimiento'], $rol);
                    $respuesta = $stmt->execute() ? ["success" => true, "mensaje" => "Registro exitoso"] : ["success" => false, "error" => $stmt->error];
                }
            }
            break;

        case 'PUT':
        $sql = "UPDATE clientes SET correo = ?, direccion = ?, telefono = ?, tarjeta = ? WHERE codigo = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // "sssss" significa que todos se tratarán como strings (más seguro para teléfonos y tarjetas)
            // Asegúrate de enviar 'codigo' en el cuerpo del JSON ($data)
            $stmt->bind_param("ssssi", 
                $data['correo'], 
                $data['direccion'], 
                $data['telefono'], 
                $data['tarjeta'],
                $data['codigo']
            );
            
            if ($stmt->execute()) {
                $respuesta = ["success" => true, "mensaje" => "Perfil actualizado correctamente"];
            } else {
                $respuesta = ["success" => false, "error" => $stmt->error];
            }
        } else {
            $respuesta = ["success" => false, "error" => $conn->error];
        }
        break;

        default:
            http_response_code(405);
            $respuesta = ["error" => "Método no soportado"];
            break;
    }

    $conn->close();

} catch (Exception $e) {
    $respuesta = ["success" => false, "error" => $e->getMessage()];
}

ob_clean(); 
echo json_encode($respuesta);
exit;