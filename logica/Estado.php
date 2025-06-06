<?php
Class Estado{
    
    private $idEstado;
    private $estado;
    
    public function __construct($idEstado = "", $estado = "") {
        $this -> idEstado = $idEstado;
        $this -> estado = $estado;
    }
    
    public function getIdEstado() {
        return $this->idEstado;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function setIdEstado($idEstado) {
        $this->idEstado = $idEstado;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
}
?>