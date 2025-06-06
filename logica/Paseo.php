<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");
require_once("logica/Paseador.php");

class Paseo {
    private $idPaseo;
    private $tarifa;
    private $fecha;
    private $hora;
    private $paseador;
    
    public function __construct($idPaseo = "", $tarifa = 0, $fecha = "", $hora = "", $paseador = "") {
        $this->idPaseo = $idPaseo;
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->paseador = $paseador;
    }
    
    // Getters
    public function getIdPaseo() {
        return $this->idPaseo;
    }
    
    public function getTarifa() {
        return $this->tarifa;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function getHora() {
        return $this->hora;
    }
    
    public function getPaseador() {
        return $this->paseador;
    }
    
    // Setters
    public function setIdPaseo($idPaseo) {
        $this->idPaseo = $idPaseo;
    }
    
    public function setTarifa($tarifa) {
        $this->tarifa = $tarifa;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    
    public function setHora($hora) {
        $this->hora = $hora;
    }
    
    public function setPaseador($paseador) {
        $this->paseador = $paseador;
    }
    
    // Métodos de negocio
    public static function consultarTodos() {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos());
        
        $paseos = array();
        while($datos = $conexion->registro()) {
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                new Paseador($datos[4], $datos[5])
                );
            array_push($paseos, $paseo);
        }
        
        $conexion->cerrar();
        return $paseos;
    }
    
    public function consultar() {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultar());
        
        $datos = $conexion->registro();
        $this->tarifa = $datos[1];
        $this->fecha = $datos[2];
        $this->hora = $datos[3];
        $this->paseador = new Paseador($datos[4], $datos[5]);
        
        $conexion->cerrar();
    }
}
?>