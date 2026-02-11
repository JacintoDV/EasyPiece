<?php
// Usamos require_once para no duplicar la carga de la clase
require_once __DIR__ . '/../Clases/Notificacion.php';

// 1. CONEXIÓN A LA BASE DE DATOS
try {
    $host = "localhost";
    $db_name = "easypiece";
    $user = "root";
    $pass = "#J4c1nt0";

    $conexion = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hacemos que la conexión esté disponible para la clase globalmente
    $GLOBALS['conexion'] = $conexion;

} catch (PDOException $e) {
    // Solo enviamos error JSON si la petición es vía Web
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "error" => "Error de conexion: " . $e->getMessage()]);
    }
    exit;
}

/**
 * 2. LÓGICA DE CONTROLADOR (SWITCH)
 * La clave: Solo se ejecuta si el archivo es llamado directamente.
 * Si es un 'require' desde el Service, este bloque se ignora.
 */
$es_llamada_directa = (basename($_SERVER['PHP_SELF']) == 'notificacionesAPI.php');
$metodo = $_SERVER['REQUEST_METHOD'] ?? null;

if ($metodo && $es_llamada_directa) {
    header('Content-Type: application/json');
    
    // Instanciamos la clase (ella misma pescará la $conexion global)
    $notifControl = new Notificacion();
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

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
                http_response_code(400);
                echo json_encode(["success" => false, "error" => "No se proporciono usuario_id"]);
            }
            break;

        case 'POST':
            $userId  = $data['usuario_id'] ?? null;
            $mensaje = $data['mensaje'] ?? null;
            $tipo    = $data['tipo'] ?? 'info';

            if ($userId && $mensaje) {
                $resultado = $notifControl->crear($userId, $mensaje, $tipo);
                echo json_encode([
                    "success" => $resultado, 
                    "mensaje" => $resultado ? "Notificacion guardada" : "Error al insertar",
                    "id_generado" => $conexion->lastInsertId()
                ]);
            } else {
                echo json_encode(["success" => false, "error" => "Faltan datos"]);
            }
            break;

        case 'PUT':
            $notifId = $data['id'] ?? null;
            if ($notifId) {
                $resultado = $notifControl->marcarComoLeida($notifId);
                echo json_encode(["success" => $resultado, "mensaje" => "Estado actualizado"]);
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
    // Terminamos la ejecución para evitar que ruidos externos ensucien el JSON
    exit;
}