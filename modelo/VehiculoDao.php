<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Vehiculo.php';

class VehiculoDao
{
    private $conexion;

    private $baseSelect = "SELECT v.id, v.placa, v.color, v.modelo, v.propietario, v.tipo_id, v.espacio_id, v.imagenfoto, v.fecha_ingreso,
                                  t.nombre AS tipo_nombre, e.numero AS espacio_numero
                           FROM vehiculo v
                           INNER JOIN tipo_vehiculo t ON t.id = v.tipo_id
                           INNER JOIN espacio e ON e.id = v.espacio_id";

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->getConexion();
    }

    // Convierte una fila de la base de datos en un objeto Vehiculo
    private function filaAVehiculo($fila){
        return new Vehiculo(
            $fila['id'],
            $fila['placa'],
            $fila['propietario'],
            $fila['tipo_id'],
            $fila['espacio_id'],
            $fila['imagenfoto'],
            $fila['fecha_ingreso'],
            $fila['tipo_nombre'],
            $fila['espacio_numero'],
            $fila['color'],
            $fila['modelo']
        );
    }

    // CREATE: registrar un vehículo
    public function insertar(Vehiculo $vehiculo){
        $sql = "INSERT INTO vehiculo (placa, color, modelo, propietario, tipo_id, espacio_id, imagenfoto)
                VALUES (:placa, :color, :modelo, :propietario, :tipo_id, :espacio_id, :imagenfoto)";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':placa'       => $vehiculo->getPlaca(),
            ':color'       => $vehiculo->getColor(),
            ':modelo'      => $vehiculo->getModelo(),
            ':propietario' => $vehiculo->getPropietario(),
            ':tipo_id'     => $vehiculo->getTipoId(),
            ':espacio_id'  => $vehiculo->getEspacioId(),
            ':imagenfoto'  => $vehiculo->getImagenfoto()
        ]);
    }

    // READ: listar todos los vehículos
    public function listar(){
        $stmt = $this->conexion->query($this->baseSelect . " ORDER BY v.id DESC");

        $vehiculos = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehiculos[] = $this->filaAVehiculo($fila);
        }

        return $vehiculos;
    }

    // READ: buscar por placa, propietario o tipo
    public function buscar($termino){
        $sql = $this->baseSelect . "
                WHERE v.placa LIKE :placa OR v.color LIKE :color OR v.modelo LIKE :modelo
                   OR v.propietario LIKE :propietario OR t.nombre LIKE :tipo
                ORDER BY v.id DESC";
        $stmt = $this->conexion->prepare($sql);
        $like = '%' . $termino . '%';
        $stmt->execute([
            ':placa'       => $like,
            ':color'       => $like,
            ':modelo'      => $like,
            ':propietario' => $like,
            ':tipo'        => $like
        ]);

        $vehiculos = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehiculos[] = $this->filaAVehiculo($fila);
        }

        return $vehiculos;
    }

    // READ: obtener un vehículo por id
    public function obtenerPorId($id){
        $stmt = $this->conexion->prepare($this->baseSelect . " WHERE v.id = :id");
        $stmt->execute([':id' => $id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila ? $this->filaAVehiculo($fila) : null;
    }

    // Verifica si ya existe una placa (ignorando el id indicado al editar)
    public function existePlaca($placa, $idExcluir = 0){
        $sql = "SELECT COUNT(*) FROM vehiculo WHERE placa = :placa AND id <> :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':placa' => $placa, ':id' => (int)$idExcluir]);
        return $stmt->fetchColumn() > 0;
    }

    // Verifica si un espacio ya está ocupado (ignorando el id indicado al editar)
    public function existeEspacio($espacioId, $idExcluir = 0){
        $sql = "SELECT COUNT(*) FROM vehiculo WHERE espacio_id = :espacio AND id <> :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':espacio' => (int)$espacioId, ':id' => (int)$idExcluir]);
        return $stmt->fetchColumn() > 0;
    }

    // UPDATE: actualizar un vehículo
    public function actualizar(Vehiculo $vehiculo){
        $sql = "UPDATE vehiculo
                SET placa = :placa,
                    color = :color,
                    modelo = :modelo,
                    propietario = :propietario,
                    tipo_id = :tipo_id,
                    espacio_id = :espacio_id,
                    imagenfoto = :imagenfoto
                WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':placa'       => $vehiculo->getPlaca(),
            ':color'       => $vehiculo->getColor(),
            ':modelo'      => $vehiculo->getModelo(),
            ':propietario' => $vehiculo->getPropietario(),
            ':tipo_id'     => $vehiculo->getTipoId(),
            ':espacio_id'  => $vehiculo->getEspacioId(),
            ':imagenfoto'  => $vehiculo->getImagenfoto(),
            ':id'          => $vehiculo->getId()
        ]);
    }

    // DELETE: eliminar un vehículo (registrar su salida)
    public function eliminar($id){
        $sql = "DELETE FROM vehiculo WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
