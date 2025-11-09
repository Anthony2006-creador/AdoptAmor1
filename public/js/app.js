document.addEventListener('DOMContentLoaded', () => {

    const mainContent = document.getElementById('main-content');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const modalContainer = document.getElementById('modal-container');
    const modalContent = document.getElementById('modal-content');
    const cartSidebar = document.getElementById('sidebar-cart');

    // --- 1. Navegación AJAX ---
    function initAjaxLinks(container) {
        // Buscamos enlaces AJAX dentro del contenedor (o en todo el body)
        const ajaxLinks = container.querySelectorAll('.ajax-link');
        
        ajaxLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const url = link.href;
                
                // Actualizar clase activa para la navegación
                if (link.classList.contains('nav-link')) {
                    document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
                    link.classList.add('active');
                }
                
                loadContent(url);
                // Actualizar la URL en el navegador
                window.history.pushState(null, '', url);
            });
        });
    }

    async function loadContent(url) {
        try {
            // Añadimos 'ajax=true' para que el servidor sepa que es un fetch
            const ajaxUrl = url.includes('?') ? `${url}&ajax=true` : `${url}?ajax=true`;
            
            const response = await fetch(ajaxUrl);
            if (!response.ok) {
                throw new Error('Error al cargar el contenido.');
            }
            const html = await response.text();
            mainContent.innerHTML = html;
            
            // Re-inicializar los listeners para el nuevo contenido cargado
            initAjaxLinks(mainContent); 
            // También re-inicializar otros listeners (ej. botones de 'comprar', 'adoptar')
            initActionButtons(mainContent);

        } catch (error) {
            console.error('Error AJAX:', error);
            showToast('Error al cargar la página.', 'error');
        }
    }

    // Manejar botones "Atrás/Adelante" del navegador
    window.addEventListener('popstate', () => {
        loadContent(window.location.href);
    });

    // --- 2. Modo Oscuro ---
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const themeIcon = themeToggleBtn.querySelector('i');

    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = document.body.getAttribute('data-theme');
        if (currentTheme === 'light') {
            document.body.setAttribute('data-theme', 'dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            document.body.setAttribute('data-theme', 'light');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
    });

    // --- 3. Lógica de Modales (Login, Registro, etc.) ---
    
    // Función global para mostrar un modal
    window.showModal = async (url) => {
        try {
            // ... (Tu código existente para cargar el HTML del modal) ...
            const ajaxUrl = url.includes('?') ? `${url}&ajax=true` : `${url}?ajax=true`;
            const response = await fetch(ajaxUrl);
            const html = await response.text();
            
            // ... (Tu código existente para reemplazar el contenido y evitar listeners duplicados) ...
            const newModalContent = modalContent.cloneNode(true);
            newModalContent.innerHTML = html; // Inyectar HTML nuevo
            modalContent.parentNode.replaceChild(newModalContent, modalContent);
            modalContent = newModalContent; // Actualizar referencia

            modalBackdrop.classList.add('visible');
            modalContainer.classList.add('visible');

            // --- LISTENERS DEL MODAL ---
            // Botón de cerrar
            const closeModalBtn = modalContent.querySelector('.modal-close-btn');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }

            // Manejar envío de formularios (AHORA ES GENÉRICO)
            const form = modalContent.querySelector('form');
            if(form) {
                // Renombramos la función para que sea más genérica
                form.addEventListener('submit', handleModalFormSubmit); 
            }

            // ... (Tu código existente para los enlaces #show-login-modal y #show-register-modal) ...
            const showLoginLink = modalContent.querySelector('#show-login-modal');
            if(showLoginLink) { /* ... */ }
            const showRegisterLink = modalContent.querySelector('#show-register-modal');
            if(showRegisterLink) { /* ... */ }

        } catch (error) {
            console.error('Error al cargar modal:', error);
            showToast('Error al abrir el formulario.', 'error');
        }
    };

    // ===== FUNCIÓN RENOMBRADA Y ACTUALIZADA =====
    // (Antes se llamaba handleAuthFormSubmit)
    async function handleModalFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        let url = '';
        let redirectUrlOnSuccess = null; // URL a donde redirigir (con AJAX)

        // Determinar la URL de la API según el ID del formulario
        switch (form.id) {
            case 'login-form':
                url = BASE_URL + 'index.php?controlador=Auth&accion=login';
                break;
            case 'register-form':
                url = BASE_URL + 'index.php?controlador=Auth&accion=registrar';
                break;
            case 'form-publicar-mascota':
                url = BASE_URL + 'index.php?controlador=Mascota&accion=crear';
                redirectUrlOnSuccess = BASE_URL + 'index.php?controlador=Mascota&accion=index';
                break;
            case 'form-publicar-producto':
                url = BASE_URL + 'index.php?controlador=Tienda&accion=crear';
                redirectUrlOnSuccess = BASE_URL + 'index.php?controlador=Tienda&accion=index';
                break;
            default:
                return; // Formulario desconocido
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const result = await response.json(); 

            if (result.success) {
                showToast(result.message, 'success');
                
                // --- Lógica de éxito ---
                if (form.id === 'login-form') {
                    closeModal();
                    window.location.reload(); // Recargar para actualizar sesión en header
                } 
                else if (form.id === 'register-form') {
                    // Mostrar modal de login
                    showModal(BASE_URL + 'index.php?controlador=Auth&accion=showLogin');
                }
                else if (redirectUrlOnSuccess) {
                    // Si fue una publicación exitosa
                    closeModal();
                    loadContent(redirectUrlOnSuccess); // Recargar el main via AJAX
                }
                
            } else {
                showToast(result.message, 'error');
            }

        } catch (error) {
            console.error('Error en el formulario:', error);
            showToast('Ocurrió un error inesperado.', 'error');
        }
    }

    // Función para cerrar el modal
    function closeModal() {
        modalBackdrop.classList.remove('visible');
        modalContainer.classList.remove('visible');
        if (modalContent) {
            modalContent.innerHTML = ''; // Limpiar contenido
        }
    }

    // Cerrar modal al hacer clic en el fondo
    modalBackdrop.addEventListener('click', closeModal);

    // Listener para el botón de Login en el Header
    const loginModalBtn = document.getElementById('login-modal-btn');
    if (loginModalBtn) {
        loginModalBtn.addEventListener('click', () => {
            showModal(BASE_URL + 'index.php?controlador=Auth&accion=showLogin');
        });
    }
    // --- 4. Lógica del Carrito (Panel Lateral) ---
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const cartCloseBtn = document.getElementById('cart-close-btn');
    const cartCounter = document.getElementById('cart-counter');

    cartToggleBtn.addEventListener('click', () => {
        cartSidebar.classList.add('visible');
        modalBackdrop.classList.add('visible');
        // (Futuro: aquí deberíamos cargar el contenido del carrito)
        // loadCartContent(); 
    });

    function closeCart() {
        cartSidebar.classList.remove('visible');
        modalBackdrop.classList.remove('visible');
    }
    
    cartCloseBtn.addEventListener('click', closeCart);
    // (Actualización: no queremos que el backdrop cierre el carrito, solo el modal)
    // modalBackdrop.addEventListener('click', closeCart); <-- Comenta o elimina esta línea

    // ===== FUNCIÓN ACTUALIZADA =====
    // Función global para añadir al carrito
    window.agregarAlCarrito = async (productoId) => {
        
        // 1. Crear FormData para enviar el ID
        const formData = new FormData();
        formData.append('id', productoId);

        try {
            // 2. Hacer la llamada AJAX al controlador
            const response = await fetch(BASE_URL + 'index.php?controlador=Tienda&accion=agregarAlCarrito', {
                method: 'POST',
                body: formData
            });

            const result = await response.json(); // Esperamos respuesta JSON

            if (result.success) {
                // 3. Éxito: Mostrar toast y actualizar contador
                showToast(result.message, 'success');
                cartCounter.textContent = result.nuevo_total_items;
                
                // (Opcional: hacer que el contador 'salte' brevemente)
                cartCounter.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    cartCounter.style.transform = 'scale(1)';
                }, 300);

            } else {
                // 4. Error (ej. no logueado, sin stock)
                showToast(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al agregar al carrito:', error);
            showToast('Error de conexión al añadir producto.', 'error');
        }
    };

    // --- 5. Notificaciones (Toasts) ---
    window.showToast = (message, type = 'info') => {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = 'fa-solid fa-circle-info';
        if (type === 'success') icon = 'fa-solid fa-check-circle';
        if (type === 'error') icon = 'fa-solid fa-times-circle';
        
        toast.innerHTML = `<i class="${icon}"></i> ${message}`;
        toastContainer.appendChild(toast);
        
        // Auto-destruir el toast
        setTimeout(() => {
            toast.style.animation = 'toast-out 0.5s forwards';
            toast.addEventListener('animationend', () => toast.remove());
        }, 3000);
    };


    // --- 6. Inicialización de Listeners ---
    function initActionButtons(container) {
        
        // Botones de "Comprar" (Código existente)
        const comprarBtns = container.querySelectorAll('.btn-comprar-producto');
        comprarBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productoId = e.currentTarget.dataset.id;
                const isLoggedIn = document.body.querySelector('.profile-icon.logged-in');
                if (!isLoggedIn) {
                    showToast('Debes iniciar sesión para comprar', 'error');
                    showModal(BASE_URL + 'index.php?controlador=Auth&accion=showLogin');
                } else {
                    agregarAlCarrito(productoId);
                }
            });
        });

        // --- NUEVOS LISTENERS ---
        // Botón "Publicar Mascota"
        const pubMascotaBtn = container.querySelector('#btn-publicar-mascota');
        if (pubMascotaBtn) {
            pubMascotaBtn.addEventListener('click', () => {
                const isLoggedIn = document.body.querySelector('.profile-icon.logged-in');
                if (!isLoggedIn) {
                    showToast('Debes iniciar sesión para publicar', 'error');
                    showModal(BASE_URL + 'index.php?controlador=Auth&accion=showLogin');
                } else {
                    showModal(BASE_URL + 'index.php?controlador=Mascota&accion=showPublicarForm');
                }
            });
        }

        // Botón "Publicar Producto"
        const pubProductoBtn = container.querySelector('#btn-publicar-producto');
        if (pubProductoBtn) {
            pubProductoBtn.addEventListener('click', () => {
                const isLoggedIn = document.body.querySelector('.profile-icon.logged-in');
                if (!isLoggedIn) {
                    showToast('Debes iniciar sesión para publicar', 'error');
                    showModal(BASE_URL + 'index.php?controlador=Auth&accion=showLogin');
                } else {
                    showModal(BASE_URL + 'index.php?controlador=Tienda&accion=showPublicarForm');
                }
            });
        }
        
        // (Aquí irán los listeners para 'Adoptar')
    }

    // Inicializar todo al cargar la página
    initAjaxLinks(document.body);
    initActionButtons(document.body);

    // Cierre del modal con backdrop (ACTUALIZADO)
    // Lo movemos aquí para que solo cierre modales, no el carrito
    modalBackdrop.addEventListener('click', () => {
        if (modalContainer.classList.contains('visible')) {
            closeModal();
        }
        if (cartSidebar.classList.contains('visible')) {
            closeCart();
        }
    });
});