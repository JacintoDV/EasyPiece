<?php
// 1. Cargamos la API (porque ella tiene la conexión y la instancia de la clase)
// Subimos un nivel porque la API está en ../API/
require_once '../API/notificacionesAPI.php'; 

echo "<h2>Probando Clase a través de la API</h2>";

// 2. No creamos conexión nueva. 
// Usamos directamente $notifControl que ya fue instanciado en la API.
if (isset($notifControl)) {
    echo "✅ La API le pasó la conexión a la clase correctamente.<br>";
    
    $idPrueba = 123454200;
    $lista = $notifControl->leerPorUsuario($idPrueba);
    
    echo "<b>Resultado:</b> Se encontraron " . count($lista) . " notificaciones para Jacinto.";
} else {
    echo "❌ No se pudo acceder al objeto de la clase.";
}
?>