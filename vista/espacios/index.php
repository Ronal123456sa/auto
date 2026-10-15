<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueo - Espacios</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <?php require __DIR__ . '/../partials/menu.php'; ?>
        <h1>Espacios de parqueo</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="errores"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <a href="index.php?modulo=espacio&accion=crear" class="boton boton-agregar">+ Agregar espacio</a>

        <?php if (empty($espacios)): ?>
            <p class="mensaje-vacio">Aún no hay espacios registrados.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Zona</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($espacios as $e): ?>
                        <tr>
                            <td><?= (int)$e->getId() ?></td>
                            <td><?= (int)$e->getNumero() ?></td>
                            <td><?= htmlspecialchars($e->getZona()) ?></td>
                            <td>
                                <?php if ($e->getOcupadoPor() !== null): ?>
                                    Ocupado (<span class="placa"><?= htmlspecialchars($e->getOcupadoPor()) ?></span>)
                                <?php else: ?>
                                    Libre
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?modulo=espacio&accion=editar&id=<?= (int)$e->getId() ?>"
                                   class="boton boton-editar">Editar</a>
                                <form action="index.php?modulo=espacio&accion=eliminar" method="POST" class="form-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este espacio?');">
                                    <input type="hidden" name="id" value="<?= (int)$e->getId() ?>">
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
