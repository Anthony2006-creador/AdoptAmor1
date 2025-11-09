<?php
    // Variables globales definidas en index.php
    global $controladorObjGlobal, $accionGlobal;
    
    // Verificar si hay un usuario en sesión
    $usuario_logueado = isset($_SESSION['usuario']);
    $nombre_usuario = $usuario_logueado ? $_SESSION['usuario']['nombre'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdoptAmor</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/estilos.css">
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body data-theme="light"> <header class="header">
        <nav class="nav-container">
            <a href="<?php echo BASE_URL; ?>" class="nav-logo ajax-link">
                <i class="fa-solid fa-paw"></i> AdoptAmor
            </a>

            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>index.php?controlador=Mascota&accion=index" class="nav-link ajax-link">Mascotas</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?controlador=Tienda&accion=index" class="nav-link ajax-link">Tienda</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?controlador=Page&accion=publicar" class="nav-link ajax-link">Publicar</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?controlador=Page&accion=contacto" class="nav-link ajax-link">Contáctanos</a></li>
            </ul>

            <div class="nav-actions">
                <button id="theme-toggle-btn" class="nav-icon-btn" aria-label="Cambiar modo">
                    <i class="fa-solid fa-moon"></i>
                </button>
                
                <button id="cart-toggle-btn" class="nav-icon-btn" aria-label="Ver carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cart-counter" class="notification-badge">0</span>
                </button>
                
                <div class="profile-section">
                    <?php if ($usuario_logueado): ?>
                        <span class="profile-name"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                        <div class="dropdown">
                            <button id="profile-btn" class="nav-icon-btn profile-icon logged-in" aria-label="Perfil de usuario">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            <div class="dropdown-content">
                                <a href="<?php echo BASE_URL; ?>index.php?controlador=Auth&accion=logout">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button id="login-modal-btn" class="nav-icon-btn profile-icon logged-out" aria-label="Iniciar sesión">
                            <i class="fa-solid fa-user"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content" class="main-container">
        <?php
            // Carga el contenido inicial (no AJAX)
            if (isset($controladorObjGlobal) && isset($accionGlobal)) {
                $controladorObjGlobal->$accionGlobal();
            }
        ?>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2025 AdoptAmor. Todos los derechos reservados.</p>
            <div class="footer-links">
                <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" aria-label="Correo"><i class="fa-solid fa-envelope"></i></a>
                </div>
        </div>
    </footer>

    <div id="toast-container"></div>

    <?php include_once '_sidebar_cart.php'; ?>

    <div id="modal-backdrop" class="modal-backdrop"></div>

    <div id="modal-container" class="modal-container">
        <div id="modal-content" class="modal-content">
            </div>
    </div>


    <script>
        // Pasamos variables de PHP a JavaScript
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>public/js/app.js"></script>
</body>
</html>