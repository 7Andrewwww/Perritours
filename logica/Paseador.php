<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseadorDAO.php");
require_once("logica/Persona.php");

class Paseador extends Persona {
    private $foto_url;
    private $id_estado;
    private $paseadorDAO;
    
    public function __construct($id = "", $nombre = "", $correo = "", $clave = "", $telefono = 0, $foto_url = "", $id_estado = "") {
        parent::__construct($id, $nombre, $correo, $clave, $telefono);
        $this->foto_url = $foto_url;
        $this->id_estado = $id_estado;
        $this->paseadorDAO = new PaseadorDAO($id, $nombre, $correo, $clave, $telefono, $foto_url, $id_estado);
    }
    
    public function autenticar() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO("", "", $this->correo, $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->autenticar());
        if($conexion->filas() == 1) {
            $this->id = $conexion->registro()[0];
            $conexion->cerrar();
            return true;
        } else {
            $conexion->cerrar();
            return false;
        }
    }
    
    public function consultar() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultar());
        $datos = $conexion->registro();
        $this->nombre = $datos[0];
        $this->correo = $datos[1];
        $this->telefono = $datos[2];
        $this->foto_url = $datos[3];
        $this->id_estado = $datos[4];
        $conexion->cerrar();
    }
    
    // Getters y setters específicos
    public function getFotoUrl() {
        return $this->foto_url;
    }
    
    public function getIdEstado() {
        return $this->id_estado;
    }
    
    public function setFotoUrl($foto_url) {
        $this->foto_url = $foto_url;
    }
    
    public function setIdEstado($id_estado) {
        $this->id_estado = $id_estado;
    }
}