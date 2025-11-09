<link rel="stylesheet" type="text/css" href="public/css/estilosMascotaF.css">
<button class="modal-close-btn"><i class="fa-solid fa-times"></i></button>
<h3>Publicar Mascota</h3>
<p>Completa los datos de tu mascota.</p>

<form id="form-publicar-mascota" novalidate>
    <div class="form-group">
        <label for="mascota-nombre">Nombre</label>
        <input type="text" id="mascota-nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="mascota-edad">Edad (años)</label>
        <input type="number" id="mascota-edad" name="edad" min="0" required>
    </div>
    <div class="form-group">
        <label for="mascota-tipo">Tipo (ej. Perro, Gato)</label>
        <input type="text" id="mascota-tipo" name="tipo" required>
    </div>
    <div class="form-group">
        <label for="mascota-raza">Raza</label>
        <input type="text" id="mascota-raza" name="raza" required>
    </div>
    <div class="form-group">
        <label for="mascota-imagen">URL de la Imagen</label>
        <input type="url" id="mascota-imagen" name="imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
    </div>
    <button type="submit" class="btn-primary">Publicar Mascota</button>
</form>

