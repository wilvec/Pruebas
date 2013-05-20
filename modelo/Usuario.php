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
    private $clave;
    private $correo;
    private $foto;

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
        if (array_key_exists('idciudad', $props)) {
            $user->setIdCiudadNacimiento($props['idciudad']);
        }
        if (array_key_exists('ciudad', $props)) {
            $user->setCiudadnacimiento($props['ciudad']);
        }
        if (array_key_exists('clave', $props)) {
            $user->setClave($props['clave']);
        }
        if (array_key_exists('email', $props)) {
            $user->setCorreo($props['email']);
        }
        if (array_key_exists('foto', $props)) {
            $user->setFoto($props['foto']);
        }
    }

    private function getParametros(Usuario $usuario) {
        $parametros = array(
            ':documento' => $usuario->getDocumento(),
            ':nombre' => $usuario->getNombre(),
            ':apellido' => $usuario->getApellido(),
            ':fechanacimiento' => $this->formatearFecha($usuario->getFechaNacimiento()),
            ':idciudad' => $usuario->getIdCiudadNacimiento(),
            ':clave' => $usuario->getClave(),
            ':email' => $usuario->getCorreo(),
            ':foto' => $usuario->getFoto()
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

    public function getClave() {
        return $this->clave;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    //Funciones CRUD

    public function crearUsuario(Usuario $user) {
        $sql = "INSERT INTO usuario (documento, nombre, apellido, fechanacimiento, idciudad, clave, email, foto) ";
        $sql .= " VALUES (:documento, :nombre, :apellido, :fechanacimiento, :idciudad, SHA(:clave), :email, :foto)";
        $this->__setSql($sql);
        $this->prepararSentencia($sql);
        $this->sentencia->bindParam(":documento", $user->getDocumento(),PDO::PARAM_STR);
        $this->sentencia->bindParam(":nombre", $user->getNombre(),PDO::PARAM_STR);
        $this->sentencia->bindParam(":apellido", $user->getApellido(),PDO::PARAM_STR);
        $this->sentencia->bindParam(":fechanacimiento", $this->formatearFecha($user->getFechaNacimiento()),PDO::PARAM_STR);
        $this->sentencia->bindParam(":idciudad", $user->getIdCiudadNacimiento(),PDO::PARAM_INT);
        $this->sentencia->bindParam(":clave", $user->getClave(),PDO::PARAM_STR);
        $this->sentencia->bindParam(":email", $user->getCorreo(),PDO::PARAM_STR);
        $this->sentencia->bindParam(":foto", $user->getFoto(),PDO::PARAM_LOB);
        $this->ejecutarSentencia();
    }

    public function leerUsuarios() {
        $sql = " SELECT u.documento, u.nombre, u.apellido, u.fechanacimiento, u.foto,";
        $sql .= "u.idciudad, u.clave, u.email, c.nombre as ciudad FROM usuario u, ciudad c ";
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
        $sql = " SELECT u.documento, u.nombre, u.apellido, u.fechanacimiento, u.foto,";
        $sql .= "u.idciudad, u.clave, u.email, c.nombre as ciudad FROM usuario u, ciudad c ";
        $sql .= " WHERE u.idciudad = c.idciudad AND u.documento = :documento";        
        $this->prepararSentencia($sql);
        $this->sentencia->execute(array(':documento' => $documento));
        
        foreach ($usuarios as $usuario) {
            if ($usuario->getDocumento() == $documento)
                return $usuario;
        }
        return null;
    }

    public function leerUsuarioPorClave($usuario, $clave) {
        //TODO: Hacer las funciones de encriptacion en php 
        //$clave = encriptar_sha($clave)

        $sql = "SELECT * FROM usuario WHERE documento=? AND clave=SHA(?)";
        $param = array($usuario, $clave);
        $this->__setSql($sql);
        $res = $this->consultar($sql, $param);
        $usuario = NULL;
        foreach ($res as $fila) {
            $usuario = new Usuario();
            $this->mapearUsuario($usuario, $fila);
        }
        return $usuario;
    }

    public function leerUsuarioPorMail($documento, $email) {
        //TODO: Hacer las funciones de encriptacion en php 
        //$clave = encriptar_sha($clave)
        $sql = "SELECT * FROM usuario WHERE documento=? AND email=?";
        $param = array($documento, $email);
        $this->__setSql($sql);
        $res = $this->consultar($sql, $param);
        $usuario = NULL;
        foreach ($res as $fila) {
            $usuario = new Usuario();
            $this->mapearUsuario($usuario, $fila);
        }
        return $usuario;
    }

    public function actualizarUsuario(Usuario $user, $cambiaclave = FALSE) {
        $sql = " UPDATE usuario SET nombre=:nombre, fechanacimiento=:fechanacimiento, foto=:foto, ";
        if (!$cambiaclave) {
            $sql .= "idciudad = :idciudad, apellido=:apellido, clave = :clave, email=:email WHERE documento=:documento ";
        } else {
            $sql .= "idciudad = :idciudad, apellido=:apellido, clave = sha(:clave), email=:email WHERE documento=:documento ";
        }
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