<?php $esEdicion = $vehiculo->getId() !== null; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - <?= $esEdicion ? 'Editar' : 'Registrar' ?> vehículo</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1><?= $esEdicion ? 'Editar vehículo' : 'Registrar vehículo' ?></h1>

        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (empty($tipos)): ?>
            <p class="mensaje-vacio">
                Primero debes registrar al menos un tipo de vehículo.
                <a href="index.php?modulo=tipo&accion=crear">Crear tipo</a>
            </p>
        <?php endif; ?>

        <?php if (empty($espacios)): ?>
            <p class="mensaje-vacio">
                No hay espacios libres. Registra un espacio nuevo o libera uno.
                <a href="index.php?modulo=espacio&accion=crear">Crear espacio</a>
            </p>
        <?php endif; ?>

        <form action="index.php?modulo=vehiculo&accion=<?= $esEdicion ? 'actualizar' : 'guardar' ?>"
              method="POST"
              enctype="multipart/form-data">

            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?= (int)$vehiculo->getId() ?>">
            <?php endif; ?>

            <label for="placa">Placa</label>
            <input type="text" id="placa" name="placa" maxlength="10" required
                   style="text-transform: uppercase;"
                   value="<?= htmlspecialchars($vehiculo->getPlaca()) ?>">

            <label for="color">Color</label>
            <input type="text" id="color" name="color" maxlength="40" required
                   value="<?= htmlspecialchars($vehiculo->getColor()) ?>">

            <label for="modelo">Modelo</label>
            <input type="text" id="modelo" name="modelo" maxlength="60" required
                   value="<?= htmlspecialchars($vehiculo->getModelo()) ?>">

            <label for="propietario">Propietario</label>
            <input type="text" id="propietario" name="propietario" maxlength="100" required
                   value="<?= htmlspecialchars($vehiculo->getPropietario()) ?>">

            <label for="tipo_id">Tipo de vehículo</label>
            <select id="tipo_id" name="tipo_id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= (int)$t->getId() ?>"
                        <?= (int)$vehiculo->getTipoId() === (int)$t->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="espacio_id">Espacio de parqueo (solo libres)</label>
            <select id="espacio_id" name="espacio_id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($espacios as $e): ?>
                    <option value="<?= (int)$e->getId() ?>"
                        <?= (int)$vehiculo->getEspacioId() === (int)$e->getId() ? 'selected' : '' ?>>
                        Espacio <?= (int)$e->getNumero() ?> - <?= htmlspecialchars($e->getZona()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="imagenfoto">Foto del vehículo</label>
            <?php if ($esEdicion && $vehiculo->getImagenfoto()): ?>
                <div class="foto-actual">
                    <img src="public/uploads/<?= htmlspecialchars($vehiculo->getImagenfoto()) ?>"
                         alt="Foto actual" class="foto">
                    <small>Foto actual. Sube otra imagen solo si quieres cambiarla.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="imagenfoto" name="imagenfoto"
                   accept=".jpg,.jpeg,.png,.gif,.webp"
                   <?= ($esEdicion && $vehiculo->getImagenfoto()) ? '' : 'required' ?>>

            <div class="acciones-form">
                <button type="submit" class="boton boton-agregar">
                    <?= $esEdicion ? 'Guardar cambios' : 'Registrar vehículo' ?>
                </button>
                <a href="index.php?modulo=vehiculo&accion=index" class="boton boton-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
