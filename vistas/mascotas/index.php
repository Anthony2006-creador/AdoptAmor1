<link rel="stylesheet" type="text/css" href="public/css/estilosMascotaI.css">
<div class="vista-container">
    <h2>Mascotas en Adopción</h2>
    <p>Encuentra a tu nuevo mejor amigo.</p>
    
    <div class="grid-mascotas">
        <?php if (isset($mascotas) && !empty($mascotas)): ?>
            <?php foreach ($mascotas as $mascota): ?>
                <div class="card-mascota" style="border:1px solid var(--color-border); background:var(--color-surface); padding: 15px; border-radius: 8px;">
                    <h3><?php echo htmlspecialchars($mascota['nombre']); ?></h3>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($mascota['tipo']); ?></p>
                    <p><strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza']); ?></p>
                    <p><strong>Edad:</strong> <?php echo htmlspecialchars($mascota['edad']); ?> años</p>
                    <button class="btn-adoptar" data-id="1">Adoptar</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay mascotas disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</div>

