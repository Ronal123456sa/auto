-- Ejecutar una sola vez en la base de datos parqueo para una instalación existente.
-- Los vehículos anteriores quedan con color y modelo vacíos hasta que se editen.
USE parqueo;

ALTER TABLE vehiculo
    ADD COLUMN color VARCHAR(40) NOT NULL DEFAULT '' AFTER placa,
    ADD COLUMN modelo VARCHAR(60) NOT NULL DEFAULT '' AFTER color;
