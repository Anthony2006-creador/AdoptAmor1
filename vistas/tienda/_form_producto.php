<link rel="stylesheet" type="text/css" href="public/css/estilosProductos.css">
<button class="modal-close-btn"><i class="fa-solid fa-times"></i></button>
<h3>Publicar Producto</h3>
<p>Completa los datos de tu producto.</p>

<form id="form-publicar-producto" novalidate>
    <div class="form-group">
        <label for="producto-nombre">Nombre del Producto</label>
        <input type="text" id="producto-nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="producto-desc">Descripción</label>
        <textarea id="producto-desc" name="descripcion" rows="3" required></textarea>
    </div>
    <div class="form-group-inline">
        <div class="form-group">
            <label for="producto-precio">Precio (Ej: 19.99)</label>
            <input type="number" id="producto-precio" name="precio" min="0.01" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="producto-stock">Stock</label>
            <input type="number" id="producto-stock" name="stock" min="0" step="1" required>
        </div>
    </div>
    <div class="form-group">
        <label for="producto-imagen">URL de la Imagen</label>
        <input type="url" id="producto-imagen" name="imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
    </div>
    <button type="submit" class="btn-primary">Publicar Producto</button>
</form>
