<?php
// 1. Iniciamos sesión con @ para evitar que errores de buffer rompan el JSON
@session_start();

require_once __DIR__ . "/../Clases/Factura.php"; 
require_once __DIR__ . "/../Clases/Registro.php"; 
require_once __DIR__ . "/../Clases/Notificacion.php"; 

header('Content-Type: application/json');

// Identidad (Sesión o Jacinto por defecto para el test)
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 123454200;

if (!$id_usuario) {
    echo json_encode(['success' => false, 'error' => 'No se pudo identificar al usuario.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Carrito vacio o datos invalidos.']);
    exit;
}

try {
    $errores = [];
    $exitos = 0;
    $totalVenta = 0; 
    $idFacturaReferencia = null; 

    $modeloFactura = new Factura();
    $modeloRegistro = new Registro(); 
    $modeloNotificacion = new Notificacion();

    // 2. Procesamos cada producto
    foreach ($input as $item) {
        $resultado = $modeloFactura->crear(
            $id_usuario,
            $item['producto'],
            $item['cantidad'],
            $item['iva'] ?? 16
        );

        if ($resultado && isset($resultado['success']) && $resultado['success'] == true) {
            $exitos++;
            $idFacturaReferencia = $resultado['id'];
            
            if(isset($item['precio'])) {
                $totalVenta += ($item['precio'] * $item['cantidad']);
            }
        } else {
            $errores[] = "Producto " . ($item['producto'] ?? '?') . ": " . ($resultado['error'] ?? 'Error');
        }
    }

    // 3. Si todo salio bien en facturacion, creamos el Registro de pago
    if ($exitos > 0 && empty($errores)) {
        
        $totalFinal = ($totalVenta > 0) ? $totalVenta : 0; 
        $totalFmt = number_format($totalFinal, 0, ',', '.');

        $resRegistro = $modeloRegistro->crear(
            $id_usuario,
            $totalFinal,
            'Efectivo',   
            'Completado', 
            $idFacturaReferencia 
        );

        if ($resRegistro && isset($resRegistro['success']) && $resRegistro['success'] == true) {
            
            // --- 🔔 MENSAJE DE NOTIFICACIÓN PERSONALIZADO ---
            $mensajeNotif = "Compra realizada Nro: #$idFacturaReferencia. Se realizo un pago por $$totalFmt COP con exito.";
            
            $modeloNotificacion->crear($id_usuario, $mensajeNotif, 'success');
            // ------------------------------------------------

            // --- 🚀 RESPUESTA DEL SISTEMA (JSON) ---
            echo json_encode([
                'success' => true, 
                'mensaje' => "Compra realizada Nro: #$idFacturaReferencia. El pago de $$totalFmt COP fue procesado correctamente.",
                'detalles' => [
                    'total' => $totalFinal,
                    'registro_id' => $resRegistro['id'],
                    'factura_relacionada' => $idFacturaReferencia
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'error' => "Pago no registrado.",
                'detalle' => $resRegistro['error'] ?? 'Error en RegistroAPI'
            ]);
        }

    } else {
        echo json_encode([
            'success' => false, 
            'error' => "No se pudo procesar la compra.",
            'detalles' => $errores
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error critico: ' . $e->getMessage()]);
}