<?php
class Notificacion {
    private $conexion;
    private $tabla = "notificaciones";

    public function __construct() {
        require_once __DIR__ . '/../API/notificacionesAPI.php';
        
        // Ahora ya tenemos acceso a $conexion porque la API la creó
        $this->conexion = $conexion;
    }

    public function leerPorUsuario($usuario_id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE usuario_id = :uid ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([':uid' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * UPDATE: Cambia el estado de 'leido' de 0 a 1.
     */
    public function marcarComoLeida($id) {
        $query = "UPDATE " . $this->tabla . " SET leido = 1 WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        
        return $stmt->execute([':id' => $id]);
    }

    /**
     * DELETE: Elimina físicamente el registro.
     */
    public function borrar($id) {
        $query = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        
        return $stmt->execute([':id' => $id]);
    }

    /**
     * EXTRA: Cuenta solo las no leídas (útil para el globo rojo de la campana).
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