<?php

// Router principal: ?modulo=vehiculo|tipo|espacio  &  ?accion=index|crear|guardar|editar|actualizar|eliminar

$modulos = [
    'vehiculo' => ['VehiculoController',      __DIR__ . '/controlador/VehiculoController.php'],
    'tipo'     => ['TipoVehiculoController',  __DIR__ . '/controlador/TipoVehiculoController.php'],
    'espacio'  => ['EspacioController',       __DIR__ . '/controlador/EspacioController.php'],
];

$accionesPermitidas = ['index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar'];

$modulo = $_GET['modulo'] ?? 'vehiculo';
$accion = $_GET['accion'] ?? 'index';

if (!isset($modulos[$modulo])) {
    $modulo = 'vehiculo';
}
if (!in_array($accion, $accionesPermitidas, true)) {
    $accion = 'index';
}

list($clase, $archivo) = $modulos[$modulo];
require_once $archivo;

$controlador = new $clase();
$controlador->$accion();
