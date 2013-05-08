<?php

/**
 * Clase para manejar los controladores en la aplicacion
 * @author Wilman Vega <wilmanvega@gmail.com>
 *
 */
class UsuarioControl extends Controlador {

    public function __construct($modelo, $accion) {
        parent::__construct($modelo, $accion);
        $this->setModelo($modelo);
    }

    public function index() {
        try {
            $datos = $this->modelo->leerUsuarios();
            $this->vista->set('usuarios', $datos);
            $this->vista->set('titulo', 'Lista de usuarios');
            return $this->vista->imprimir();
        } catch (Exception $exc) {
            echo 'Error de aplicacion: ' . $exc->getMessage();
        }
    }

    public function detalle($documento) {
        try {
            $usuario = $this->modelo->leerUsuarioPorDocumento($documento);
            if ($usuario != null) {
                $this->vista->set('titulo', 'Datos de ' . $usuario->getNombre());
                $this->vista->set('usuario', $usuario);
            } else {
                //TODO: Esto se puede mejorar redireccionando a una pagina de error
                $this->vista->set('titulo', 'Usuario no existe');
                $this->vista->set('usuario', $usuario);
            }
            return $this->vista->imprimir();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function agregar() {
        $this->vista->set('titulo', 'Agregar Usuario');
        $ciudad = new Ciudad();
        $this->vista->set('deptos',  $ciudad->leerDepartamentos());
        return $this->vista->imprimir();
    }

    public function guardar() {
        if (isset($_POST['agregarusuario'])) {

            $documento = isset($_POST['documento']) ? $_POST['documento'] : NULL;
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : NULL;
            $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : NULL;
            $fechanacimiento = isset($_POST['fechanacimiento']) ? $_POST['fechanacimiento'] : NULL;
            //TODO: Si se hace validacion de datos mandaria mensaje de datos no validos 
            try {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellido($apellido);
                $usuario->setFechaNacimiento($usuario->crearFecha($fechanacimiento));
                $usuario->setDocumento($documento);
                $usuario->crearUsuario($usuario);
                $this->vista->set('titulo', 'Datos almacenados');
                $this->vista->set('mensaje', 'Se ha guardado la informacion de manera satisfactoria');
            } catch (Exception $ex) {
                $this->vista->set('titulo', 'Error');
                $this->vista->set('mensaje', 'Error al guardar los datos: ' . $ex->getMessage());
            } catch (ErrorException $ex) {
                $this->vista->set('titulo', 'Error');
                $this->vista->set('mensaje', 'Error al guardar los datos: ' . $ex->getMessage());
                $this->setVista('agregar');
            }
            return $this->vista->imprimir();
        }
    }

    public function listarCiudad($departamento=''){
        $ciudad = new Ciudad();
        $ciudades = $ciudad->leerCiudades($departamento);
        $this->vista->set('ciudades',$ciudades);
        return $this->vista->imprimir();
    }
    
    
}

?>
