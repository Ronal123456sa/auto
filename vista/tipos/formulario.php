<?php $esEdicion = $tipo->getId() !== null; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - <?= $esEdicion ? 'Editar' : 'Agregar' ?> tipo</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1><?= $esEdicion ? 'Editar tipo de vehículo' : 'Agregar tipo de vehículo' ?></h1>

        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="index.php?modulo=tipo&accion=<?= $esEdicion ? 'actualizar' : 'guardar' ?>" method="POST">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?= (int)$tipo->getId() ?>">
            <?php endif; ?>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="30" required
                   value="<?= htmlspecialchars($tipo->getNombre()) ?>">

            <label for="tarifa_hora">Tarifa por hora</label>
            <input type="number" id="tarifa_hora" name="tarifa_hora" min="0" step="0.01" required
                   value="<?= htmlspecialchars((string)$tipo->getTarifaHora()) ?>">

            <div class="acciones-form">
                <button type="submit" class="boton boton-agregar">
                    <?= $esEdicion ? 'Guardar cambios' : 'Guardar tipo' ?>
                </button>
                <a href="index.php?modulo=tipo&accion=index" class="boton boton-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
