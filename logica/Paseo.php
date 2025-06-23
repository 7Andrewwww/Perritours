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
    private $dueño;
    private $perro;
    
    public function __construct($idPaseo = "", $tarifa = 0, $fecha = "", $hora = "", $paseador = null, $dueño = null, $perro = null) {
        $this->idPaseo = $idPaseo;
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->paseador = $paseador;
        $this->dueño = $dueño;
        $this->perro = $perro;
    }

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
    
    public function getDueño() {
        return $this->dueño;
    }
    
    public function getPerro() {
        return $this->perro;
    }
    
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
        $conexion->ejecutar($paseoDAO->consultarDetallePaseo($this->idPaseo));
        
        $datos = $conexion->registro();
        $this->tarifa = $datos[1];
        $this->fecha = $datos[2];
        $this->hora = $datos[3];
        $this->paseador = new Paseador(
            $datos[4],
            $datos[5],
            "", "", "",
            $datos[6]
            );
        $this->dueño = new Dueño(
            $datos[7],
            $datos[8],
            "", "",
            $datos[9]
            );
        $this->perro = new Perro(
            $datos[10],
            $datos[11],
            $datos[12],
            $datos[13]
            );
        
        $conexion->cerrar();
    }

    public static function consultarPaseosProgramados($idPaseador, $mes = null, $anio = null) {
        $filtroFecha = "";
        if ($mes && $anio) {
            $filtroFecha = "AND MONTH(p.fecha) = $mes AND YEAR(p.fecha) = $anio";
        }
        
        $sql = "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora
            FROM paseo p
            WHERE p.id_pas = $idPaseador
            $filtroFecha
            AND (p.fecha > CURDATE() OR (p.fecha = CURDATE() AND p.hora > CURTIME()))
            ORDER BY p.fecha ASC, p.hora ASC";
            
            $conexion = new Conexion();
            $conexion->abrir();
            $conexion->ejecutar($sql);
            
            $paseos = array();
            while ($datos = $conexion->registro()) {
                $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3]);
                array_push($paseos, $paseo);
            }
            
            $conexion->cerrar();
            return $paseos;
    }
    
    public static function consultarHistorialPaseos($idPaseador) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarHistorialPaseos($idPaseador));
        
        $paseos = array();
        while($datos = $conexion->registro()) {
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3]
                );
            array_push($paseos, $paseo);
        }
        
        $conexion->cerrar();
        return $paseos;
    }
    
    public function calificarDueño($puntuacion, $idPaseador, $comentario) {
        if (empty($comentario)) {
            throw new Exception("El comentario es obligatorio");
        }
        
        if ($puntuacion < 1 || $puntuacion > 5) {
            throw new Exception("La puntuación debe estar entre 1 y 5");
        }
        
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        
        try {
            // Obtener el id_dueño asociado al paseo
            $id_dueño = $this->getDueño()->getId();
            
            $resultado = $conexion->ejecutar(
                $paseoDAO->calificarDueño($this->idPaseo, $id_dueño, $idPaseador, $puntuacion, $comentario)
                );
            
            if (!$resultado) {
                throw new Exception("Error al guardar la calificación");
            }
            
            return true;
        } finally {
            $conexion->cerrar();
        }
    }
    
    public function obtenerCalificacionDueño($idPaseador) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        
        try {
            $conexion->ejecutar($paseoDAO->obtenerCalificacionDueño($this->idPaseo, $idPaseador));
            
            $calificacion = null;
            if ($datos = $conexion->registro()) {
                $calificacion = [
                    'puntuacion' => $datos[0],
                    'comentario' => $datos[1] ?? null
                ];
            }
            
            return $calificacion;
        } finally {
            $conexion->cerrar();
        }
    }
    
    public static function consultarHistorialPaseosDueño($idDueño) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarHistorialPaseosDueño($idDueño));
        
        $paseos = array();
        while($datos = $conexion->registro()) {
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3]
                );
            array_push($paseos, $paseo);
        }
        
        $conexion->cerrar();
        return $paseos;
    }
    
    public function calificarPaseador($puntuacion, $idPaseador, $comentario) {
        if (empty($comentario)) {
            throw new Exception("El comentario es obligatorio");
        }
        
        if ($puntuacion < 1 || $puntuacion > 5) {
            throw new Exception("La puntuación debe estar entre 1 y 5");
        }
        
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        
        try {
            // Obtener el id_dueño asociado al paseo
            $id_dueño = $this->getDueño()->getId();
            
            $resultado = $conexion->ejecutar(
                $paseoDAO->calificarPaseador($this->idPaseo, $idPaseador, $id_dueño, $puntuacion, $comentario)
                );
            
            if (!$resultado) {
                throw new Exception("Error al guardar la calificación");
            }
            
            return true;
        } finally {
            $conexion->cerrar();
        }
    }
    
    public function obtenerCalificacionPaseador($idPaseador) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        
        try {
            $conexion->ejecutar($paseoDAO->obtenerCalificacionPaseador($this->idPaseo, $idPaseador));
            
            $calificacion = null;
            if ($datos = $conexion->registro()) {
                $calificacion = [
                    'puntuacion' => $datos[0],
                    'comentario' => $datos[1] ?? null
                ];
            }
            
            return $calificacion;
        } finally {
            $conexion->cerrar();
        }
    }
}
?>