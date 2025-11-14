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

    public static function validar_contrasenas($pass1, $pass2) {
    return $pass1 === $pass2;
    }
}
?>
