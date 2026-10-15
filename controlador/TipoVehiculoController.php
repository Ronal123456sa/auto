<?php

require_once __DIR__ . '/../modelo/TipoVehiculo.php';
require_once __DIR__ . '/../modelo/TipoVehiculoDao.php';

class TipoVehiculoController{
    private $dao;

    public function __construct(){
        $this->dao = new TipoVehiculoDao();
    }

    private function volver($mensaje = ''){
        $url = "index.php?modulo=tipo&accion=index";
        if ($mensaje !== '') {
            $url .= "&mensaje=" . urlencode($mensaje);
        }
        header("Location: " . $url);
        exit;
    }

    public function index(){
        $mensaje = $_GET['mensaje'] ?? '';
        $tipos = $this->dao->listar();
        require __DIR__ . '/../vista/tipos/index.php';
    }

    public function crear(){
        $tipo = new TipoVehiculo();
        $errores = [];
        require __DIR__ . '/../vista/tipos/formulario.php';
    }

    public function guardar(){
        $tipo = $this->desdePost(null);
        $errores = $this->validar($tipo);

        if (empty($errores)) {
            try {
                $this->dao->insertar($tipo);
                $this->volver();
            } catch (PDOException $e) {
                $errores[] = ($e->getCode() == 23000) ? "Ya existe un tipo con ese nombre." : "Error al guardar el tipo.";
            }
        }

        require __DIR__ . '/../vista/tipos/formulario.php';
    }

    public function editar(){
        $tipo = $this->dao->obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$tipo) {
            $this->volver();
        }
        $errores = [];
        require __DIR__ . '/../vista/tipos/formulario.php';
    }

    public function actualizar(){
        $id = (int)($_POST['id'] ?? 0);
        if (!$this->dao->obtenerPorId($id)) {
            $this->volver();
        }

        $tipo = $this->desdePost($id);
        $errores = $this->validar($tipo);

        if (empty($errores)) {
            try {
                $this->dao->actualizar($tipo);
                $this->volver();
            } catch (PDOException $e) {
                $errores[] = ($e->getCode() == 23000) ? "Ya existe un tipo con ese nombre." : "Error al actualizar el tipo.";
            }
        }

        require __DIR__ . '/../vista/tipos/formulario.php';
    }

    // Se exige método POST para que no se pueda borrar con solo abrir un enlace
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->dao->eliminar((int)($_POST['id'] ?? 0));
            } catch (PDOException $e) {
                $this->volver("No se puede eliminar: hay vehículos registrados con este tipo.");
            }
        }
        $this->volver();
    }

    private function desdePost($id){
        return new TipoVehiculo($id, trim($_POST['nombre'] ?? ''), trim($_POST['tarifa_hora'] ?? ''));
    }

    private function validar(TipoVehiculo $tipo){
        $errores = [];

        if ($tipo->getNombre() === '') {
            $errores[] = "El nombre del tipo es obligatorio.";
        } elseif (mb_strlen($tipo->getNombre()) > 30) {
            $errores[] = "El nombre no puede superar los 30 caracteres.";
        }

        $tarifa = $tipo->getTarifaHora();
        if (!is_numeric($tarifa) || $tarifa < 0 || $tarifa > 9999.99) {
            $errores[] = "La tarifa por hora debe ser un número igual o mayor a 0.";
        }

        return $errores;
    }
}
