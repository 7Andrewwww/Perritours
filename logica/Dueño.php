<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/DueñoDAO.php");
require_once("logica/Persona.php");

class Dueño extends Persona {
    private $dueñoDAO;
    
    public function __construct($id = "", $nombre = "", $correo = "", $clave = "", $telefono = 0) {
        parent::__construct($id, $nombre, $correo, $clave, $telefono);
        $this->dueñoDAO = new DueñoDAO($id, $nombre, $correo, $clave, $telefono);
    }
    
    public function autenticar() {
        $conexion = new Conexion();
        $dueñoDAO = new DueñoDAO("", "", $this->correo, $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($dueñoDAO->autenticar());
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
        $dueñoDAO = new DueñoDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($dueñoDAO->consultar());
        $datos = $conexion->registro();
        $this->nombre = $datos[0];
        $this->correo = $datos[1];
        $this->telefono = $datos[2];
        $conexion->cerrar();
    }
    
    public static function consultarTodos() {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $dao = new DueñoDAO();
        $conexion->ejecutar($dao->consultarTodos());
        
        $dueños = [];
        while (($registro = $conexion->registro())) {
            $dueños[] = new Dueño($registro[0], $registro[1], $registro[2], "", $registro[3]);
        }
        
        $conexion->cerrar();
        return $dueños;
    }
    
    

}