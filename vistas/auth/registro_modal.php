<link rel="stylesheet" type="text/css" href="public/css/estilosRegistro.css">
<button class="modal-close-btn"><i class="fa-solid fa-times"></i></button>
<h3>Crear Cuenta</h3>

<form id="register-form" novalidate>
    <div class="form-group">
        <label for="register-nombre">Nombre</label>
        <input type="text" id="register-nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="register-email">Email</label>
        <input type="email" id="register-email" name="email" required>
    </div>
    <div class="form-group">
        <label for="register-pass">Contraseña</label>
        <input type="password" id="register-pass" name="pass" required>
    </div>
    <button type="submit" class="btn-primary">Registrarse</button>
</form>

<p class="modal-links">
    ¿Ya tienes una cuenta? <a href="#" id="show-login-modal">Inicia Sesión</a>
</p>

