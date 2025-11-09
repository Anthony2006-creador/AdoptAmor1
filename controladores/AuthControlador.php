<?php
// Incluimos los modelos necesarios
require_once 'modelos/Usuario.php';

class AuthControlador {

    // Muestra el formulario modal de login
    public function showLogin() {
        require 'vistas/auth/login_modal.php';
    }

    // Muestra el formulario modal de registro
    public function showRegistro() {
        require 'vistas/auth/registro_modal.php';
    }

    /**
     * Procesa la solicitud de registro (vía AJAX/Fetch).
     */
    public function registrar() {
        header('Content-Type: application/json');
        
        // 1. Validar que los datos POST existan
        if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['pass'])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            return;
        }

        $usuarioModel = new Usuario();
        $email = $_POST['email'];

        // 2. Verificar si el email ya existe
        if ($usuarioModel->findByEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado.']);
            return;
        }

        // 3. Hashear la contraseña (¡Importante!)
        // Usamos BCRYPT, tal como tu ejemplo de hash
        $password_hash = password_hash($_POST['pass'], PASSWORD_BCRYPT, ['cost' => 10]);

        // 4. Crear el usuario
        if ($usuarioModel->create($_POST['nombre'], $email, $password_hash)) {
            echo json_encode(['success' => true, 'message' => '¡Cuenta creada con éxito! Ahora puedes iniciar sesión.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la cuenta. Inténtalo de nuevo.']);
        }
    }

    /**
     * Procesa la solicitud de login (vía AJAX/Fetch).
     */
    public function login() {
        header('Content-Type: application/json');

        if (empty($_POST['email']) || empty($_POST['pass'])) {
            echo json_encode(['success' => false, 'message' => 'Email y contraseña son obligatorios.']);
            return;
        }

        $usuarioModel = new Usuario();
        $email = $_POST['email'];
        $pass = $_POST['pass'];

        // 1. Buscar al usuario por email
        $usuario = $usuarioModel->findByEmail($email);

        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
            return;
        }

        // 2. Verificar la contraseña
        // Compara la contraseña enviada ($_POST['pass']) con el hash de la BD ($usuario['pass'])
        if (password_verify($pass, $usuario['pass'])) {
            // ¡Éxito! Iniciar la sesión
            
            // session_start() ya se llama en /index.php, no es necesario llamarlo de nuevo
            
            $_SESSION['usuario'] = [
                'id' => $usuario['ID_usuario'],
                'nombre' => $usuario['U_nombre'],
                'email' => $usuario['email'],
                'rol' => $usuario['rol']
            ];
            
            echo json_encode(['success' => true, 'message' => '¡Bienvenido, ' . $usuario['U_nombre'] . '!']);
        } else {
            // Contraseña incorrecta
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        // session_start() ya está en index.php
        session_unset();    // Libera todas las variables de sesión
        session_destroy();  // Destruye la sesión
        
        // Redirigir al inicio
        header('Location: ' . BASE_URL);
        exit;
    }
}
?>