<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/AdminDAO.php");
require_once("logica/Persona.php");

class Admin extends Persona {
    private $adminDAO;
    
    public function __construct($id = "", $nombre = "", $correo = "", $clave = "", $telefono = 0) {
        parent::__construct($id, $nombre, $correo, $clave, $telefono);
        $this->adminDAO = new AdminDAO($id, $nombre, $correo, $clave, $telefono);
    }
    
    public function autenticar() {
        $conexion = new Conexion();
        $adminDAO = new AdminDAO("", "", $this->correo, $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($adminDAO->autenticar());
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
        $adminDAO = new AdminDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($adminDAO->consultar());
        $datos = $conexion->registro();
        $this->nombre = $datos[0];
        $this->correo = $datos[1];
        $this->telefono = $datos[2];
        $conexion->cerrar();
    }
}