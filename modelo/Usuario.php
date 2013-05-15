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
    private $idCiudadNacimiento; //El campo no se llama igual en la base de datos OJO!
    private $ciudadNacimiento;   //Para el nombre de la ciudad

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
        if(array_key_exists('idciudad', $props)){
            $user->setIdCiudadNacimiento($props['idciudad']);
        }
        
        if(array_key_exists('ciudad', $props)){
            $user->setCiudadnacimiento($props['ciudad']);
        }
    }

    private function getParametros(Usuario $usuario) {
        $parametros = array(
            ':documento' => $usuario->getDocumento(),
            ':nombre' => $usuario->getNombre(),
            ':apellido' => $usuario->getApellido(),
            ':fechanacimiento' => $this->formatearFecha($usuario->getFechaNacimiento()),
            ':idciudad' => $usuario->getCiudadnacimiento()
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
    
    public function getIdCiudadNacimiento() {
        return $this->idCiudadNacimiento;
    }

    public function setIdCiudadNacimiento($idCiudadNacimiento) {
        $this->idCiudadNacimiento = $idCiudadNacimiento;
    }
    
    public function getCiudadNacimiento() {
        return $this->ciudadNacimiento;
    }

    public function setCiudadNacimiento($ciudadNacimiento) {
        $this->ciudadNacimiento = $ciudadNacimiento;
    }

        
    //Funciones CRUD

    public function crearUsuario(Usuario $user) {
        $sql = "INSERT INTO usuario (documento, nombre, apellido, fechanacimiento, idciudad) VALUES (?, ?, ?, ?, ?)";
        $this->__setSql($sql);
        $this->ejecutar($this->getParametros($user));
    }

    public function leerUsuarios() {
        $sql =  " SELECT u.documento, u.nombre, u.apellido, u.fechanacimiento, u.idciudad, c.nombre as ciudad FROM usuario u, ciudad c ";
        $sql .= " WHERE u.idciudad = c.idciudad";
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
        $usuarios = $this->leerUsuarios();
        foreach ($usuarios as $usuario) {
            if ($usuario->getDocumento() == $documento)
                return $usuario;
        }
        return null;
    }
    
    public function leerUsuarioPorClave($usuario, $clave){
        //TODO: Hacer las funciones de encriptacion en php 
        //$clave = encriptar_sha($clave)
        
        $sql = "SELECT * FROM usuario WHERE documento=? AND clave=SHA(?)";
        $param = array($usuario,$clave);
        $this->__setSql($sql);
        $res = $this->consultar($sql, $param);
        $usuario = NULL;
        foreach ($res as $fila) {
            $usuario = new Usuario();
            $this->mapearUsuario($usuario, $fila);
        }
        return $usuario;
    }

    public function actualizarUsuario(Usuario $user) {
        $sql = "UPDATE usuario SET nombre=?, apellido=?, fechanacimiento=?, idciudad = ? WHERE documento=?";
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