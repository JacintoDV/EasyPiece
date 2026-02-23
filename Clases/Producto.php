<?php
class Producto {
    // Atributos privados basados en tu tabla de MySQL
    private $codigo;
    private $nombre;
    private $descripcion;
    private $precio;
    private $cantidad;
    private $marca;
    private $presentacion;
    private $dosis;
    private $imagen;
    private $estado;

    /**
     * Constructor de la clase
     * Recibe un array asociativo con los datos (vengan de la DB o de un JSON)
     */
    public function __construct($datos = []) {
        $this->codigo       = $datos['codigo'] ?? null;
        $this->nombre       = $datos['nombre'] ?? null;
        $this->descripcion  = $datos['descripcion'] ?? null;
        $this->precio       = $datos['precio'] ?? 0.00;
        $this->cantidad     = $datos['cantidad'] ?? 0;
        $this->marca        = $datos['marca'] ?? null;
        $this->presentacion = $datos['presentacion'] ?? null;
        $this->dosis        = $datos['dosis'] ?? null;
        $this->imagen       = $datos['imagen'] ?? 'default.jpg'; // Imagen por defecto
        $this->estado       = $datos['estado'] ?? 1; // 1 para activo por defecto
    }

    // --- MÉTODOS GETTERS (Para que la API o UI puedan leer los datos) ---

    public function getCodigo() { return $this->codigo; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getCantidad() { return $this->cantidad; }
    public function getMarca() { return $this->marca; }
    public function getPresentacion() { return $this->presentacion; }
    public function getDosis() { return $this->dosis; }
    public function getImagen() { return $this->imagen; }
    public function getEstado() { return $this->estado; }

    /**
     * Formatea el precio para mostrarlo con dos decimales y símbolo de moneda
     */
    public function getPrecioFormateado() {
        return "$" . number_format($this->precio, 2);
    }

    /**
     * Convierte el objeto a un array (Útil para enviar a la API)
     */
    public function toArray() {
        return [
            "codigo"       => $this->codigo,
            "nombre"       => $this->nombre,
            "descripcion"  => $this->descripcion,
            "precio"       => $this->precio,
            "cantidad"     => $this->cantidad,
            "marca"        => $this->marca,
            "presentacion" => $this->presentacion,
            "dosis"        => $this->dosis,
            "imagen"       => $this->imagen,
            "estado"       => $this->estado
        ];
    }

    public function tieneStockDisponible($cantidadRequerida = 1) {
        return $this->cantidad >= $cantidadRequerida;
    }

    public function obtenerEstadoStock() {
        if ($this->cantidad <= 0) {
            return "Agotado";
        } elseif ($this->cantidad <= 5) {
            return "Últimas unidades";
        } else {
            return "Disponible";
        }
    }

    public function consultarStockActual() {
        return $this->cantidad;
    }
    
    public function enviarALaApi($urlApi) {
        $payload = json_encode($this->toArray());

        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public static function obtenerDeApi($urlApi) {
        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

        $result = curl_exec($ch);
        curl_close($ch);

        $datos = json_decode($result, true);
        $productos = [];

        if (is_array($datos)) {
            foreach ($datos as $item) {
                // Convertimos cada array de la API en un objeto Producto
                $productos[] = new Producto($item);
            }
        }

        return $productos;
    }

    public function actualizarStock($urlApi, $cantidadADescontar) {
        // Preparamos los datos según lo que espera tu case 'PUT'
        $datos = [
            "codigo" => $this->codigo,
            "cantidad" => $cantidadADescontar
        ];
        
        $payload = json_encode($datos);

        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Configuramos el método personalizado PUT
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

        $result = curl_exec($ch);
        
        // Manejo de errores de conexión
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ["success" => false, "error" => "Error de conexión: $error_msg"];
        }

        curl_close($ch);
        return json_decode($result, true);
    }

}
?>