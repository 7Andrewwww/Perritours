<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseadorDAO.php");
require_once("logica/Persona.php");
require_once("logica/Estado.php");

class Paseador extends Persona {
    private $foto_url;
    private $estado;
    
    public function __construct($id = "", $nombre = "", $correo = "", $clave = "", $telefono = 0, $foto_url = "", $estado = "") {
        parent::__construct($id, $nombre, $correo, $clave, $telefono);
        $this->foto_url = $foto_url;
        $this->estado = $estado;
    }
    
    public function getFotoUrl() {
        return $this->foto_url;
    }
    
    public function setFotoUrl($foto_url) {
        $this->foto_url = $foto_url;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
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
        $this->estado = new Estado($datos[4], $datos[5]);
        $conexion->cerrar();
    }
    
    public static function consultarTodos() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarTodos());
        $paseadores = array();
        while($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], "", $datos[3], $datos[4], new Estado($datos[5], $datos[6]));
            array_push($paseadores, $paseador);
        }
        $conexion->cerrar();
        return $paseadores;
    }

    public function crear() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO(
            $this->id,
            $this->nombre,
            $this->correo,
            $this->clave,
            $this->telefono,
            $this->foto_url,
            $this->estado->getIdEstado()
            );
        
        $conexion->abrir();
        
        try{
            $conexion->ejecutar("SELECT id_pas FROM paseador WHERE id_pas = '" . $this->id . "'");
            if($conexion->filas() > 0) {
                $conexion->cerrar();
                throw new Exception("El ID del paseador ya existe");
            }
            
            $conexion->ejecutar("SELECT id_pas FROM paseador WHERE correo = '" . $this->correo . "'");
            if($conexion->filas() > 0) {
                $conexion->cerrar();
                throw new Exception("El correo electrónico ya está registrado");
            }
            $conexion->ejecutar($paseadorDAO->crear());
            $resultado = true;
            
        }catch(Exception){
            $resultado = false;
        }finally{
            return $resultado;
        }
    }

    public function actualizar() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO(
            $this->id,
            $this->nombre,
            $this->correo,
            "",
            $this->telefono,
            $this->foto_url,
            $this->estado->getIdEstado()
            );
        
        $conexion->abrir();

        $conexion->ejecutar("SELECT id_pas FROM paseador WHERE correo = '" . $this->correo . "' AND id_pas != '" . $this->id . "'");
        if($conexion->filas() > 0) {
            $conexion->cerrar();
            throw new Exception("El correo electrónico ya está registrado en otro paseador");
        }
        
        $resultado = $conexion->ejecutar($paseadorDAO->actualizar());
        $filasAfectadas = $conexion->filasAfectadas(); 
        $conexion->cerrar();
        
        return $filasAfectadas > 0; 
    }
    
    public function actualizarClave($nuevaClave) {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id, "", "", $nuevaClave);
        
        $conexion->abrir();
        $resultado = $conexion->ejecutar($paseadorDAO->actualizarClave());
        $conexion->cerrar();
        return $resultado;
    }
    
    public function verificarClave($claveIngresada) {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO("", "", $this->correo, $claveIngresada);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->autenticar());
        $resultado = ($conexion->filas() == 1);
        $conexion->cerrar();
        return $resultado;
    }
    
    public static function siguienteId() {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $paseadorDAO = new PaseadorDAO();
        $conexion->ejecutar($paseadorDAO->siguienteId());
        
        $nextId = $conexion->registro()[0] ?? 1;
        $conexion->cerrar();
        
        return $nextId;
    }
    
    public static function consultarPaseadoresActivos() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarPaseadoresActivos());
        
        $paseadores = array();
        while($datos = $conexion->registro()) {
            $paseador = new Paseador(
                $datos[0],  // id_pas
                $datos[1],  // nombre
                $datos[2],  // correo
                "",         // clave (no se necesita)
                $datos[3],  // telefono
                $datos[4],  // foto_url
                new Estado($datos[5], $datos[6]) // id_estado, estado
                );
            array_push($paseadores, $paseador);
        }
        
        $conexion->cerrar();
        return $paseadores;
    }
    
    public static function consultarPaseadoresConExperiencia($idDueño) {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarPaseadoresConExperiencia($idDueño));
        
        $paseadores = array();
        while($datos = $conexion->registro()) {
            $paseador = new Paseador(
                $datos[0],  // id_pas
                $datos[1],  // nombre
                $datos[2],  // correo
                "",         // clave (no se necesita)
                $datos[3],  // telefono
                $datos[4],  // foto_url
                new Estado($datos[5], $datos[6]) // id_estado, estado
                );
            array_push($paseadores, $paseador);
        }
        
        $conexion->cerrar();
        return $paseadores;
    }
}
?>