<?php

/**
 * Clase que guarda el usuario
 * @author Wilman Vega <wilmanvega@gmail.com>
 *
 */
class Usuario extends Modelo {

    private $documento;
    private $nombre;
    private $apellido;
    private $fechaNacimiento;

    public function __construct() {
        parent::__construct();
    }

    private function mapearUsuario(Usuario $user, array $props) {
        if (array_key_exists('documento', $props)) {
            $user->setDocumento($props['documento']);
        }
        if (array_key_exists('nombre', $props)) {
            $user->setNombre($props['nombre']);
        }
        if (array_key_exists('apellido', $props)) {
            $user->setApellido($props['apellido']);
        }
        if (array_key_exists('fechanacimiento', $props)) {
            $user->setFechaNacimiento(self::crearFecha($props['fechanacimiento']));
        }
    }

    private function getParametros(Usuario $usuario) {
        $parametros = array(
            ':documento' => $usuario->getDocumento(),
            ':nombre' => $usuario->getNombre(),
            ':apellido' => $usuario->getApellido(),
            ':fechanacimiento' => $this->formatearFecha($usuario->getFechaNacimiento())
        );
        return $parametros;
    }

    //Getter y Setter
    public function getDocumento() {
        return $this->documento;
    }

    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(DateTime $fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    //Funciones CRUD

    public function crearUsuario(Usuario $user) {
        $sql = "INSERT INTO usuario (documento, nombre, apellido, fechanacimiento) VALUES (?, ?, ?, ?)";
        $this->__setSql($sql);
        $this->ejecutar($this->getParametros($user));
    }

    public function leerUsuarios() {
        $sql = "SELECT documento, nombre, apellido, fechanacimiento FROM usuario";
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        $usuarios = array();
        foreach ($resultado as $fila) {
            $user = new Usuario();
            $this->mapearUsuario($user, $fila);
            $usuarios[$user->getDocumento()] = $user;
        }
        return $usuarios;
    }

    public function leerUsuarioPorDocumento($documento) {
        //TODO: Mejorar esta forma!!!
        $usuarios = $this->leerUsuarios();
        foreach ($usuarios as $usuario) {
            if ($usuario->getDocumento() == $documento)
                return $usuario;
        }
        return null;
    }

    public function actualizarUsuario(Usuario $user) {
        $sql = "UPDATE usuario SET nombre=?, apellido=?, fechanacimiento=? WHERE documento=?";
        $this->__setSql($sql);
        $this->ejecutar($this->getParametros($user));
    }

    public function eliminarUsuario(Usuario $user) {
        $sql = "DELETE usuario where documento=?";
        $this->__setSql($sql);
        $param = array(':documento' => $user->getDocumento());
        $this->ejecutar($param);
    }

}

?>