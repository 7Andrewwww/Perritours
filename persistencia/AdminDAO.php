<?php

class AdminDAO {
    private $id_adm;
    private $nombre;
    private $correo;
    private $clave;
    private $telefono;
    
    public function __construct($id_adm = 0, $nombre = "", $correo = "", $clave = "", $telefono = 0) {
        $this->id_adm = $id_adm;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->telefono = $telefono;
    }
    
    public function autenticar() {
        return "SELECT id_admin
                FROM administrador
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }
    
    public function consultar() {
        return "SELECT nombre, correo, telefono
                FROM administrador
                WHERE id_admin = '" . $this->id_adm . "'";
    }
}