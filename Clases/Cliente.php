<?php
class Cliente {
    private $contrasena;
    private $codigo;
    private $nombre;
    private $correo;
    private $direccion;
    private $telefono;
    private $registro;
    private $fecha_nacimiento;
    private $conn; 

    public function __construct($conexion = null, $datos = []) {
        $this->conn = $conexion;
        $this->codigo = $datos['codigo'] ?? null;
        $this->correo = $datos['correo'] ?? null;
        $this->nombre = $datos['nombre'] ?? null;
        $this->direccion = $datos['direccion'] ?? null;
        $this->telefono = $datos['telefono'] ?? null;
        $this->registro = $datos['registro'] ?? null;
        
        // CORRECCIÓN: Quitamos el procesamiento de hash, solo asignamos el valor
        if (isset($datos['contrasena']) && $datos['contrasena'] !== "") {
            $this->setContrasena($datos['contrasena']);
        }

        $this->fecha_nacimiento = $this->validarFecha($datos['fecha_nacimiento'] ?? null);
    }

    private function validarFecha($fecha) {
        if (!$fecha) return null;
        $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha);
        return ($fecha_obj) ? $fecha_obj->format('Y-m-d') : $fecha;
    }

    // SETTERS
    public function setNombre($n) { $this->nombre = $n; }
    public function setCorreo($c) { $this->correo = $c; }
    public function setTelefono($t) { $this->telefono = $t; }
    public function setFechaNacimiento($f) { $this->fecha_nacimiento = $this->validarFecha($f); }
    public function setCodigo($id) { $this->codigo = $id; }
    public function setDireccion($d) { $this->direccion = $d; }
    public function setRegistro($r) { $this->registro = $r; }
    
    // CAMBIO SOLICITADO: Solo asigna la contraseña plana para que la API la hashee
    public function setContrasena($p) { 
        if(!empty($p)) {
            $this->contrasena = $p; 
        }
    }

    public function verificarCorreoCoincide($correo, $idUsuario) {
        $url = BASE_URL . "/API/clientesAPI.php?correo=" . urlencode($correo);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $resData = json_decode($result, true);

        if (isset($resData['existe']) && $resData['existe'] === true) {
            if (isset($resData['datos']['codigo']) && $resData['datos']['codigo'] != $idUsuario) {
                return false; 
            }
        }
        return true; 
    }

    public function enviarALaApi($url, $metodo = "POST") {
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
        
        if ($metodo === "PUT") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        } else {
            curl_setopt($ch, CURLOPT_POST, true);
        }

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
            // Sigue usando verify porque comparamos la plana enviada contra el hash de la DB
            if (password_verify($passwordPlana, $usuario['contrasena'])) {
                unset($usuario['contrasena']);
                return $usuario;
            }
        }
        return false;
    }
    
    public function obtenerPerfil($codigoSession) {  
        
        $url = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php?codigo=" . urlencode($codigoSession);

        //Iniciamos cURL para hacer la petición GET
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Manejo de errores de conexión
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ["success" => false, "error" => "Error de conexión: " . $error_msg];
        }

        curl_close($ch);

        // 3. Procesamos la respuesta
        if ($httpCode === 200) {
            $datos = json_decode($response, true);
            return $datos;
        } else {
            return ["success" => false, "error" => "La API respondió con código: " . $httpCode];
        }
    }
    public function actualizarPerfil($datos) {
        $url = "http://localhost/EasyPiece_Nuevo/API/clientesAPI.php";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Especificamos que es PUT
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}