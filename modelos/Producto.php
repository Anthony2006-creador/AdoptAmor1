<?php
require_once 'Database.php';

class Producto {
    private $conn;
    private $table_name = 'producto';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Obtiene todos los productos que están activos y tienen stock.
     * @return array Lista de productos.
     */
    public function getProductosActivos() {
        // Solo mostramos productos activos (esta_activo = 1) y con stock > 0
        $query = 'SELECT 
                    ID_producto, 
                    nombre, 
                    descripcion, 
                    precio, 
                    stock, 
                    imagen 
                  FROM ' . $this->table_name . ' 
                  WHERE 
                    esta_activo = 1 AND stock > 0 
                  ORDER BY nombre ASC';
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un producto por su ID (para el carrito).
     * @param int $id
     * @return mixed Array con datos del producto o false.
     */
    public function getProductoById($id) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE ID_producto = :id AND esta_activo = 1';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo producto en la base de datos.
     * @param string $nombre
     * @param string $descripcion
     * @param float $precio
     * @param int $stock
     * @param string $imagen (URL)
     * @param int $id_usuario
     * @return bool True si se creó, false si no.
     */
    public function crear($nombre, $descripcion, $precio, $stock, $imagen, $id_usuario) {
        // esta_activo se define como TRUE por defecto según tu SQL
        $query = 'INSERT INTO ' . $this->table_name . ' 
                    (nombre, descripcion, precio, stock, ID_usuario, imagen, esta_activo)
                  VALUES
                    (:nombre, :descripcion, :precio, :stock, :id_usuario, :imagen, TRUE)';
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $descripcion = htmlspecialchars(strip_tags($descripcion));
        $precio = (float)$precio;
        $stock = (int)$stock;
        $imagen = htmlspecialchars(strip_tags($imagen));
        $id_usuario = (int)$id_usuario;

        // Bind parameters
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':imagen', $imagen);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // Puedes loggear el error $e->getMessage()
            return false;
        }
    }
}
?>