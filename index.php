<?php
// Iniciar la sesión para manejar el login y el carrito
session_start();

// Definir la URL base del proyecto
define('BASE_URL', '/'); // Ajusta esto si tu proyecto está en otra ruta

// Lógica de Enrutamiento Simple
// 1. Obtener controlador y acción de la URL
$controlador = isset($_GET['controlador']) ? $_GET['controlador'] : 'Page';
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio';

// 2. Formatear el nombre del archivo y la clase
$nombreControlador = ucwords($controlador) . 'Controlador';
$archivoControlador = 'controladores/' . $nombreControlador . '.php';

// 3. Verificar si el archivo del controlador existe
if (file_exists($archivoControlador)) {
    // 4. Incluir el archivo
    require_once $archivoControlador;

    // 5. Crear la instancia del controlador
    if (class_exists($nombreControlador)) {
        $controladorObj = new $nombreControlador();

        // 6. Verificar si el método (acción) existe
        if (method_exists($controladorObj, $accion)) {
            
            // 7. Comprobar si es una solicitud AJAX
            // Si es AJAX, solo ejecutamos la acción (que imprimirá el HTML parcial)
            // Si NO es AJAX, cargamos el layout completo primero.
            
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            if ($isAjax || isset($_GET['ajax'])) {
                // Solicitud AJAX: Solo ejecutar la acción
                $controladorObj->$accion();
            } else {
                // Solicitud Normal: Cargar el layout completo
                // El layout se encargará de llamar a la acción correcta
                // para inyectar el contenido inicial.
                global $controladorObjGlobal, $accionGlobal;
                $controladorObjGlobal = $controladorObj;
                $accionGlobal = $accion;
                
                require_once 'vistas/_layout.php';
            }

        } else {
            echo "Error: La acción '$accion' no existe en el controlador '$nombreControlador'.";
        }
    } else {
        echo "Error: La clase '$nombreControlador' no existe.";
    }
} else {
    echo "Error: El controlador '$nombreControlador' no existe.";
}
?>