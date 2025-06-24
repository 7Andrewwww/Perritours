<?php
class EstadoSolicitud {
    private $idEstado;
    private $nombre;
    private $descripcion;
    
    public function __construct($idEstado, $nombre, $descripcion) {
        $this->idEstado = $idEstado;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getIdEstado() {
        return $this->idEstado;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

}
?>