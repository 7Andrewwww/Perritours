<?php

class PaseadorDAO {
    private $id_pas;
    private $nombre;
    private $correo;
    private $clave;
    private $telefono;
    private $foto_url;
    private $id_estado;
    
    public function __construct($id_pas = 0, $nombre = "", $correo = "", $clave = "", $telefono = 0, $foto_url = "", $id_estado = "") {
        $this->id_pas = $id_pas;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->telefono = $telefono;
        $this->foto_url = $foto_url;
        $this->id_estado = $id_estado;
    }
    
    public function autenticar() {
        return "SELECT id_pas
                FROM paseador
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }
    
    public function consultar() {
        return "SELECT nombre, correo, telefono, foto_url, id_estado
                FROM paseador
                WHERE id_pas = '" . $this->id_pas . "'";
    }
}