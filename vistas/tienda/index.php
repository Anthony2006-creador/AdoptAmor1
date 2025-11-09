<link rel="stylesheet" type="text/css" href="public/css/estilosTienda.css">
<div class="vista-container">
    <h2>Nuestra Tienda</h2>
    <p>Todo lo que necesitas para tu compañero.</p>

    <div class="tienda-grid">
        <?php if (isset($productos) && !empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                
                <div class="producto-card">
                    <div class="producto-imagen" style="background-image: url('<?php echo htmlspecialchars($producto['imagen']); ?>');">
                        </div>
                    <div class="producto-info">
                        <h3 class="producto-nombre"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="producto-descripcion"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 100)); ?>...</p>
                        <div class="producto-footer">
                            <span class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></span>
                            <span class="producto-stock">Quedan: <?php echo htmlspecialchars($producto['stock']); ?></span>
                        </div>
                        <button class="btn-comprar-producto" data-id="<?php echo $producto['ID_producto']; ?>">
                            <i class="fa-solid fa-cart-plus"></i> Comprar
                        </button>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles en la tienda en este momento.</p>
        <?php endif; ?>
    </div>
</div>
