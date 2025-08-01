<?php
class Cliente {
    // Atributos (propiedades)
    private $contrasena;
    private $codigo;
    private $nombre;
    private $correo;
    private $direccion;
    private $telefono;
    private $registro;
    private $fecha_nacimiento;

    // Constructor
    public function __construct($contrasena, $codigo, $nombre, $correo, $direccion, $telefono, $registro, $fecha_nacimiento) {
    $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $this->codigo = $codigo;
    $this->nombre = $nombre;
    $this->correo = $correo;
    $this->direccion = $direccion;
    $this->telefono = $telefono;
    $this->registro = $registro;

    //Convertimos de "d/m/Y" a "Y-m-d" si la fecha existe
    if (!empty($fecha_nacimiento)) {
        $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha_nacimiento);
        $this->fecha_nacimiento = $fecha_obj ? $fecha_obj->format('Y-m-d') : null;
    } else {
        $this->fecha_nacimiento = null;
    }
}


    // Método para obtener datos del formulario
    public static function obtener_datos() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $codigo = $_POST["id"] ?? null;
        $nombre = $_POST["nombres"] . " " . $_POST["apellidos"];
        $correo = $_POST["correo"] ?? null;
        $direccion = null;
        $telefono = $_POST["telefono"] ?? null;
        $registro = $_POST["registro"] ?? null;
        $fecha_nacimiento = $_POST["fecha"] ?? null;
        $contrasena = $_POST["contrasena"] ?? null;
        $contrasenados = $_POST["contrasenados"] ?? null;

        if (!self::validar_contrasenas($contrasena, $contrasenados)) {
            return null;
        }

        return new Cliente($contrasena, $codigo, $nombre, $correo, $direccion, $telefono, $registro, $fecha_nacimiento);
    }

    return null;
}

    // Método para insertar datos en la base de datos
    public function insertar_en_bd($conexion) {
        $sql = "INSERT INTO clientes (codigo, nombre, correo, direccion, telefono, registro, contrasena, fecha_nacimiento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssss", 
            $this->codigo, 
            $this->nombre, 
            $this->correo, 
            $this->direccion, 
            $this->telefono, 
            $this->registro, 
            $this->contrasena,
            $this->fecha_nacimiento
        );
        return $stmt->execute();
    }

    public static function validar_contrasenas($pass1, $pass2) {
    return $pass1 === $pass2;
    }
}
?>
