<?php
require_once __DIR__ . "/../Clases/Registro.php";

$modelo = new Registro();

// Datos de prueba
$usuario_id = 123454200; 
$monto_test = 55000;

echo "<h2>🧪 Test Unitario: Clase Registro</h2>";

// 1. PROBAR CREACIÓN (Mandamos factura como NULL para evitar bloqueos de FK)
echo "<b>1. Probando método crear():</b> ";
$resCrear = $modelo->crear($usuario_id, $monto_test, 'Efectivo', 'Completado', null);

if (isset($resCrear['success']) && $resCrear['success'] == true) {
    $id_recien_creado = $resCrear['id'];
    echo "<span style='color:green'>✅ ÉXITO. ID generado: $id_recien_creado</span><br>";
} else {
    echo "<span style='color:red'>❌ FALLÓ</span><br>";
    echo "<b>Respuesta cruda de la API:</b><pre>";
    print_r($resCrear); 
    echo "</pre>";
}

echo "<hr>";

// 2. PROBAR ELIMINACIÓN (Si se pudo crear, lo borramos de una vez)
if (isset($id_recien_creado)) {
    echo "<b>2. Probando método eliminar() para el ID $id_recien_creado:</b> ";
    $resBorrar = $modelo->eliminar($id_recien_creado);
    
    if (isset($resBorrar['success']) && $resBorrar['success'] == true) {
        echo "<span style='color:green'>✅ ÉXITO. Registro borrado correctamente.</span><br>";
    } else {
        echo "<span style='color:red'>❌ FALLÓ al borrar.</span><br>";
        print_r($resBorrar);
    }
} else {
    echo "<i>Saltando prueba de eliminación porque no se pudo crear el registro.</i><br>";
}

echo "<hr>";

// 3. PROBAR LISTADO
echo "<b>3. Probando método listar():</b> ";
$resLista = $modelo->listar($usuario_id);

// Manejamos si la API devuelve el array directo o envuelto en 'datos'
$items = isset($resLista['datos']) ? $resLista['datos'] : $resLista;

if (is_array($items)) {
    echo "<span style='color:green'>✅ ÉXITO. Se recuperaron " . count($items) . " registros.</span><br>";
} else {
    echo "<span style='color:red'>❌ FALLÓ al listar.</span><br>";
}