<?php
// 1. Simular sesión
session_start();
$_SESSION['usuario_id'] = 123454200; 

echo "<h2>Probando Lógica Interna del Service</h2>";

// 2. Simular los datos que el service normalmente lee de 'php://input'
$dataSimulada = [
    "correo" => "test_interno@correo.com",
    "direccion" => "Calle Falsa 123",
    "telefono" => "123456",
    "tarjeta" => "9999",
    "codigo" => $_SESSION['usuario_id']
];

try {
    // 3. Importar la clase directamente para ver si falla el require
    echo "Cargando Clase Cliente...<br>";
    require_once __DIR__ . "/../Clases/Cliente.php";
    echo "✅ Clase cargada.<br>";

    // 4. Ejecutar la lógica de la clase
    echo "Llamando a actualizarPerfil...<br>";
    $obj = new Cliente();
    $res = $obj->actualizarPerfil($dataSimulada);

    echo "<h3>Resultado:</h3><pre>";
    print_r($res);
    echo "</pre>";

} catch (Throwable $e) {
    echo "<h3 style='color:red'>❌ ERROR DETECTADO:</h3>";
    echo "<b>Mensaje:</b> " . $e->getMessage() . "<br>";
    echo "<b>Archivo:</b> " . $e->getFile() . " en línea " . $e->getLine();
}