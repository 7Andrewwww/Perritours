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
    
    public function actualizarInformacion() {
        $conexion = new Conexion();
        $conexion->abrir();
        
        // Verificar si el correo ya existe en otro dueño
        $this->dueñoDAO = new DueñoDAO($this->id, "", $this->correo);
        $conexion->ejecutar($this->dueñoDAO->verificarCorreoExistente());
        if($conexion->filas() > 0) {
            $conexion->cerrar();
            throw new Exception("El correo electrónico ya está registrado");
        }
        
        // Actualizar información
        $this->dueñoDAO = new DueñoDAO(
            $this->id,
            $this->nombre,
            $this->correo,
            "",
            $this->telefono
            );
        
        $conexion->ejecutar($this->dueñoDAO->actualizar());
        $filasAfectadas = $conexion->filasAfectadas();
        $conexion->cerrar();
        
        return $filasAfectadas > 0;
    }
    
    public function cambiarClave($nuevaClave) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $this->dueñoDAO = new DueñoDAO($this->id, "", "", $nuevaClave);
        $conexion->ejecutar($this->dueñoDAO->actualizarClave());
        $filasAfectadas = $conexion->filasAfectadas();
        $conexion->cerrar();
        
        return $filasAfectadas > 0;
    }
    
    public function verificarClave($claveActual) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $this->dueñoDAO = new DueñoDAO($this->id, "", "", $claveActual);
        $conexion->ejecutar($this->dueñoDAO->verificarClaveActual());
        $resultado = $conexion->filas() > 0;
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public static function obtenerPromedioSatisfaccion() {
        $conexion = new Conexion();
        $dao = new DueñoDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->obtenerPromedioSatisfaccion());
        
        $dato = $conexion->registro();
        $conexion->cerrar();
        
        return $dato ? (float)($dato->promedio ?? $dato[0]) : 0;
    }
    
    

}