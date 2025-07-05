<?php
class SolicitudPaseoDAO {
    private $id_solicitud;
    private $id_dueño;
    private $id_paseador;
    private $id_perro;
    private $id_estado;
    private $fecha_paseo;
    private $hora_inicio;
    private $fecha_creacion;
    
    public function __construct(
        $id_solicitud = 0,
        $id_dueño = 0,
        $id_paseador = 0,
        $id_perro = 0,
        $id_estado = 1,
        $fecha_paseo = "",
        $hora_inicio = "",
        $fecha_creacion = ""
        ) {
            $this->id_solicitud = $id_solicitud;
            $this->id_dueño = $id_dueño;
            $this->id_paseador = $id_paseador;
            $this->id_perro = $id_perro;
            $this->id_estado = $id_estado;
            $this->fecha_paseo = $fecha_paseo;
            $this->hora_inicio = $hora_inicio;
            $this->fecha_creacion = $fecha_creacion;
    }
    
    // Método para crear una nueva solicitud
    public function crearSolicitud() {
        return "INSERT INTO solicitud_paseo (
            id_dueño,
            id_paseador,
            id_perro,
            id_estado,
            fecha_paseo,
            hora_inicio
        ) VALUES (
            $this->id_dueño,
            $this->id_paseador,
            $this->id_perro,
            $this->id_estado,
            '$this->fecha_paseo',
            '$this->hora_inicio'
        )";
    }
    
    public function obtenerPorId() {
        return "SELECT
            s.id_solicitud,
            s.id_dueño,
            s.id_paseador,
            s.id_perro,
            s.id_estado,
            s.fecha_paseo,
            s.hora_inicio,
            d.nombre AS nombre_dueño,
            d.telefono AS telefono_dueño,
            p.nombre AS nombre_paseador,
            p.telefono AS telefono_paseador,
            per.nombre AS nombre_perro,
            per.raza AS raza_perro,
            s.fecha_creacion,
            es.nombre AS nombre_estado,
            es.descripcion AS descripcion_estado
        FROM solicitud_paseo s
        JOIN dueño d ON s.id_dueño = d.id_dueño
        JOIN paseador p ON s.id_paseador = p.id_pas
        JOIN perro per ON s.id_perro = per.id_perro
        JOIN estado_solicitud es ON s.id_estado = es.id_estado
        WHERE s.id_solicitud = $this->id_solicitud";
    }
    
    public function obtenerSolicitudesPendientesPaseador($id_paseador) {
        return "SELECT
            s.id_solicitud,
            s.fecha_paseo,
            s.hora_inicio,
            d.id_dueño,
            d.nombre AS nombre_dueño,
            d.telefono AS telefono_dueño,
            per.id_perro,
            per.nombre AS nombre_perro,
            per.raza AS raza_perro,
            s.id_estado
        FROM solicitud_paseo s
        JOIN dueño d ON s.id_dueño = d.id_dueño
        JOIN perro per ON s.id_perro = per.id_perro
        WHERE s.id_paseador = $id_paseador
        AND s.id_estado = 1
        ORDER BY s.fecha_paseo ASC, s.hora_inicio ASC";
    }
    
    public function obtenerHistorialSolicitudesDueño($id_dueño) {
        return "SELECT
            s.id_solicitud,
            s.fecha_paseo,
            s.hora_inicio,
            p.id_pas,
            p.nombre AS nombre_paseador,
            p.telefono,
            per.id_perro,
            per.nombre AS nombre_perro,
            per.raza,
            es.id_estado,
            es.nombre AS estado,
            s.fecha_creacion
        FROM solicitud_paseo s
        JOIN paseador p ON s.id_paseador = p.id_pas
        JOIN perro per ON s.id_perro = per.id_perro
        JOIN estado_solicitud es ON s.id_estado = es.id_estado
        WHERE s.id_dueño = $id_dueño
        ORDER BY s.fecha_paseo DESC, s.hora_inicio DESC";
    }
    
    // Método para actualizar el estado de una solicitud
    public function actualizarEstado($nuevoEstado) {
        return "UPDATE solicitud_paseo
                SET id_estado = $nuevoEstado
                WHERE id_solicitud = $this->id_solicitud";
    }
    
    public function aceptarSolicitud($idSolicitud) {
        return "UPDATE solicitud_paseo SET id_estado = 2 WHERE id_solicitud = $idSolicitud";
    }
    
    public function obtenerProximoIdPaseo() {
        return "SELECT IFNULL(MAX(id_paseo), 0) + 1 FROM paseo";
    }
    
    // Método para que el paseador rechace una solicitud
    public function rechazarSolicitud() {
        return "UPDATE solicitud_paseo
            SET id_estado = 3
            WHERE id_solicitud = $this->id_solicitud";
    }
    
    
    // Método para que el dueño cancele una solicitud
    public function cancelarSolicitud() {
        return "UPDATE solicitud_paseo
                SET id_estado = 4
                WHERE id_solicitud = $this->id_solicitud
                AND id_dueño = $this->id_dueño";
    }
    
    public function crearPaseo($idPaseo, $tarifa, $fecha, $hora, $idPaseador) {
        return "INSERT INTO paseo (
                id_paseo,
                tarifa,
                fecha,
                hora,
                id_pas
              ) VALUES (
                $idPaseo,
                $tarifa,
                '$fecha',
                '$hora',
                $idPaseador
              )";
    }
    
    public function relacionarPaseoPerro($idPaseo, $idPerro) {
        return "INSERT INTO paseo_perro (id_paseo, id_perro)
            VALUES ($idPaseo, $idPerro)";
    }
    
    
}
?>