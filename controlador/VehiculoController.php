<?php

require_once __DIR__ . '/../modelo/Vehiculo.php';
require_once __DIR__ . '/../modelo/VehiculoDao.php';
require_once __DIR__ . '/../modelo/TipoVehiculoDao.php';
require_once __DIR__ . '/../modelo/EspacioDao.php';

class VehiculoController{
    private $dao;
    private $tipoDao;
    private $espacioDao;
    private $carpetaUploads;

    public function __construct(){
        $this->dao = new VehiculoDao();
        $this->tipoDao = new TipoVehiculoDao();
        $this->espacioDao = new EspacioDao();
        $this->carpetaUploads = __DIR__ . '/../public/uploads/';
    }

    // Mostrar el listado de vehículos (con búsqueda opcional)
    public function index(){
        $busqueda = trim($_GET['buscar'] ?? '');

        if ($busqueda !== '') {
            $vehiculos = $this->dao->buscar($busqueda);
        } else {
            $vehiculos = $this->dao->listar();
        }

        require __DIR__ . '/../vista/vehiculos/index.php';
    }

    // Mostrar el formulario vacío para registrar
    public function crear(){
        $vehiculo = new Vehiculo();
        $errores = [];
        $tipos = $this->tipoDao->listar();
        $espacios = $this->espacioDao->listarLibres(0);
        require __DIR__ . '/../vista/vehiculos/formulario.php';
    }

    // Guardar un vehículo nuevo
    public function guardar(){
        $vehiculo = $this->vehiculoDesdePost(null);
        $tipos = $this->tipoDao->listar();
        $espacios = $this->espacioDao->listarLibres(0);
        $errores = $this->validar($vehiculo);

        // La foto es obligatoria al crear
        if (empty($_FILES['imagenfoto']['name'])) {
            $errores[] = "La foto del vehículo es obligatoria.";
        }

        if (empty($errores)) {
            $nombreImagen = $this->subirImagen($_FILES['imagenfoto'], $errores);
            if ($nombreImagen) {
                $vehiculo->setImagenfoto($nombreImagen);
            }
        }

        if (empty($errores)) {
            try {
                $this->dao->insertar($vehiculo);
            } catch (PDOException $e) {
                // Por si otra persona tomó la placa o el espacio justo antes
                $this->borrarImagen($vehiculo->getImagenfoto());
                $errores[] = "No se pudo registrar: la placa o el espacio ya están en uso.";
            }
        }

        if (!empty($errores)) {
            require __DIR__ . '/../vista/vehiculos/formulario.php';
            return;
        }

        header("Location: index.php?modulo=vehiculo&accion=index");
        exit;
    }

    // Mostrar el formulario con los datos para editar
    public function editar()
    {
        $id = (int)($_GET['id'] ?? 0);
        $vehiculo = $this->dao->obtenerPorId($id);

        if (!$vehiculo) {
            header("Location: index.php?modulo=vehiculo&accion=index");
            exit;
        }

        $errores = [];
        $tipos = $this->tipoDao->listar();
        $espacios = $this->espacioDao->listarLibres($id);
        require __DIR__ . '/../vista/vehiculos/formulario.php';
    }

    // Guardar los cambios de un vehículo existente
    public function actualizar()
    {
        $id = (int)($_POST['id'] ?? 0);
        $actual = $this->dao->obtenerPorId($id);

        if (!$actual) {
            header("Location: index.php?modulo=vehiculo&accion=index");
            exit;
        }

        // Por defecto conserva la foto actual
        $vehiculo = $this->vehiculoDesdePost($id, $actual->getImagenfoto());
        $vehiculo->setFechaIngreso($actual->getFechaIngreso());
        $tipos = $this->tipoDao->listar();
        $espacios = $this->espacioDao->listarLibres($id);
        $errores = $this->validar($vehiculo);

        // Si se subió una nueva foto, reemplaza la anterior
        $fotoAnterior = null;
        $fotoNueva = null;
        if (empty($errores) && !empty($_FILES['imagenfoto']['name'])) {
            $nombreImagen = $this->subirImagen($_FILES['imagenfoto'], $errores);
            if ($nombreImagen) {
                $fotoAnterior = $actual->getImagenfoto();
                $fotoNueva = $nombreImagen;
                $vehiculo->setImagenfoto($nombreImagen);
            }
        }

        if (empty($errores)) {
            try {
                $this->dao->actualizar($vehiculo);
            } catch (PDOException $e) {
                $this->borrarImagen($fotoNueva);
                $errores[] = "No se pudo guardar: la placa o el espacio ya están en uso.";
            }
        }

        if (!empty($errores)) {
            require __DIR__ . '/../vista/vehiculos/formulario.php';
            return;
        }

        // Solo se borra la foto vieja cuando ya se guardó el cambio en la base
        $this->borrarImagen($fotoAnterior);

        header("Location: index.php?modulo=vehiculo&accion=index");
        exit;
    }

