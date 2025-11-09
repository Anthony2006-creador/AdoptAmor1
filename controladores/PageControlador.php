<?php
class PageControlador {
    
    // Acción por defecto al cargar la web
    public function inicio() {
        // Redirigimos a la vista de mascotas como página principal
        require_once 'controladores/MascotaControlador.php';
        $mascotaControlador = new MascotaControlador();
        $mascotaControlador->index();
    }
    
    // Vista "Publicar"
    public function publicar() {
        require 'vistas/publicar/index.php';
    }
    
    // Vista "Contáctanos"
    public function contacto() {
        require 'vistas/contacto/index.php';
    }
}
?>