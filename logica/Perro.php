<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PerroDAO.php");
require_once("logica/Dueño.php");

class Perro {
    private $idPerro;
    private $nombre;
    private $raza;
    private $foto_url;
    private $dueño;
    
    public function __construct($idPerro = "", $nombre = "", $raza = "", $foto_url = "", $dueño = "") {
        $this->idPerro = $idPerro;
        $this->nombre = $nombre;
        $this->raza = $raza;
        $this->foto_url = $foto_url;
        $this->dueño = $dueño;
    }
    
    public function getIdPerro() {
        return $this->idPerro;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getRaza() {
        return $this->raza;
    }
    
    public function getFotoUrl() {
        return $this->foto_url;
    }
    
    public function getDueño() {
        return $this->dueño;
    }
    
    public static function consultarTodos() {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->consultarTodos());
        
        $perros = array();
        while($datos = $conexion->registro()) {
            $perro = new Perro(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                new Dueño(0, $datos[4])
                );
            array_push($perros, $perro);
        }
        
        $conexion->cerrar();
        return $perros;
    }
}
?>