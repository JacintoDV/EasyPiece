<?php
class Cliente {
    // Atributos privados
    private $contrasena;
    private $codigo;
    private $nombre;
    private $correo;
    private $direccion;
    private $telefono;
    private $registro;
    private $fecha_nacimiento;
    private $conn; 

    public function __construct($datos = [], $conexion = null) {
        $this->conn = $conexion;
        $this->codigo = $datos['codigo'] ?? null;
        $this->correo = $datos['correo'] ?? null;
        $this->nombre = $datos['nombre'] ?? null;
        $this->direccion = $datos['direccion'] ?? null;
        $this->telefono = $datos['telefono'] ?? null;
        $this->registro = $datos['registro'] ?? null;

        $this->contrasena = isset($datos['contrasena']) 
            ? password_hash($datos['contrasena'], PASSWORD_DEFAULT) 
            : null;

        $this->fecha_nacimiento = $this->validarFecha($datos['fecha_nacimiento'] ?? null);
    }


    private function validarFecha($fecha) {
        if (!$fecha) return null;
        $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha);
        return ($fecha_obj) ? $fecha_obj->format('Y-m-d') : $fecha;
    }



    public function obtenerDatosDeApi($urlApi) {
        $url = $urlApi . "?correo=" . urlencode($this->correo);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    public function enviarALaApi($url) {
        $datosParaEnviar = [
            "contrasena"       => $this->contrasena,
            "codigo"           => $this->codigo,
            "nombre"           => $this->nombre,
            "correo"           => $this->correo,
            "direccion"        => $this->direccion,
            "telefono"         => $this->telefono,
            "registro"         => $this->registro,
            "fecha_nacimiento" => $this->fecha_nacimiento
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosParaEnviar));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }


    public function autenticarDirecto($correo, $passwordPlana) {
        if (!$this->conn) return false;

        $sql = "SELECT codigo, nombre, contrasena, rol FROM clientes WHERE correo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($usuario = $resultado->fetch_assoc()) {
            if (password_verify($passwordPlana, $usuario['contrasena'])) {
                unset($usuario['contrasena']);
                return $usuario;
            }
        }
        return false;
    }
}
?>