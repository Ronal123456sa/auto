<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/TipoVehiculo.php';

class TipoVehiculoDao
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->getConexion();
    }

    private function aObjeto($fila){
        return new TipoVehiculo($fila['id'], $fila['nombre'], $fila['tarifa_hora']);
    }

    // CREATE
    public function insertar(TipoVehiculo $tipo){
        $stmt = $this->conexion->prepare("INSERT INTO tipo_vehiculo (nombre, tarifa_hora) VALUES (:nombre, :tarifa)");
        return $stmt->execute([':nombre' => $tipo->getNombre(), ':tarifa' => $tipo->getTarifaHora()]);
    }

    // READ
    public function listar(){
        $stmt = $this->conexion->query("SELECT id, nombre, tarifa_hora FROM tipo_vehiculo ORDER BY nombre ASC");
        $tipos = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tipos[] = $this->aObjeto($fila);
        }
        return $tipos;
    }

    public function obtenerPorId($id){
        $stmt = $this->conexion->prepare("SELECT id, nombre, tarifa_hora FROM tipo_vehiculo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $this->aObjeto($fila) : null;
    }

    // UPDATE
    public function actualizar(TipoVehiculo $tipo){
        $stmt = $this->conexion->prepare("UPDATE tipo_vehiculo SET nombre = :nombre, tarifa_hora = :tarifa WHERE id = :id");
        return $stmt->execute([
            ':nombre' => $tipo->getNombre(),
            ':tarifa' => $tipo->getTarifaHora(),
            ':id'     => $tipo->getId()
        ]);
    }

    // DELETE (falla si hay vehículos de este tipo)
    public function eliminar($id){
        $stmt = $this->conexion->prepare("DELETE FROM tipo_vehiculo WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
