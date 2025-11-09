<?php
require_once 'modelos/Producto.php';

class TiendaControlador {

    /**
     * Muestra la página principal de la tienda con los productos.
     */
    public function index() {
        $productoModel = new Producto();
        // Obtenemos los productos de la BD
        $productos = $productoModel->getProductosActivos();
        
        // Cargamos la vista (parcial) y le pasamos los datos
        require 'vistas/tienda/index.php';
    }

    /**
     * Acción AJAX para agregar un producto al carrito (en la sesión).
     */
    public function agregarAlCarrito() {
        header('Content-Type: application/json');

        // 1. Verificar que el usuario esté logueado (ya lo hace el JS, pero doble chequeo)
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
            return;
        }

        // 2. Verificar que se envió un ID de producto
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'Producto no válido.']);
            return;
        }

        $producto_id = (int)$_POST['id'];

        // 3. Inicializar el carrito en la sesión si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // 4. (Opcional pero recomendado) Verificar que el producto existe y tiene stock
        $productoModel = new Producto();
        $producto = $productoModel->getProductoById($producto_id);
        
        if (!$producto || $producto['stock'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Producto no disponible.']);
            return;
        }

        // 5. Agregar al carrito o incrementar cantidad
        if (isset($_SESSION['carrito'][$producto_id])) {
            // Si ya está en el carrito, incrementamos la cantidad
            // (Asegurándonos de no pasar el stock)
            if ($_SESSION['carrito'][$producto_id]['cantidad'] < $producto['stock']) {
                $_SESSION['carrito'][$producto_id]['cantidad']++;
            }
        } else {
            // Si es nuevo, lo añadimos
            $_SESSION['carrito'][$producto_id] = [
                'id' => $producto_id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'cantidad' => 1
            ];
        }

        // 6. Enviar respuesta de éxito con el nuevo total de items
        $total_items = count($_SESSION['carrito']); // O podrías sumar todas las 'cantidades'
        
        echo json_encode([
            'success' => true, 
            'message' => 'Producto añadido al carrito.',
            'nuevo_total_items' => $total_items
        ]);
    }

    public function showPublicarForm() {
        if (!isset($_SESSION['usuario'])) {
            echo '<p>Debes iniciar sesión para publicar.</p>';
            return;
        }
        require 'vistas/tienda/_form_producto.php';
    }

    // --- NUEVA ACCIÓN ---
    /**
     * Procesa la solicitud AJAX para crear un producto.
     */
    public function crear() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Acción no permitida.']);
            return;
        }

        // Validar datos
        if (empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['precio']) || !isset($_POST['stock']) || empty($_POST['imagen'])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            return;
        }

        $productoModel = new Producto();
        $id_usuario = $_SESSION['usuario']['id'];

        if ($productoModel->crear($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['imagen'], $id_usuario)) {
            echo json_encode(['success' => true, 'message' => '¡Producto publicado con éxito!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al publicar el producto.']);
        }
    }
}
?>