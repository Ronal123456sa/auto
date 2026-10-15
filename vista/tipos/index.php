<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - Tipos de vehículo</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1>Tipos de vehículo</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="errores"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <a href="index.php?modulo=tipo&accion=crear" class="boton boton-agregar">+ Agregar tipo</a>

        <?php if (empty($tipos)): ?>
            <p class="mensaje-vacio">Aún no hay tipos registrados.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tarifa por hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tipos as $t): ?>
                        <tr>
                            <td><?= (int)$t->getId() ?></td>
                            <td><?= htmlspecialchars($t->getNombre()) ?></td>
                            <td><?= number_format((float)$t->getTarifaHora(), 2) ?></td>
                            <td>
                                <a href="index.php?modulo=tipo&accion=editar&id=<?= (int)$t->getId() ?>"
                                   class="boton boton-editar">Editar</a>
                                <form action="index.php?modulo=tipo&accion=eliminar" method="POST" class="form-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este tipo?');">
                                    <input type="hidden" name="id" value="<?= (int)$t->getId() ?>">
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
