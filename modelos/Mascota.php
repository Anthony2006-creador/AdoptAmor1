<?php
require_once 'Database.php';

class Mascota {
    private $conn;
    private $table_name = 'mascota';

    /**
     * Constructor: Obtiene la conexión a la base de datos.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Obtiene todas las mascotas que aún no han sido adoptadas.
     * (Usado por la vista principal de Mascotas)
     *
     * @return array Lista de mascotas.
     */
    public function getAll() {
        // Esta consulta selecciona todas las mascotas (m)
        // que NO tienen una entrada (LEFT JOIN ... IS NULL) en la tabla 'adopcion'.
        // Así nos aseguramos de no mostrar mascotas ya adoptadas.
        $query = 'SELECT m.* FROM ' . $this->table_name . ' AS m
                  LEFT JOIN adopcion AS a ON m.ID_mascota = a.ID_mascota
                  WHERE a.ID_adopcion IS NULL
                  ORDER BY m.ID_mascota DESC';
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene una mascota específica por su ID.
     * (Lo necesitaremos para el formulario de adopción).
     *
     * @param int $id
     * @return mixed Array con datos de la mascota o false.
     */
    public function getById($id) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE ID_mascota = :id';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea una nueva mascota en la base de datos.
     * (Usado por el formulario modal de Publicar Mascota)
     *
     * @param string $nombre
     * @param int $edad
     * @param string $tipo
     * @param string $raza
     * @param string $imagen (URL)
     * @param int $id_usuario
     * @return bool True si se creó, false si no.
     */
    public function crear($nombre, $edad, $tipo, $raza, $imagen, $id_usuario) {
        $query = 'INSERT INTO ' . $this->table_name . ' 
                    (nombre, edad, tipo, raza, ID_usuario, imagen)
                  VALUES
                    (:nombre, :edad, :tipo, :raza, :id_usuario, :imagen)';
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $edad = (int)$edad;
        $tipo = htmlspecialchars(strip_tags($tipo));
        $raza = htmlspecialchars(strip_tags($raza));
        $imagen = htmlspecialchars(strip_tags($imagen)); // Como es URL, solo limpiamos
        $id_usuario = (int)$id_usuario;

        // Bind parameters
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':raza', $raza);
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