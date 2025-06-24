<?php
class PaseoDAO {
    private $id_paseo;
    private $tarifa;
    private $fecha;
    private $hora;
    private $id_pas;
    
    public function __construct($id_paseo = 0, $tarifa = 0, $fecha = "", $hora = "", $id_pas = 0) {
        $this->id_paseo = $id_paseo;
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->id_pas = $id_pas;
    }
    
    public function consultarTodos() {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora,
                       pa.id_pas, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.id_pas = pa.id_pas
                ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultarPorPaseador($id_pas) {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora
                FROM paseo p
                WHERE p.id_pas = " . $id_pas . "
                ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultarPaseosProgramados($id_pas) {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora
                FROM paseo p
                WHERE p.id_pas = " . $id_pas . "
                AND (p.fecha > CURDATE() OR (p.fecha = CURDATE() AND p.hora > CURTIME()))
                ORDER BY p.fecha ASC, p.hora ASC";
    }
    
    public function consultarHistorialPaseos($id_pas) {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora
                FROM paseo p
                WHERE p.id_pas = " . $id_pas . "
                AND (p.fecha < CURDATE() OR (p.fecha = CURDATE() AND p.hora <= CURTIME()))
                ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultarDetallePaseo($id_paseo) {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora,
                       pa.id_pas, pa.nombre as nombre_paseador, pa.foto_url as foto_paseador,
                       d.id_dueño, d.nombre as nombre_dueño, d.telefono as telefono_dueño,
                       per.id_perro, per.nombre as nombre_perro, per.raza, per.foto_url as foto_perro
                FROM paseo p
                JOIN paseador pa ON p.id_pas = pa.id_pas
                JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
                JOIN perro per ON pp.id_perro = per.id_perro
                JOIN dueño d ON per.id_dueño = d.id_dueño
                WHERE p.id_paseo = " . $this->id_paseo;
    }
    
    public function calificarDueño($id_paseo, $id_dueño, $id_paseador, $puntuacion, $comentario) {
        return "INSERT INTO calificacion_dueño
            (id_paseo, id_dueño, id_paseador, puntuacion, comentario)
            VALUES
            ($id_paseo, $id_dueño, $id_paseador, $puntuacion, '$comentario')
            ON DUPLICATE KEY UPDATE
                puntuacion = VALUES(puntuacion),
                comentario = VALUES(comentario),
                fecha = CURRENT_TIMESTAMP";
    }
    
    public function obtenerCalificacionDueño($id_paseo, $id_paseador) {
        return "SELECT puntuacion, comentario
            FROM calificacion_dueño
            WHERE id_paseo = $id_paseo AND id_paseador = $id_paseador";
    }
    
    public function consultarPorDueño($id_dueño) {
        return "SELECT DISTINCT p.id_paseo, p.tarifa, p.fecha, p.hora
            FROM paseo p
            JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
            JOIN perro per ON pp.id_perro = per.id_perro
            WHERE per.id_dueño = " . $id_dueño . "
            ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultarPaseosProgramadosDueño($id_dueño) {
        return "SELECT DISTINCT p.id_paseo, p.tarifa, p.fecha, p.hora
            FROM paseo p
            JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
            JOIN perro per ON pp.id_perro = per.id_perro
            WHERE per.id_dueño = " . $id_dueño . "
            AND (p.fecha > CURDATE() OR (p.fecha = CURDATE() AND p.hora > CURTIME()))
            ORDER BY p.fecha ASC, p.hora ASC";
    }
    
    public function consultarHistorialPaseosDueño($id_dueño) {
        return "SELECT DISTINCT p.id_paseo, p.tarifa, p.fecha, p.hora
            FROM paseo p
            JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
            JOIN perro per ON pp.id_perro = per.id_perro
            WHERE per.id_dueño = " . $id_dueño . "
            AND (p.fecha < CURDATE() OR (p.fecha = CURDATE() AND p.hora <= CURTIME()))
            ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function calificarPaseador($id_paseo, $id_paseador, $id_dueño, $puntuacion, $comentario) {
        return "INSERT INTO calificacion_paseador
            (id_paseo, id_paseador, id_dueño, puntuacion, comentario)
            VALUES
            ($id_paseo, $id_paseador, $id_dueño, $puntuacion, '$comentario')
            ON DUPLICATE KEY UPDATE
                puntuacion = VALUES(puntuacion),
                comentario = VALUES(comentario),
                fecha = CURRENT_TIMESTAMP";
    }
    
    public function obtenerCalificacionPaseador($id_paseo, $id_paseador) {
        return "SELECT puntuacion, comentario
            FROM calificacion_paseador
            WHERE id_paseo = $id_paseo AND id_paseador = $id_paseador";
    }
    
    
}
?>