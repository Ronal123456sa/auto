<?php

class Espacio
{
    private $id;
    private $numero;
    private $zona;
    private $ocupadoPor; // solo lectura: placa del vehículo que lo ocupa (o null si está libre)

    public function __construct($id = null, $numero = 0, $zona = "", $ocupadoPor = null){
        $this->id = $id;
        $this->numero = $numero;
        $this->zona = $zona;
        $this->ocupadoPor = $ocupadoPor;
    }

    public function getId(){ return $this->id; }
    public function getNumero(){ return $this->numero; }
    public function getZona(){ return $this->zona; }
    public function getOcupadoPor(){ return $this->ocupadoPor; }

    public function setId($id){ $this->id = $id; }
    public function setNumero($numero){ $this->numero = $numero; }
    public function setZona($zona){ $this->zona = $zona; }
}
