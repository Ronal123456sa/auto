<?php $moduloActual = $_GET['modulo'] ?? 'vehiculo'; ?>
<nav class="menu">
    <a href="index.php?modulo=vehiculo&accion=index" class="<?= $moduloActual === 'vehiculo' ? 'activo' : '' ?>">Vehículos</a>
    <a href="index.php?modulo=tipo&accion=index" class="<?= $moduloActual === 'tipo' ? 'activo' : '' ?>">Tipos</a>
    <a href="index.php?modulo=espacio&accion=index" class="<?= $moduloActual === 'espacio' ? 'activo' : '' ?>">Espacios</a>
</nav>
