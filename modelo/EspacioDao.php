<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Espacio.php';

class EspacioDao
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->getConexion();
    }

    // CREATE
    public function insertar(Espacio $espacio){
        $stmt = $this->conexion->prepare("INSERT INTO espacio (numero, zona) VALUES (:numero, :zona)");
        return $stmt->execute([':numero' => $espacio->getNumero(), ':zona' => $espacio->getZona()]);
    }

    // READ: todos los espacios con la placa de quien lo ocupa (si hay alguien)
    public function listar(){
        $sql = "SELECT e.id, e.numero, e.zona, v.placa
                FROM espacio e
                LEFT JOIN vehiculo v ON v.espacio_id = e.id
                ORDER BY e.numero ASC";
        $stmt = $this->conexion->query($sql);

        $espacios = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $espacios[] = new Espacio($fila['id'], $fila['numero'], $fila['zona'], $fila['placa']);
        }
        return $espacios;
    }

    // READ: espacios libres (el vehículo indicado no cuenta como ocupante, útil al editar)
    public function listarLibres($idVehiculoExcluir = 0){
        $sql = "SELECT e.id, e.numero, e.zona
                FROM espacio e
                WHERE NOT EXISTS (
                    SELECT 1 FROM vehiculo v WHERE v.espacio_id = e.id AND v.id <> :id
                )
                ORDER BY e.numero ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => (int)$idVehiculoExcluir]);

        $espacios = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $espacios[] = new Espacio($fila['id'], $fila['numero'], $fila['zona']);
        }
        return $espacios;
    }

    public function obtenerPorId($id){
        $stmt = $this->conexion->prepare("SELECT id, numero, zona FROM espacio WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? new Espacio($fila['id'], $fila['numero'], $fila['zona']) : null;
    }

    // Verifica si ya existe ese número de espacio (ignorando el id indicado al editar)
    public function existeNumero($numero, $idExcluir = 0){
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM espacio WHERE numero = :numero AND id <> :id");
        $stmt->execute([':numero' => (int)$numero, ':id' => (int)$idExcluir]);
        return $stmt->fetchColumn() > 0;
    }

    // UPDATE
    public function actualizar(Espacio $espacio){
        $stmt = $this->conexion->prepare("UPDATE espacio SET numero = :numero, zona = :zona WHERE id = :id");
        return $stmt->execute([
            ':numero' => $espacio->getNumero(),
            ':zona'   => $espacio->getZona(),
            ':id'     => $espacio->getId()
        ]);
    }

    // DELETE (falla si hay un vehículo en ese espacio)
    public function eliminar($id){
        $stmt = $this->conexion->prepare("DELETE FROM espacio WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
