<?php

/**
 * Clase de la cual se basan las clases de modelo.
 * 
 * @author Wilman Vega <wilmanvega@gmail.com>
 */
abstract class Modelo {

    /**
     * Objeto de tipo Db para Acceso a datos. 
     * @var Db
     */
    protected $db;

    /**
     * Objeto para la sentencia de PDO
     * @var PDOStatement
     */
    protected $sentencia;

    /**
     * Objeto de tipo string para manejo de las consultas en el modelo.
     * @var string
     */
    protected $sql;

    /**
     * Envia un error por pantalla.
     * @todo Actualizar para enviar por correo, consola, Manejo de Log.
     * @param array $infoE Arreglo que tiene la información del error que se generó
     * @throws Exception
     */
    private static function enviarError(array $infoE) {
        throw new Exception('Error de B.D: ' . $infoE[0]);
    }

    public static function crearFecha($entrada) {
        return DateTime::createFromFormat('Y-m-d', $entrada);
    }

    /**
     * Inicializa la variable de acceso a Db
     */
    public function __construct() {
        $this->db = Db::init();
    }

    /**
     * Cierra la comunicación con la base de datos.
     */
    public function __destruct() {
        $this->db = null;
    }

    /**
     * Establece la cadena de consulta actual para el modelo.
     * @param string $sql
     */
    protected function __setSql($sql) {
        $this->sql = $sql;
    }

    /**
     * Retorna el objeto sentencia del modelo actual
     * @return PDOStatement
     */
    protected function prepararSentencia($sql) {
        $this->sentencia = $this->db->prepare($sql);
        return $this->sentencia;
    }

    /**
     * Función para ejecutar consultar de tipo SELECT.
     * Retorna un objeto de tipo PDOStatement, que tiene metainformación
     * sobre la consulta realizada.
     * @param string $sql
     * @return PDOStatement
     */
    protected function consultar($sql = null, $param = null) {
        if ($sql == null)
            $sql = $this->sql;
        $this->sentencia = $this->db->prepare($sql);
        if ($param != null)
            $this->sentencia->execute($param);
        $this->sentencia->execute();
        $resultado = $this->sentencia->fetchAll();
        return $resultado;
    }

    /**
     * Función para ejecutar consultar de tipo SELECT.
     * Retorna un objeto de tipo PDOStatement, que tiene metainformación
     * 
     * @return PDOStatement
     */
    protected function consultarSentencia() {
        $this->sentencia->execute();
        $resultado = $this->sentencia->fetchAll();
        return $resultado;
    }

    /**
     * Función para ejecutar consultar de tipo INSERT, UPDATE, DELETE
     * @param array $parametros
     * @param string $sql
     */
    protected function ejecutar($parametros = null, $sql = null) {
        if ($sql == null)
            $sql = $this->sql;
        $this->sentencia = $this->db->prepare($sql);
        if (!$this->sentencia->execute($parametros)) {
            self::enviarError($this->db->errorInfo());
        }
    }

    /**
     * Función para ejecutar consultar de tipo INSERT, UPDATE, DELETE
     */
    protected function ejecutarSentencia() {
        if (!$this->sentencia->execute()) {
            self::enviarError($this->db->errorInfo());
        }
    }

    protected static function formatearFecha(DateTime $fecha, $formato = 'Y-m-d') {
        return empty($formato) ? $fecha->format(DateTime::ISO8601) : $fecha->format($formato);
    }

}

?>
