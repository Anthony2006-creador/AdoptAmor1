<link rel="stylesheet" type="text/css" href="public/css/estilosLogin.css">

<button class="modal-close-btn"><i class="fa-solid fa-times"></i></button>
<h3>Iniciar Sesión</h3>

<form id="login-form" novalidate>
    <div class="form-group">
        <label for="login-email">Email</label>
        <input type="email" id="login-email" name="email" required>
    </div>
    <div class="form-group">
        <label for="login-pass">Contraseña</label>
        <input type="password" id="login-pass" name="pass" required>
    </div>
    <button type="submit" class="btn-primary">Entrar</button>
</form>

<p class="modal-links">
    <a href="#" id="show-register-modal">Crear cuenta</a> | 
    <a href="#">Recuperar cuenta</a>
</p>

