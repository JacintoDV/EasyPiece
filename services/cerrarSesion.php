<?php
session_start();
session_unset();    // Limpia todas las variables ($_SESSION)
session_destroy();  // Destruye la sesión físicamente

header('Content-Type: application/json');
echo json_encode(["success" => true, "mensaje" => "Sesión cerrada"]);
exit;