<?php
session_start();
require_once '../Clases/Cliente.php';

header('Content-Type: application/json');

// Leer los datos JSON enviados por el JS
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    // Instanciar la clase Cliente. Sin conexión porque el Service es cliente de la API.
    $cliente = new Cliente(null);

    // Mapear los datos recibidos
    $idOriginal  = $data['id'] ?? $data['codigo'] ?? null;
    $correoNuevo = isset($data['correo']) ? trim($data['correo']) : null;
    $nombres     = $data['nombres'] ?? '';
    $apellidos   = $data['apellidos'] ?? '';
    $accion      = $data['accion'] ?? 'update'; 

    // --- CAMBIO SOLICITADO: LÓGICA DE ID RANDOM ---
    // Si es un registro, concatenamos 4 dígitos aleatorios al ID
    $idFinal = $idOriginal;
    if ($accion === 'registro' && !empty($idOriginal)) {
        $random = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
        $idFinal = $idOriginal . $random;
    }

    // 1. Validar correo (vía cURL interno de la Clase)
    // Usamos el idFinal para la validación de coincidencia
    if ($correoNuevo && !$cliente->verificarCorreoCoincide($correoNuevo, $idFinal)) { 
        echo json_encode(["success" => false, "error" => "El correo ya está registrado por otro usuario"]);
        exit;
    }

    // 2. Cargar datos al objeto
    $cliente->setNombre(trim($nombres . " " . $apellidos));
    $cliente->setCorreo($correoNuevo);
    $cliente->setTelefono($data['telefono'] ?? '');
    $cliente->setFechaNacimiento($data['fecha'] ?? $data['fecha_nacimiento'] ?? '');
    $cliente->setCodigo($idFinal); // <--- Usamos el ID con el random
    $cliente->setDireccion(null); 
    $cliente->setRegistro(null);

    if (!empty($data['contrasena'])) {
        $cliente->setContrasena($data['contrasena']);
    }

    // 3. Comunicación con la API
    $urlApi = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php";
    $metodoHttp = ($accion === 'registro') ? "POST" : "PUT";
    
    $respuesta = $cliente->enviarALaApi($urlApi, $metodoHttp);
    
    // --- BLINDAJE CONTRA EL NULL ---
    if ($respuesta === null) {
        http_response_code(500);
        echo json_encode([
            "success" => false, 
            "error" => "La API no respondió un JSON válido. Revisa la URL o errores en clientesAPI.php",
            "url_intentada" => $urlApi
        ]);
        exit;
    }

    // 4. Actualizar sesión si fue un PUT exitoso
    if (isset($respuesta['success']) && $respuesta['success'] === true && $metodoHttp === "PUT") {
        $_SESSION['usuario_nombre'] = trim($nombres . " " . $apellidos);
    }
    
    // Enviar respuesta final al JS
    echo json_encode($respuesta);

} else {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "No se recibieron datos en el Service"]);
}