    // Eliminar un vehículo (salida del parqueo) y su foto
    // Se exige método POST para que no se pueda borrar con solo abrir un enlace
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $vehiculo = $this->dao->obtenerPorId($id);

            if ($vehiculo) {
                $this->dao->eliminar($id);
                $this->borrarImagen($vehiculo->getImagenfoto());
            }
        }

        header("Location: index.php?modulo=vehiculo&accion=index");
        exit;
    }

    // Construye un Vehiculo con los datos del formulario
    private function vehiculoDesdePost($id, $imagen = "")
    {
        return new Vehiculo(
            $id,
            strtoupper(trim($_POST['placa'] ?? '')),
            trim($_POST['propietario'] ?? ''),
            $_POST['tipo_id'] ?? null,
            $_POST['espacio_id'] ?? null,
            $imagen,
            null,
            '',
            null,
            trim($_POST['color'] ?? ''),
            trim($_POST['modelo'] ?? '')
        );
    }

    // Validar los campos de texto y las relaciones
    private function validar(Vehiculo $vehiculo)
    {
        $errores = [];
        $id = $vehiculo->getId() ?? 0;

        // Placa
        if ($vehiculo->getPlaca() === '') {
            $errores[] = "La placa es obligatoria.";
        } elseif (!preg_match('/^[A-Z0-9-]{4,10}$/', $vehiculo->getPlaca())) {
            $errores[] = "La placa debe tener entre 4 y 10 caracteres (solo letras, números o guion).";
        } elseif ($this->dao->existePlaca($vehiculo->getPlaca(), $id)) {
            $errores[] = "Ya existe un vehículo registrado con esa placa.";
        }

        // Propietario
        if ($vehiculo->getPropietario() === '') {
            $errores[] = "El propietario es obligatorio.";
        } elseif (mb_strlen($vehiculo->getPropietario()) > 100) {
            $errores[] = "El propietario no puede superar los 100 caracteres.";
        }

        if ($vehiculo->getColor() === '') {
            $errores[] = "El color es obligatorio.";
        } elseif (mb_strlen($vehiculo->getColor()) > 40) {
            $errores[] = "El color no puede superar los 40 caracteres.";
        }

        if ($vehiculo->getModelo() === '') {
            $errores[] = "El modelo es obligatorio.";
        } elseif (mb_strlen($vehiculo->getModelo()) > 60) {
            $errores[] = "El modelo no puede superar los 60 caracteres.";
        }

        // Tipo
        if (!$vehiculo->getTipoId() || !$this->tipoDao->obtenerPorId($vehiculo->getTipoId())) {
            $errores[] = "Selecciona un tipo de vehículo válido.";
        }

        // Espacio
        if (!$vehiculo->getEspacioId() || !$this->espacioDao->obtenerPorId($vehiculo->getEspacioId())) {
            $errores[] = "Selecciona un espacio válido.";
        } elseif ($this->dao->existeEspacio($vehiculo->getEspacioId(), $id)) {
            $errores[] = "Ese espacio ya está ocupado por otro vehículo.";
        }

        return $errores;
    }

    // Subir la imagen y devolver el nombre guardado (o null si hay error)
    private function subirImagen($archivo, &$errores)
    {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir la imagen.";
            return null;
        }

        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            $errores[] = "Formato de imagen no permitido (usa jpg, jpeg, png, gif o webp).";
            return null;
        }

        if ($archivo['size'] > 2 * 1024 * 1024) {
            $errores[] = "La imagen no puede superar los 2 MB.";
            return null;
        }

        // Comprueba que el contenido realmente sea una imagen
        if (@getimagesize($archivo['tmp_name']) === false) {
            $errores[] = "El archivo seleccionado no es una imagen válida.";
            return null;
        }

        if (!is_dir($this->carpetaUploads)) {
            mkdir($this->carpetaUploads, 0777, true);
        }

        // Nombre único para evitar que se sobrescriban imágenes
        $nombreFinal = uniqid('vehiculo_', true) . '.' . $extension;

        if (!move_uploaded_file($archivo['tmp_name'], $this->carpetaUploads . $nombreFinal)) {
            $errores[] = "No se pudo guardar la imagen en el servidor.";
            return null;
        }

        return $nombreFinal;
    }

    // Borrar una imagen del disco
    private function borrarImagen($nombreImagen)
    {
        if ($nombreImagen) {
            $ruta = $this->carpetaUploads . basename($nombreImagen);
            if (is_file($ruta)) {
                unlink($ruta);
            }
        }
    }
}
