<?php
class Notificacion {
    private $conexion;
    private $tabla = "notificaciones";

    public function __construct() {
        // Traemos el archivo de la API que crea la conexión $conexion
        require_once __DIR__ . '/../API/notificacionesAPI.php';
        
        // Usamos la palabra clave 'global' para acceder a la variable definida en la API
        global $conexion;
        $this->conexion = $conexion;
    }

    /**
     * CREATE: Inserta una nueva notificación (usada por el Service de Factura)
     */
    public function crear($usuario_id, $mensaje, $tipo = 'info') {
        $query = "INSERT INTO " . $this->tabla . " (usuario_id, mensaje, tipo, leido, fecha_creacion) 
                  VALUES (:uid, :msg, :tipo, 0, NOW())";
        
        $stmt = $this->conexion->prepare($query);
        
        return $stmt->execute([
            ':uid'   => $usuario_id,
            ':msg'   => $mensaje,
            ':tipo'  => $tipo
        ]);
    }

    /**
     * READ: Trae todas las notificaciones de un usuario
     */
    public function leerPorUsuario($usuario_id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE usuario_id = :uid ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([':uid' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * UPDATE: Cambia el estado de 'leido' de 0 a 1
     */
    public function marcarComoLeida($id) {
        $query = "UPDATE " . $this->tabla . " SET leido = 1 WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        
        return $stmt->execute([':id' => $id]);
    }

    /**
     * DELETE: Elimina físicamente el registro
     */
    public function borrar($id) {
        $query = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        
        return $stmt->execute([':id' => $id]);
    }

    /**
     * EXTRA: Cuenta solo las no leídas (para el globo rojo)
     */
    public function contarNoLeidas($usuario_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->tabla . " 
                  WHERE usuario_id = :uid AND leido = 0";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([':uid' => $usuario_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] ?? 0;
    }
}
?>