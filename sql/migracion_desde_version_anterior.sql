-- Úsalo SOLO si ya tenías la tabla vehiculo con las columnas "tipo" (texto) y "espacio" (número).
-- Conserva tus vehículos: crea tipo_vehiculo y espacio, pasa los valores existentes y los enlaza.
USE parqueo;

CREATE TABLE IF NOT EXISTS tipo_vehiculo (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(30)  NOT NULL UNIQUE,
    tarifa_hora DECIMAL(6,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS espacio (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    numero INT         NOT NULL UNIQUE,
    zona   VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- Tipos: los que ya usaban tus vehículos + los de la lista original
INSERT IGNORE INTO tipo_vehiculo (nombre) SELECT DISTINCT tipo FROM vehiculo WHERE tipo <> '';
INSERT IGNORE INTO tipo_vehiculo (nombre) VALUES ('Auto'), ('Moto'), ('Camioneta'), ('Camión'), ('Bicicleta');

-- Espacios: los números que ya estaban ocupados (zona "General", puedes cambiarla después)
INSERT IGNORE INTO espacio (numero, zona) SELECT DISTINCT espacio, 'General' FROM vehiculo;

ALTER TABLE vehiculo ADD COLUMN tipo_id INT NULL, ADD COLUMN espacio_id INT NULL;

UPDATE vehiculo v
JOIN tipo_vehiculo t ON t.nombre = v.tipo
JOIN espacio e ON e.numero = v.espacio
SET v.tipo_id = t.id, v.espacio_id = e.id;

ALTER TABLE vehiculo
    MODIFY tipo_id INT NOT NULL,
    MODIFY espacio_id INT NOT NULL,
    ADD UNIQUE KEY uq_vehiculo_espacio (espacio_id),
    ADD CONSTRAINT fk_vehiculo_tipo    FOREIGN KEY (tipo_id)    REFERENCES tipo_vehiculo(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    ADD CONSTRAINT fk_vehiculo_espacio FOREIGN KEY (espacio_id) REFERENCES espacio(id)       ON DELETE RESTRICT ON UPDATE CASCADE,
    DROP COLUMN tipo,
    DROP COLUMN espacio;
