<?php
class PerroDAO {
    private $id_perro;
    private $nombre;
    private $raza;
    private $foto_url;
    private $id_dueño;
    
    public function __construct($id_perro = 0, $nombre = "", $raza = "", $foto_url = "", $id_dueño = 0) {
        $this->id_perro = $id_perro;
        $this->nombre = $nombre;
        $this->raza = $raza;
        $this->foto_url = $foto_url;
        $this->id_dueño = $id_dueño;
    }
    
    public function consultarTodos() {
        return "SELECT p.id_perro, p.nombre, p.raza, p.foto_url, d.nombre as nombre_dueño
                FROM perro p
                JOIN dueño d ON p.id_dueño = d.id_dueño
                ORDER BY p.nombre";
    }
    
    public function consultarPorDueño($id_dueño) {
        return "SELECT p.id_perro, p.nombre, p.raza, p.foto_url, d.nombre as nombre_dueño
                FROM perro p
                JOIN dueño d ON p.id_dueño = d.id_dueño
                WHERE p.id_dueño = " . $id_dueño . "
                ORDER BY p.nombre";
    }
    
    
    public function cantidadPorDueño($id_dueño) {
        return "SELECT COUNT(*)
                FROM perro
                WHERE id_dueño = " . $id_dueño;
    }
    
    public function insertar() {
        return "INSERT INTO perro (id_perro, nombre, raza, foto_url, id_dueño)
                VALUES (" . $this->id_perro . ",
                       '" . $this->nombre . "',
                       '" . $this->raza . "',
                       '" . $this->foto_url . "',
                       " . $this->id_dueño . ")";
    }
    
    public function siguienteId() {
        return "SELECT MAX(id_perro) + 1 FROM perro";
    }
    
    public function existePerro($nombre, $id_dueño) {
        return "SELECT id_perro FROM perro
                WHERE nombre = '" . $this->nombre . "'
                AND id_dueño = " . $this->id_dueño;
    }
}
?>