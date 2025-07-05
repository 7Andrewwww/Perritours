<?php

class DueñoDAO {
    private $id_dueño;
    private $nombre;
    private $correo;
    private $clave;
    private $telefono;
    
    public function __construct($id_dueño = 0, $nombre = "", $correo = "", $clave = "", $telefono = 0) {
        $this->id_dueño = $id_dueño;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->telefono = $telefono;
    }
    
    public function autenticar() {
        return "SELECT id_dueño
                FROM dueño
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }
    
    public function consultar() {
        return "SELECT nombre, correo, telefono
                FROM dueño
                WHERE id_dueño = '" . $this->id_dueño . "'";
    }
    
    public function consultarTodos() {
        return "SELECT id_dueño, nombre, correo, telefono FROM dueño";
    }
    
    public function actualizar() {
        return "UPDATE dueño SET
                nombre = '" . $this->nombre . "',
                correo = '" . $this->correo . "',
                telefono = " . $this->telefono . "
                WHERE id_dueño = " . $this->id_dueño;
    }
    
    public function actualizarClave() {
        return "UPDATE dueño SET
                clave = '" . md5($this->clave) . "'
                WHERE id_dueño = " . $this->id_dueño;
    }
    
    public function verificarCorreoExistente() {
        return "SELECT id_dueño FROM dueño
                WHERE correo = '" . $this->correo . "'
                AND id_dueño != " . $this->id_dueño;
    }
    
    public function verificarClaveActual() {
        return "SELECT id_dueño FROM dueño
                WHERE id_dueño = " . $this->id_dueño . "
                AND clave = '" . md5($this->clave) . "'";
    }
        
    public function obtenerPromedioSatisfaccion() {
        return "SELECT ROUND(AVG(puntuacion), 1) AS promedio FROM calificacion_dueño";
    }
    
    

}