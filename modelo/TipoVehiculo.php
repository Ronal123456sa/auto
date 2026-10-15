<?php

class TipoVehiculo
{
    private $id;
    private $nombre;
    private $tarifaHora;

    public function __construct($id = null, $nombre = "", $tarifaHora = 0){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tarifaHora = $tarifaHora;
    }

    public function getId(){ return $this->id; }
    public function getNombre(){ return $this->nombre; }
    public function getTarifaHora(){ return $this->tarifaHora; }

    public function setId($id){ $this->id = $id; }
    public function setNombre($nombre){ $this->nombre = $nombre; }
    public function setTarifaHora($tarifaHora){ $this->tarifaHora = $tarifaHora; }
}
