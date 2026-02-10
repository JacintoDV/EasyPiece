<?php
require_once '../Clases/Notificacion.php';

header('Content-Type: application/json');

try {
    $host = "localhost";
    $db_name = "easypiece";
    $user = "root";
    $pass = "#J4c1nt0";

    $conexion = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// 2. INSTANCIAR LA CLASE (Pasándole la conexión que acabamos de crear)
$notifControl = new Notificacion($conexion);

$metodo = $_SERVER['REQUEST_METHOD'];
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 3. SWITCH DE VERBOS HTTP
switch ($metodo) {
    
    case 'GET':
        $userId = $_GET['usuario_id'] ?? $data['usuario_id'] ?? null;

        if ($userId) {
            $res = $notifControl->leerPorUsuario($userId);
            echo json_encode([
                "success" => true,
                "datos" => $res,
                "contador" => count($res)
            ]);
        } else {
            // Solo mandamos el error 400 si alguien entra DIRECTO al archivo API
            // Si viene por el Service (vía require de la Clase), se queda callado.
            if (basename($_SERVER['PHP_SELF']) == 'notificacionesAPI.php') {
                http_response_code(400);
                echo json_encode([
                    "success" => false, 
                    "error" => "No se proporcionó usuario_id"
                ]);
            }
        }
        break;

        

    case 'POST':
        $userId  = $data['usuario_id'] ?? null;
        $mensaje = $data['mensaje'] ?? null;
        $tipo    = $data['tipo'] ?? 'info';

        if ($userId && $mensaje) {
            $resultado = $notifControl->crear($userId, $mensaje, $tipo);
            
            if ($resultado) {
                // Obtenemos el ID que la DB generó automáticamente
                $nuevoId = $conexion->lastInsertId(); 
                echo json_encode([
                    "success" => true, 
                    "mensaje" => "Notificación guardada",
                    "id_generado" => $nuevoId
                ]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo insertar"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Faltan datos (usuario_id o mensaje)"]);
        }
        break;

    case 'PUT':
        $notifId = $data['id'] ?? null;
        if ($notifId) {
            $resultado = $notifControl->marcarComoLeida($notifId);
            echo json_encode(["success" => $resultado, "mensaje" => "Leída"]);
        }
        break;

    case 'DELETE':
        $notifId = $data['id'] ?? null;
        if ($notifId) {
            $resultado = $notifControl->borrar($notifId);
            echo json_encode(["success" => $resultado, "mensaje" => "Eliminada"]);
        }
        break;
}