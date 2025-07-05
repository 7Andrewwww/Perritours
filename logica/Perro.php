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
    
    public function consultar() {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $perroDAO = new PerroDAO($this->idPerro);
        $conexion->ejecutar($perroDAO->obtenerPorId());
        
        $datos = $conexion->registro();
        if ($datos) {
            $this->nombre = $datos[1];
            $this->raza = $datos[2];
            $this->foto_url = $datos[3];
            $this->dueño = new Dueño($datos[4]);
        }
        
        $conexion->cerrar();
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
    
    public static function consultarPorDueño($id_dueño) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $perroDAO = new PerroDAO();
        $conexion->ejecutar($perroDAO->consultarPorDueño($id_dueño));
        
        $perros = array();
        while ($registro = $conexion->registro()) {
            $perro = new Perro(
                $registro[0], // id_perro
                $registro[1], // nombre
                $registro[2], // raza
                $registro[3], // foto_url
                new Dueño($id_dueño) // objeto Dueño
                );
            $perros[] = $perro;
        }
        
        $conexion->cerrar();
        return $perros;
    }
    
    public static function cantidadPorDueño($id_dueño) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $perroDAO = new PerroDAO();
        $conexion->ejecutar($perroDAO->cantidadPorDueño($id_dueño));
        
        $resultado = $conexion->registro()[0];
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public static function siguienteId() {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $perroDAO = new PerroDAO();
        $conexion->ejecutar($perroDAO->siguienteId());
        
        $nextId = $conexion->registro()[0] ?? 1; 
        $conexion->cerrar();
        
        return $nextId;
    }
    
    public function insertar() {
        $conexion = new Conexion();
        $conexion->abrir();

        $perroDAO = new PerroDAO();
        $conexion->ejecutar($perroDAO->siguienteId());
        $nextId = $conexion->registro()[0] ?? 1; 

        $perroDAO = new PerroDAO(0, $this->nombre, "", "", $this->dueño->getId());
        $conexion->ejecutar($perroDAO->existePerro($this->nombre, $this->dueño->getId()));
        
        if($conexion->filas() > 0) {
            $conexion->cerrar();
            throw new Exception("Ya tienes un perro registrado con ese nombre");
        }

        $perroDAO = new PerroDAO(
            $nextId,
            $this->nombre,
            $this->raza,
            $this->foto_url,
            $this->dueño->getId()
            );
        
        $conexion->ejecutar($perroDAO->insertar());
        $resultado = $conexion->filasAfectadas() > 0;
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public static function contarTotal() {
        $conexion = new Conexion();
        $dao = new PerroDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->contarTotal());
        
        $dato = $conexion->registro();
        $conexion->cerrar();
        
        return $dato ? (int)($dato->total ?? $dato[0]) : 0;
    }
    
    
}
?>