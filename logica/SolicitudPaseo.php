<?php
require_once("persistencia/SolicitudPaseoDAO.php");
require_once("persistencia/Conexion.php");
require_once("logica/Paseador.php");
require_once("logica/Dueño.php");
require_once("logica/Perro.php");
require_once("logica/EstadoSolicitud.php");

class SolicitudPaseo {
    private $idSolicitud;
    private $dueño;
    private $paseador;
    private $perro;
    private $estado;
    private $fecha;
    private $hora;
    private $fechaCreacion;
    
    public function __construct(
        $idSolicitud = "",
        $dueño = null,
        $paseador = null,
        $perro = null,
        $estado = null,
        $fecha = "",
        $hora = "",
        $fechaCreacion = ""
        ) {
            $this->idSolicitud = $idSolicitud;
            $this->dueño = $dueño;
            $this->paseador = $paseador;
            $this->perro = $perro;
            $this->estado = $estado;
            $this->fecha = $fecha;
            $this->hora = $hora;
            $this->fechaCreacion = $fechaCreacion;
    }
    
    public function getIdSolicitud() {
        return $this->idSolicitud;
    }
    
    public function getDueño() {
        return $this->dueño;
    }
    
    public function getPaseador() {
        return $this->paseador;
    }
    
    public function getPerro() {
        return $this->perro;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function getHora() {
        return $this->hora;
    }
    
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }
    
    public static function consultarPendientesPorPaseador($idPaseador) {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($solicitudDAO->obtenerSolicitudesPendientesPaseador($idPaseador));
        
        $solicitudes = array();
        while($datos = $conexion->registro()) {
            $solicitud = new SolicitudPaseo(
                $datos[0], 
                new Dueño(
                    $datos[3], 
                    $datos[4], 
                    '', 
                    '', 
                    $datos[5]  
                    ),
                new Paseador($idPaseador),
                new Perro(
                    $datos[6], 
                    $datos[7], 
                    $datos[8]  
                    ),
                new EstadoSolicitud($datos[9], "Pendiente", ""),
                $datos[1], 
                $datos[2]  
                );
            array_push($solicitudes, $solicitud);
        }
        
        $conexion->cerrar();
        return $solicitudes;
    }
    
    public function consultar() {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO($this->idSolicitud);
        $conexion->abrir();
        $conexion->ejecutar($solicitudDAO->obtenerPorId());
        
        $datos = $conexion->registro();
        if (!$datos) {
            $conexion->cerrar();
            throw new Exception("Solicitud no encontrada");
        }
        
        $this->dueño = new Dueño(
            $datos[1] ?? 0,      
            $datos[7] ?? '',    
            '',                 
            '',                  
            $datos[8] ?? ''      
            );
        
        $this->paseador = new Paseador(
            $datos[2] ?? 0,       
            $datos[9] ?? '',     
            '',                   
            '',                   
            $datos[10] ?? '',     
            '',                  
            ''                    
            );
        
        $this->perro = new Perro(
            $datos[3] ?? 0,      
            $datos[11] ?? '',    
            $datos[12] ?? '',    
            '',                   
            new Dueño($datos[1] ?? 0) 
            );
        
        $this->estado = new EstadoSolicitud(
            $datos[4] ?? 1,      
            $datos[15] ?? 'Pendiente', 
            $datos[16] ?? ''     
            );
        
        $this->fecha = $datos[5] ?? date('Y-m-d');
        $this->hora = $datos[6] ?? '00:00';
        $this->fechaCreacion = $datos[14] ?? date('Y-m-d H:i:s');
        
        $conexion->cerrar();
    }
    
    public function crear() {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO(
            0,
            $this->dueño->getId(),
            $this->paseador->getId(),
            $this->perro->getIdPerro(), 
            $this->estado->getIdEstado(),
            $this->fecha,
            $this->hora
            );
        
        $conexion->abrir();
        $resultado = $conexion->ejecutar($solicitudDAO->crearSolicitud());
        $this->idSolicitud = $conexion->obtenerUltimoId();
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public function aceptar($tarifa) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        try {
            // Iniciar transacción
            $conexion->ejecutar("START TRANSACTION");
            
            // 1. Actualizar estado de la solicitud
            $solicitudDAO = new SolicitudPaseoDAO();
            $conexion->ejecutar($solicitudDAO->aceptarSolicitud($this->idSolicitud));
            
            // 2. Obtener próximo ID de paseo
            $conexion->ejecutar($solicitudDAO->obtenerProximoIdPaseo());
            $idPaseo = $conexion->registro()[0];
            
            // 3. Crear nuevo paseo
            $conexion->ejecutar($solicitudDAO->crearPaseo(
                $idPaseo,
                $tarifa,
                $this->fecha,
                $this->hora,
                $this->paseador->getId()
                ));
            
            // Confirmar transacción
            $conexion->ejecutar("COMMIT");
            $conexion->cerrar();
            return true;
            
        } catch (Exception $e) {
            // Revertir en caso de error
            $conexion->ejecutar("ROLLBACK");
            $conexion->cerrar();
            error_log("Error al aceptar solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    public function rechazar() {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO($this->idSolicitud);
        
        $conexion->abrir();
        $resultado = $conexion->ejecutar($solicitudDAO->rechazarSolicitud());
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public function cancelar() {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO($this->idSolicitud);
        
        $conexion->abrir();
        $resultado = $conexion->ejecutar($solicitudDAO->cancelarSolicitud());
        $conexion->cerrar();
        
        return $resultado;
    }
    
    public static function consultarHistorialPorDueño($idDueño) {
        $conexion = new Conexion();
        $solicitudDAO = new SolicitudPaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($solicitudDAO->obtenerHistorialSolicitudesDueño($idDueño));
        
        $solicitudes = array();
        while($datos = $conexion->registro()) {       
            $paseador = new Paseador(
                $datos[3], 
                $datos[4],  
                '',     
                '',       
                $datos[5],  
                '',         
                ''        
                );
            
            $perro = new Perro(
                $datos[6],  
                $datos[7],  
                $datos[8],
                '',         
                new Dueño($idDueño)
                );
            
            $solicitud = new SolicitudPaseo(
                $datos[0],  
                new Dueño($idDueño),
                $paseador,
                $perro,
                new EstadoSolicitud($datos[9], $datos[10], ''), 
                $datos[1],  
                $datos[2],  
                $datos[11]  
                );
            
            array_push($solicitudes, $solicitud);
        }
        
        $conexion->cerrar();
        return $solicitudes;
    }
}
?>