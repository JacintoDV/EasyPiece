<?php
require_once __DIR__ . "/../config/conexion.php";
class Registro {

    private $api_url;

    public function __construct() {
        $this->api_url = BASE_URL . "/API/registroAPI.php";
    }

    public function crear($idCliente, $total, $metodoPago = 'Efectivo', $estado = 'Pendiente', $idFactura = null) {
        $datos = [
            "cliente"     => $idCliente,
            "total"       => $total,
            "metodo_pago" => $metodoPago,
            "estado"      => $estado,
            "factura"     => $idFactura
        ];

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        // CAMBIO 2: Opciones de estabilidad
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Espera 5 seg para conectar
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);        // 10 seg máximo de ejecución
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        
        // DEBUG: Si algo falla, esto nos dirá qué fue en el log
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["success" => false, "error" => "Error de cURL: " . $error];
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function listar($idCliente = null) {
        $url = $this->api_url;

        // Si pasamos un ID, lo concatenamos a la URL
        if (!empty($idCliente)) {
            // Usamos http_build_query para que cree "?cliente=123454200"
            $params = ['cliente' => $idCliente];
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }

        // Aquí vendría tu curl_init o file_get_contents
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Elimina un registro por ID
     */
    public function eliminar($id) {
        $datos = ["id" => $id];

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
}
?>