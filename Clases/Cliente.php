<?php
class Cliente {
    // Atributos privados para proteger los datos
    private $contrasena;
    private $codigo;
    private $nombre;
    private $correo;
    private $direccion;
    private $telefono;
    private $registro;
    private $fecha_nacimiento;

    /**
     * Constructor de la clase
     * Recibe un array asociativo con los datos
     */
    public function __construct($datos = []) {

        $this->codigo = $datos['codigo'] ?? null;
        $this->correo = $datos['correo'] ?? null;
        $this->contrasena = isset($datos['contrasena']) 
            ? password_hash($datos['contrasena'], PASSWORD_DEFAULT) 
            : null;

        $this->nombre = $datos['nombre'] ?? null;
        $this->direccion = $datos['direccion'] ?? null;
        $this->telefono = $datos['telefono'] ?? null;
        $this->registro = $datos['registro'] ?? null;
        $this->fecha_nacimiento = $this->validarFecha($datos['fecha_nacimiento'] ?? null);
    }

    private function validarFecha($fecha) {
        if (!$fecha) return null;

        $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha);
        if ($fecha_obj) {
            return $fecha_obj->format('Y-m-d');
        }
        
        // Si ya viene en formato Y-m-d o es inválida, se retorna tal cual (o null)
        return $fecha;
    }

    public function generarCodigoPersonalizado($numeroBase) {

        $random = rand(1000, 9999);
        $codigoFinal = (int)($numeroBase . $random);
        $this->codigo = $codigoFinal;
        return $codigoFinal;
    }
 
    public function verificarCorreoCoincide($correoAComparar) {
        // Usamos trim() para evitar errores por espacios en blanco
        // y strtolower() para que no importe si uno es Mayúscula y otro minúscula
        return strtolower(trim($this->correo)) === strtolower(trim($correoAComparar));
    }




    public function enviarALaApi($url) {
        $datosParaEnviar = [
            "contrasena" => $this->contrasena,
            "codigo"     => $this->codigo,
            "nombre"     => $this->nombre,
            "correo"     => $this->correo,
            "direccion"  => $this->direccion,
            "telefono"   => $this->telefono,
            "registro"   => $this->registro,
            "fecha_nacimiento" => $this->fecha_nacimiento
        ];

        $ch = curl_init($url);
        $payload = json_encode($datosParaEnviar);

        // --- CONFIGURACIÓN DE CUALIDADES DEL ENVÍO ---
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // El "disfraz" de Mozilla para que Apache no sospeche
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        
        // Ignorar errores de SSL por si usas HTTPS local
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        // Capturar errores técnicos de conexión
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["error_curl" => $error];
        }

        curl_close($ch);
        
        // Intentar decodificar la respuesta
        $respuestaDecodificada = json_decode($result, true);
        
        // Si la API mandó un error de PHP (texto plano) y no un JSON, lo capturamos aquí
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ["error_api_crudo" => $result];
        }

        return $respuestaDecodificada;
    }

}
?>