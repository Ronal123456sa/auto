-- Instalación NUEVA del sistema de Parqueo. 3 tablas: tipo_vehiculo, espacio, vehiculo
-- (Si ya tienes vehículos guardados, usa migracion_desde_version_anterior.sql)
CREATE DATABASE IF NOT EXISTS parqueo
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE parqueo;

DROP TABLE IF EXISTS vehiculo;
DROP TABLE IF EXISTS espacio;
DROP TABLE IF EXISTS tipo_vehiculo;

CREATE TABLE tipo_vehiculo (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(30)  NOT NULL UNIQUE,
    tarifa_hora DECIMAL(6,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE espacio (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    numero INT         NOT NULL UNIQUE,
    zona   VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE vehiculo (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    placa         VARCHAR(10)  NOT NULL UNIQUE,
    color         VARCHAR(40)  NOT NULL DEFAULT '',
    modelo        VARCHAR(60)  NOT NULL DEFAULT '',
    propietario   VARCHAR(100) NOT NULL,
    tipo_id       INT          NOT NULL,
    espacio_id    INT          NOT NULL UNIQUE,
    imagenfoto    VARCHAR(255) NOT NULL DEFAULT '',
    fecha_ingreso TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_vehiculo_tipo    FOREIGN KEY (tipo_id)    REFERENCES tipo_vehiculo(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_vehiculo_espacio FOREIGN KEY (espacio_id) REFERENCES espacio(id)       ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Datos de ejemplo
INSERT INTO tipo_vehiculo (nombre, tarifa_hora) VALUES
('Auto', 5.00), ('Moto', 2.00), ('Camioneta', 7.00), ('Camión', 10.00), ('Bicicleta', 1.00);

INSERT INTO espacio (numero, zona) VALUES
(1, 'Planta baja'), (2, 'Planta baja'), (3, 'Planta baja'),
(4, 'Planta baja'), (5, 'Planta baja'), (6, 'Primer piso'),
(7, 'Primer piso'), (8, 'Primer piso'), (9, 'Primer piso'), (10, 'Primer piso');

-- Vehículos de ejemplo (sin foto; puedes editarlos y subir una imagen)
INSERT INTO vehiculo (placa, propietario, tipo_id, espacio_id) VALUES
('1234ABC', 'Juan Pérez',   1, 1),
('5678XYZ', 'María Gómez',  2, 2),
('9012DEF', 'Carlos Rojas', 3, 3);
