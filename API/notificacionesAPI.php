<?php
// Usamos require_once para no duplicar la carga de la clase
require_once __DIR__ . '/../Clases/Notificacion.php';

require_once __DIR__ . '/../config/conexion.php'; 

$es_llamada_directa = (basename($_SERVER['PHP_SELF']) == 'notificacionesAPI.php');
$metodo = $_SERVER['REQUEST_METHOD'] ?? null;

if ($metodo && $es_llamada_directa) {
    header('Content-Type: application/json');
    
    // Instanciamos la clase (usará la $conexion que ya viene de conexion.php)
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