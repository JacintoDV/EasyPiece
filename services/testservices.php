<?php
/**
 * TEST DE INTEGRACIÓN (TERMINAL): obtenerRegistros.php
 */

// Colores para la terminal
$verde = "\033[0;32m";
$rojo  = "\033[0;31m";
$azul  = "\033[0;34m";
$reset = "\033[0m";

// URL del Service
$url_service = "http://127.0.0.1/EasyPiece_Nuevo/services/obtenerRegistro.php";

echo "\n" . str_repeat("=", 50) . "\n";
echo "🧪 TEST DE INTEGRACIÓN: obtenerRegistros.php\n";
echo str_repeat("=", 50) . "\n";

echo "📡 Conectando con: $url_service ...\n";

// 1. Realizar la petición
$ch = curl_init($url_service);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 2. Validar Estado HTTP
if ($http_code == 200) {
    echo "[$verde OK $reset] Estado HTTP: 200\n";
} else {
    echo "[$rojo ERROR $reset] Estado HTTP: $http_code\n";
    exit(1);
}

// 3. Validar JSON
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "[$rojo ERROR $reset] El Service no devolvió un JSON válido.\n";
    echo "Respuesta recibida:\n$response\n";
    exit(1);
}

echo "[$verde OK $reset] Formato JSON: Válido\n";

// 4. Validar Estructura y Datos
if (isset($data['success']) && $data['success'] === true) {
    echo "[$verde OK $reset] Campo 'success': TRUE\n";
    
    $num_registros = count($data['datos']);
    echo "📊 Registros encontrados: $num_registros\n";
    
    if ($num_registros > 0) {
        echo "\n$azul--- VISTA PREVIA DEL PRIMER REGISTRO ---$reset\n";
        print_r($data['datos'][0]);
        echo "$verde" . str_repeat("=", 50) . "\n";
        echo "✅ TEST EXITOSO: Service listo para producción.\n";
        echo str_repeat("=", 50) . "$reset\n\n";
    } else {
        echo "⚠️  Atención: El Service funciona pero la lista está vacía.\n\n";
    }
} else {
    echo "[$rojo ERROR $reset] El Service respondió success: false\n";
    echo "Mensaje: " . ($data['mensaje'] ?? 'Sin error especificado') . "\n\n";
}