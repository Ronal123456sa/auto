<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - Listado de vehículos</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1>Parqueo - Vehículos registrados</h1>

        <a href="index.php?modulo=vehiculo&accion=crear" class="boton boton-agregar">+ Registrar vehículo</a>

        <!-- Formulario de busqueda -->
        <form action="index.php" method="GET" class="buscador">
            <input type="hidden" name="modulo" value="vehiculo">
            <input type="hidden" name="accion" value="index">
            <input type="text" name="buscar" placeholder="Buscar por placa, propietario o tipo..."
                   value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="boton boton-editar">Buscar</button>
            <?php if ($busqueda !== ''): ?>
                <a href="index.php?modulo=vehiculo&accion=index" class="boton boton-cancelar">Limpiar</a>
            <?php endif; ?>
        </form>
        <?php if ($busqueda !== ''): ?>
            <p class="resultado-busqueda">
                <?= count($vehiculos) ?> resultado(s) para
                "<strong><?= htmlspecialchars($busqueda) ?></strong>"
            </p>
        <?php endif; ?>

        <?php if (empty($vehiculos)): ?>
            <p class="mensaje-vacio">
                <?= $busqueda !== '' ? 'No se encontraron vehículos con ese criterio.' : 'Aún no hay vehículos registrados.' ?>
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Placa</th>
                        <th>Color</th>
                        <th>Modelo</th>
                        <th>Propietario</th>
                        <th>Tipo</th>
                        <th>Espacio</th>
                        <th>Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehiculos as $v): ?>
                        <tr>
                            <td><?= (int)$v->getId() ?></td>
                            <td>
                                <?php if ($v->getImagenfoto() !== ''): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($v->getImagenfoto()) ?>"
                                         alt="Foto del vehículo <?= htmlspecialchars($v->getPlaca()) ?>"
                                         class="foto">
                                <?php else: ?>
                                    <div class="sin-foto">Sin foto</div>
                                <?php endif; ?>
                            </td>
                            <td><span class="placa"><?= htmlspecialchars($v->getPlaca()) ?></span></td>
                            <td><?= htmlspecialchars($v->getColor()) ?></td>
                            <td><?= htmlspecialchars($v->getModelo()) ?></td>
                            <td><?= htmlspecialchars($v->getPropietario()) ?></td>
                            <td><?= htmlspecialchars($v->getTipoNombre()) ?></td>
                            <td><?= (int)$v->getEspacioNumero() ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($v->getFechaIngreso()))) ?></td>
                            <td>
                                <a href="index.php?modulo=vehiculo&accion=editar&id=<?= (int)$v->getId() ?>"
                                   class="boton boton-editar">Editar</a>
                                <form action="index.php?modulo=vehiculo&accion=eliminar" method="POST" class="form-inline"
                                      onsubmit="return confirm('¿Registrar la salida y eliminar este vehículo?');">
                                    <input type="hidden" name="id" value="<?= (int)$v->getId() ?>">
                                    <button type="submit" class="boton boton-eliminar">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
