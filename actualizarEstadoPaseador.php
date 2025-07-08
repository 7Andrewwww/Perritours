<?php
require("persistencia/Conexion.php");
require("persistencia/PaseadorDAO.php");
require("logica/Paseador.php");

if (isset($_GET["idPaseador"]) && isset($_GET["idNuevoEstado"])) {
    $idPaseador = $_GET["idPaseador"];
    $idNuevoEstado = $_GET["idNuevoEstado"];
    
    $paseador = new Paseador($idPaseador);
    $paseador->consultar();
    
    $paseador->actualizarEstado($idNuevoEstado);
    
    $estado = new Estado($idNuevoEstado);
    echo $estado->getEstado();
} else {
    echo "Error: Faltan parámetros";
}
?>