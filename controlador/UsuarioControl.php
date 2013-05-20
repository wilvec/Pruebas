<?php

/**
 * Clase para manejar los controladores en la aplicacion
 * @author Wilman Vega <wilmanvega@gmail.com>
 */
class UsuarioControl extends Controlador {

    public function __construct($modelo, $accion) {
        parent::__construct($modelo, $accion);
        $this->setModelo($modelo);
    }

    public function index() {
        try {
            session_start();
            if (!isset($_SESSION['usuario.id'])) {
                $this->setVista('fuera');
                return $this->vista->imprimir();
            }
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
            session_start();
            if (!isset($_SESSION['usuario.id'])) {
                $this->setVista('fuera');
                return $this->vista->imprimir();
            }
            $usuario = $this->modelo->leerUsuarioPorDocumento($documento);
            if ($usuario != null) {
                $this->vista->set('titulo', 'Datos de ' . $usuario->getNombre());
                $this->vista->set('usuario', $usuario);
                $filename = './estatico/' . $usuario->getDocumento() . '.jpg';
                file_put_contents($filename, $usuario->getFoto());
                $this->vista->set('foto', $usuario->getDocumento() . '.jpg');
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
        $this->vista->set('deptos', $ciudad->leerDepartamentos());
        return $this->vista->imprimir();
    }

    public function guardar() {
        if (isset($_POST['agregarusuario'])) {
            $documento = isset($_POST['documento']) ? $_POST['documento'] : NULL;
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : NULL;
            $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : NULL;
            $fechanacimiento = isset($_POST['fechanacimiento']) ? $_POST['fechanacimiento'] : NULL;
            $correo = isset($_POST['email']) ? $_POST['email'] : NULL;
            $clave = isset($_POST['clave']) ? $_POST['clave'] : NULL;
            //TODO: Si se hace validacion de datos mandaria mensaje de datos no validos 
            try {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellido($apellido);
                $usuario->setFechaNacimiento($usuario->crearFecha($fechanacimiento));
                $usuario->setDocumento($documento);
                $usuario->setClave($clave);
                //foto
                $filename = $_FILES['foto']['tmp_name'];
                $foto = fopen($filename, 'rb');
                $usuario->setFoto($foto);
                $usuario->setCorreo($correo);
                $usuario->setIdCiudadNacimiento('20001');
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

    public function login() {
        $this->vista->set('titulo', 'Acceder a la aplicaci&oacute;n');
        return $this->vista->imprimir();
    }

    public function fuera() {
        $this->vista->set('titulo', 'SALIENDO DE LA APLICACION');
        return $this->vista->imprimir();
    }

    public function entrar() {
        if (isset($_POST['enviar'])) {
            $documento = isset($_POST['documento']) ? $_POST['documento'] : NULL;
            $clave = isset($_POST['clave']) ? $_POST['clave'] : NULL;
            $usuario = $this->modelo->leerUsuarioPorClave($documento, $clave);
            if ($usuario == NULL) {
                $this->vista->set('mensaje', 'No esta registrado');
                return $this->vista->imprimir();
            }
            $this->vista->set('mensaje', 'Entrar a la aplicacion');
            //MANEJO DE SESIONES
            session_start();
            $_SESSION['usuario.id'] = $usuario->getDocumento();
            return $this->vista->imprimir();
        }
    }

    public function listarCiudad($departamento = '') {
        $ciudad = new Ciudad();
        $ciudades = $ciudad->leerCiudades($departamento);
        $this->vista->set('ciudades', $ciudades);
        return $this->vista->imprimir();
    }

    public function olvidoclave() {
        $this->vista->set('titulo', 'Recuperar contrase&ntilde;a');
        return $this->vista->imprimir();
    }

    public function enviardatosolvido() {
        if (isset($_POST['botonenviar'])) {
            $documento = isset($_POST['documento']) ? $_POST['documento'] : NULL;
            $email = isset($_POST['email']) ? $_POST['email'] : NULL;
            $usuario = $this->modelo->leerUsuarioPorMail($documento, $email);
            if ($usuario == NULL) {
                $this->vista->set('mensaje', 'No esta registrado');
                return $this->vista->imprimir();
            }

            $msg1 = "Para cambiar su clave, haga clic en el siguiente enlace:<br>";
            //TODO: Mejor URL Para recuperar clave, por ejemplo, 
            //md5 o sha combinando usuario+mail+salt, etc.
            $msg1 .= "http://localhost/Pruebas/usuario/cambiarclave/" . $usuario->getDocumento();
            $msg1 .= "<br>El administrador";

            //TODO: se puede encapsular el envio de correos en una clase, para 
            //personalizar mas facil los datos configuracion y las opciones de envio.
            $mailer = new PHPMailer();
            $mailer->SetFrom("cursoss400@gmail.com", "PROGRAMACION BAJO WEB SS400");
            $direccion = $usuario->getCorreo();
            $nombre = $usuario->getNombre() . " " . $usuario->getApellido();
            $mailer->AddAddress($direccion, $nombre);
            $mailer->CharSet = "UTF-8";
            $mailer->SMTPDebug = true;
            $mailer->Subject = "Cambio de contraseña en la aplicación Pruebas";
            $mailer->MsgHTML($msg1);
            $mailer->IsSMTP();
            $mailer->Host = "smtp.gmail.com";
            $mailer->Port = 587;
            $mailer->SMTPAuth = true;
            $mailer->SMTPSecure = "tls";
            $mailer->Username = "cursoss400@gmail.com";
            $mailer->Password = "curso-400";
            if (!$mailer->Send()) {
                $this->vista->set("mensaje", "Error al enviar correo! (" . $mailer->ErrorInfo . ")");
                return $this->vista->imprimir();
            } else {
                $this->vista->set('mensaje', 'Se ha enviado la informaci&oacute;n de acceso a su correo.');
                return $this->vista->imprimir();
            }
        }
    }

    public function cambiarclave($documento) {
        $this->vista->set('titulo', 'Cambiar contrase&ntilde;a');
        $this->vista->set('documento', $documento);
        $user1 = new Usuario();
        $user1 = $user1->leerUsuarioPorDocumento($documento);
        $this->vista->set('nombreusuario', $user1->getNombre() . " " . $user1->getApellido());
        return $this->vista->imprimir();
    }

    public function guardarclave() {
        if (isset($_POST['botonenviar'])) {
            try {
                $documento = $_POST['documento'];
                $nuevaclave = $_POST['clave'];
                $user1 = new Usuario();
                $user1 = $user1->leerUsuarioPorDocumento($documento);
                $user1->setClave($nuevaclave);
                $user1->actualizarUsuario($user1, TRUE);
                $this->vista->set('mensaje', 'Se ha actualizado la clave correctamente.');
                return $this->vista->imprimir();
            } catch (Exception $ex) {
                error_log($ex->getMessage());
                $this->vista->set('mensaje', 'Error al guardar la clave!: ' . $ex->getMessage());
                return $this->vista->imprimir();
            }
        }
    }

}

?>
