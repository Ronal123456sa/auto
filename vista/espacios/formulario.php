<?php $esEdicion = $espacio->getId() !== null; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - <?= $esEdicion ? 'Editar' : 'Agregar' ?> espacio</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1><?= $esEdicion ? 'Editar espacio' : 'Agregar espacio' ?></h1>

        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="index.php?modulo=espacio&accion=<?= $esEdicion ? 'actualizar' : 'guardar' ?>" method="POST">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?= (int)$espacio->getId() ?>">
            <?php endif; ?>

            <label for="numero">Número de espacio</label>
            <input type="number" id="numero" name="numero" min="1" step="1" required
                   value="<?= $espacio->getNumero() ? (int)$espacio->getNumero() : '' ?>">

            <label for="zona">Zona</label>
            <input type="text" id="zona" name="zona" maxlength="50" required
                   value="<?= htmlspecialchars($espacio->getZona()) ?>">

            <div class="acciones-form">
                <button type="submit" class="boton boton-agregar">
                    <?= $esEdicion ? 'Guardar cambios' : 'Guardar espacio' ?>
                </button>
                <a href="index.php?modulo=espacio&accion=index" class="boton boton-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
