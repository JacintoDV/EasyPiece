<?php
class Factura {
    private $api_url = "http://localhost/EasyPiece_Nuevo/API/facturaAPI.php";

    public function listar() {
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    // 1. Agregamos $id_usuario como parámetro obligatorio
    public function crear($id_usuario, $producto, $cantidad, $iva, $cupon = null) {
        $datos = [
            "id_usuario" => $id_usuario, // 2. Lo incluimos en el array de envío
            "producto"   => $producto,
            "cantidad"   => $cantidad,
            "iva"        => $iva,
            "cupon"      => $cupon
        ];

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

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