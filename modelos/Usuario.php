<?php
require_once 'Database.php';

class Usuario {
    private $conn;
    private $table_name = 'usuario';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Busca un usuario por su email.
     * @param string $email
     * @return mixed Array con datos del usuario si se encuentra, false si no.
     */
    public function findByEmail($email) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE email = :email LIMIT 1';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param string $nombre
     * @param string $email
     * @param string $password_hash (Contraseña ya hasheada)
     * @return bool True si se creó, false si hubo un error.
     */
    public function create($nombre, $email, $password_hash) {
        // Como definiste, todos son 'user' y la fecha es la actual.
        $query = 'INSERT INTO ' . $this->table_name . ' 
                    (U_nombre, email, pass, rol, F_registro)
                  VALUES
                    (:nombre, :email, :pass, "user", CURDATE())';
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind parameters
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $password_hash);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // Manejar error de email duplicado
            if ($e->errorInfo[1] == 1062) { // 1062 es el código de 'Duplicate entry'
                return false;
            }
            throw $e; // Lanzar otras excepciones
        }
    }
}
?>