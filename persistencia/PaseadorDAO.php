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
        return "SELECT p.nombre, p.correo, p.telefono, p.foto_url, p.id_estado, e.estado
                FROM paseador p
                JOIN estado_paseador e ON p.id_estado = e.id_estado
                WHERE p.id_pas = '" . $this->id_pas . "'";
    }
    
    public function consultarTodos() {
        return "SELECT p.id_pas, p.nombre, p.correo, p.telefono, p.foto_url, p.id_estado, e.estado
            FROM paseador p
            JOIN estado_paseador e ON p.id_estado = e.id_estado
            ORDER BY p.nombre";
    }
    
    public function crear() {
        return "INSERT INTO paseador (id_pas, nombre, correo, clave, telefono, foto_url, id_estado)
            VALUES ('" . $this->id_pas . "',
                    '" . $this->nombre . "',
                    '" . $this->correo . "',
                    '" . md5($this->clave) . "',
                    " . $this->telefono . ",
                    '" . $this->foto_url . "',
                    " . $this->id_estado . ")";
    }

    public function actualizar() {
        return "UPDATE paseador SET
            nombre = '" . $this->nombre . "',
            correo = '" . $this->correo . "',
            telefono = " . $this->telefono . ",
            foto_url = '" . $this->foto_url . "',
            id_estado = " . $this->id_estado . "
            WHERE id_pas = '" . $this->id_pas . "'";
    }
    

    public function actualizarClave() {
        return "UPDATE paseador SET
            clave = '" . md5($this->clave) . "'
            WHERE id_pas = '" . $this->id_pas . "'";
    }

    public function eliminar() {
        return "DELETE FROM paseador WHERE id_pas = '" . $this->id_pas . "'";
    }
    
}
?>