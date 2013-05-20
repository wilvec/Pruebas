<?php

class Ciudad extends Modelo {
    private $idCiudad;
    private $idDepto;
    private $ciudad;
    private $depto;

    //Getter y Setter
    public function __construct() {
        parent::__construct();
    }
    
    public function getIdCiudad() {
        return $this->idCiudad;
    }

    public function setIdCiudad($idCiudad) {
        $this->idCiudad = $idCiudad;
    }

    public function getIdDepto() {
        return $this->idDepto;
    }

    public function setIdDepto($idDepto) {
        $this->idDepto = $idDepto;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    public function getDepto() {
        return $this->depto;
    }

    public function setDepto($depto) {
        $this->depto = $depto;
    }
    
    //CRUD
    public function leerCiudades($departamento = ''){
        $sql =  "SELECT c.idciudad, c.iddepto, c.nombre as ciudad, d.nombre as depto FROM ciudad c, departamento d WHERE ";
        $sql .= " c.iddepto = d.iddepto";
        $sql .= empty($departamento) ? $departamento : " AND d.iddepto = ".$departamento;
        
        $this->__setSql($sql);
        $resultado = $this->consultar($sql);
        $ciudades = array();
        foreach ($resultado as $fila){
            $ciudad = new Ciudad();
            $this->mapearCiudad($ciudad, $fila);
            $ciudades[$ciudad->getIdCiudad()] = $ciudad;
        }
        return $ciudades;
    }
    
    
    public function leerDepartamentos(){
        $sql = "SELECT iddepto, nombre as departamento FROM departamento";
        $this->__setSql($sql);
        $res = $this->consultar($sql);
        $deptos = array();
        return $res;
    }


    private function mapearCiudad(Ciudad $ciudad, array $props){
        if(array_key_exists('idciudad', $props)){
            $ciudad->setIdCiudad($props['idciudad']);
        }
        if(array_key_exists('iddepto', $props)){
            $ciudad->setIdDepto($props['iddepto']);
        }
        if(array_key_exists('ciudad', $props)){
            $ciudad->setCiudad($props['ciudad']);
        }
        if(array_key_exists('departamento', $props)){
            $ciudad->setDepto($props['departamento']);
        }
    }
}
?>
