<?php

class Vehiculo
{
    private $id;
    private $placa;
    private $color;
    private $modelo;
    private $propietario;
    private $tipoId;
    private $espacioId;
    private $imagenfoto;
    private $fechaIngreso;
    private $tipoNombre;     // solo lectura (JOIN con tipo_vehiculo)
    private $espacioNumero;  // solo lectura (JOIN con espacio)

    public function __construct($id = null, $placa = "", $propietario = "", $tipoId = null, $espacioId = null, $imagenfoto = "", $fechaIngreso = null, $tipoNombre = "", $espacioNumero = null, $color = "", $modelo = ""){
        $this->id = $id;
        $this->placa = $placa;
        $this->color = $color;
        $this->modelo = $modelo;
        $this->propietario = $propietario;
        $this->tipoId = $tipoId;
        $this->espacioId = $espacioId;
        $this->imagenfoto = $imagenfoto;
        $this->fechaIngreso = $fechaIngreso;
        $this->tipoNombre = $tipoNombre;
        $this->espacioNumero = $espacioNumero;
    }

    // Getters
    public function getId(){ return $this->id; }
    public function getPlaca(){ return $this->placa; }
    public function getColor(){ return $this->color; }
    public function getModelo(){ return $this->modelo; }
    public function getPropietario(){ return $this->propietario; }
    public function getTipoId(){ return $this->tipoId; }
    public function getEspacioId(){ return $this->espacioId; }
    public function getImagenfoto(){ return $this->imagenfoto; }
    public function getFechaIngreso(){ return $this->fechaIngreso; }
    public function getTipoNombre(){ return $this->tipoNombre; }
    public function getEspacioNumero(){ return $this->espacioNumero; }

    // Setters
    public function setId($id){ $this->id = $id; }
    public function setPlaca($placa){ $this->placa = $placa; }
    public function setColor($color){ $this->color = $color; }
    public function setModelo($modelo){ $this->modelo = $modelo; }
    public function setPropietario($propietario){ $this->propietario = $propietario; }
    public function setTipoId($tipoId){ $this->tipoId = $tipoId; }
    public function setEspacioId($espacioId){ $this->espacioId = $espacioId; }
    public function setImagenfoto($imagenfoto){ $this->imagenfoto = $imagenfoto; }
    public function setFechaIngreso($fechaIngreso){ $this->fechaIngreso = $fechaIngreso; }
}
