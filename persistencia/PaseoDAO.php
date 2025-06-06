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
    
    public function consultar() {
        return "SELECT p.id_paseo, p.tarifa, p.fecha, p.hora,
                       pa.id_pas, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.id_pas = pa.id_pas
                WHERE p.id_paseo = " . $this->id_paseo;
    }
}
?>