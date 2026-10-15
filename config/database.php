<?php

class Database
{
    private $host = "127.0.0.1";
    private $puerto = "3306";      // Cambia a 3306 si tu MySQL usa el puerto por defecto
    private $db_name = "parqueo";
    private $usuario = "root";
    private $password = "";
    private $conexion;

    public function getConexion()
    {
        $this->conexion = null;

        try {
            $this->conexion = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->puerto . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->usuario,
                $this->password
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return $this->conexion;
    }
}
