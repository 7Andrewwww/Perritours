<?php

abstract class Persona {
    protected $id;
    protected $nombre;
    protected $correo;
    protected $clave;
    protected $telefono;
    
    public function __construct($id = "", $nombre="", $correo="", $clave="", $telefono="") {
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> telefono = $telefono;
    }
    
    public function getId(){
        return $this -> id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getCorreo() {
        return $this->correo;
    }
    
    public function getClave() {
        return $this->clave;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function setId($id){
        $this -> id = $id;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setCorreo($correo) {
        $this->correo = $correo;
    }
    
    public function setClave($clave) {
        $this->clave = $clave;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
}
?>
