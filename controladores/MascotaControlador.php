<?php

require_once 'modelos/Mascota.php';

class MascotaControlador {
    
    public function index() {
        
        $mascotaModel = new Mascota(); // Ya deberías tener esto
        $mascotas = $mascotaModel->getAll(); // Asumiendo que tienes un método 'getAll'
        
        // Simulación si no tienes 'getAll'
        if (!method_exists($mascotaModel, 'getAll')) {
             $mascotas = [
                ['nombre' => 'Firulais', 'tipo' => 'Perro', 'raza' => 'Mestizo', 'edad' => 2],
                ['nombre' => 'Mishi', 'tipo' => 'Gato', 'raza' => 'Siamés', 'edad' => 1]
            ];
        }

        require 'vistas/mascotas/index.php';
    }
    
    public function showPublicarForm() {
        // Solo puede publicar si está logueado (doble chequeo)
        if (!isset($_SESSION['usuario'])) {
            echo '<p>Debes iniciar sesión para publicar.</p>';
            return;
        }
        require 'vistas/mascotas/_form_mascota.php';
    }

    // --- NUEVA ACCIÓN ---
    /**
     * Procesa la solicitud AJAX para crear una mascota.
     */
    public function crear() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Acción no permitida.']);
            return;
        }

        // Validar datos (puedes añadir más validaciones)
        if (empty($_POST['nombre']) || empty($_POST['edad']) || empty($_POST['tipo']) || empty($_POST['raza']) || empty($_POST['imagen'])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            return;
        }

        $mascotaModel = new Mascota();
        $id_usuario = $_SESSION['usuario']['id'];

        if ($mascotaModel->crear($_POST['nombre'], $_POST['edad'], $_POST['tipo'], $_POST['raza'], $_POST['imagen'], $id_usuario)) {
            echo json_encode(['success' => true, 'message' => '¡Mascota publicada con éxito!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al publicar la mascota.']);
        }
    }
}
?>