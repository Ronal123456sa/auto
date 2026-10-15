<?php

require_once __DIR__ . '/../modelo/Espacio.php';
require_once __DIR__ . '/../modelo/EspacioDao.php';

class EspacioController{
    private $dao;

    public function __construct(){
        $this->dao = new EspacioDao();
    }

    private function volver($mensaje = ''){
        $url = "index.php?modulo=espacio&accion=index";
        if ($mensaje !== '') {
            $url .= "&mensaje=" . urlencode($mensaje);
        }
        header("Location: " . $url);
        exit;
    }

    public function index(){
        $mensaje = $_GET['mensaje'] ?? '';
        $espacios = $this->dao->listar();
        require __DIR__ . '/../vista/espacios/index.php';
    }

    public function crear(){
        $espacio = new Espacio();
        $errores = [];
        require __DIR__ . '/../vista/espacios/formulario.php';
    }

    public function guardar(){
        $espacio = $this->desdePost(null);
        $errores = $this->validar($espacio);

        if (empty($errores)) {
            try {
                $this->dao->insertar($espacio);
                $this->volver();
            } catch (PDOException $e) {
                $errores[] = "Error al guardar el espacio.";
            }
        }

        require __DIR__ . '/../vista/espacios/formulario.php';
    }

    public function editar(){
        $espacio = $this->dao->obtenerPorId((int)($_GET['id'] ?? 0));
        if (!$espacio) {
            $this->volver();
        }
        $errores = [];
        require __DIR__ . '/../vista/espacios/formulario.php';
    }

    public function actualizar(){
        $id = (int)($_POST['id'] ?? 0);
        if (!$this->dao->obtenerPorId($id)) {
            $this->volver();
        }

        $espacio = $this->desdePost($id);
        $errores = $this->validar($espacio);

        if (empty($errores)) {
            try {
                $this->dao->actualizar($espacio);
                $this->volver();
            } catch (PDOException $e) {
                $errores[] = "Error al actualizar el espacio.";
            }
        }

        require __DIR__ . '/../vista/espacios/formulario.php';
    }

    // Se exige método POST para que no se pueda borrar con solo abrir un enlace
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->dao->eliminar((int)($_POST['id'] ?? 0));
            } catch (PDOException $e) {
                $this->volver("No se puede eliminar: hay un vehículo registrado en este espacio.");
            }
        }
        $this->volver();
    }

    private function desdePost($id){
        return new Espacio($id, $_POST['numero'] ?? 0, trim($_POST['zona'] ?? ''));
    }

    private function validar(Espacio $espacio){
        $errores = [];
        $id = $espacio->getId() ?? 0;

        $numero = $espacio->getNumero();
        if (filter_var($numero, FILTER_VALIDATE_INT) === false || (int)$numero <= 0) {
            $errores[] = "El número de espacio debe ser un entero mayor a 0.";
        } elseif ($this->dao->existeNumero($numero, $id)) {
            $errores[] = "Ya existe el espacio número " . (int)$numero . ".";
        }

        if ($espacio->getZona() === '') {
            $errores[] = "La zona es obligatoria.";
        } elseif (mb_strlen($espacio->getZona()) > 50) {
            $errores[] = "La zona no puede superar los 50 caracteres.";
        }

        return $errores;
    }
}
