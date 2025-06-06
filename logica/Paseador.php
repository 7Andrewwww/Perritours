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

        $resultado = $conexion->ejecutar($paseadorDAO->crear());
        $conexion->cerrar();
        return $resultado;
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
        $conexion->cerrar();
        return $resultado;
    }
    
    public function actualizarClave($nuevaClave) {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id, "", "", $nuevaClave);
        
        $conexion->abrir();
        $resultado = $conexion->ejecutar($paseadorDAO->actualizarClave());
        $conexion->cerrar();
        return $resultado;
    }

    public function eliminar() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        
        $conexion->abrir();
        
        $conexion->ejecutar("SELECT foto_url FROM paseador WHERE id_pas = '" . $this->id . "'");
        $foto_url = $conexion->registro()[0];
        
        $resultado = $conexion->ejecutar($paseadorDAO->eliminar());
        
        if($resultado && $foto_url != 'img/default-profile.png' && file_exists($foto_url)) {
            unlink($foto_url); 
        }
        
        $conexion->cerrar();
        return $resultado;
    }
}
?>