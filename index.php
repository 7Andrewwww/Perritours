<?php
session_start();
require ("logica/Admin.php");
require ("logica/Dueño.php");  
require ("logica/Paseador.php");
require ("logica/Perro.php");
require ("logica/Paseo.php");
require ("logica/SolicitudPaseo.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perritours</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link href="https://use.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<?php 
$paginas_sin_autenticacion = array(
    "presentacion/inicio.php",
    "presentacion/autenticar.php",
    "presentacion/noAutorizado.php",
    "presentacion/error.php",
    "presentacion/estadisticas/graficas.php",
    "presentacion/paseador/actualizarEstadoPaseador.php",
);

$paginas_con_autenticacion = array(
    "presentacion/sesionAdmin.php",
    "presentacion/sesionPaseador.php",
    "presentacion/sesionDueño.php",
    "presentacion/paseador/crearPaseador.php",
    "presentacion/paseador/consultarPaseador.php",
    "presentacion/paseador/consultarPaseadores.php",
    "presentacion/paseador/editarPaseador.php",
    "presentacion/paseador/editarMiPerfil.php",
    "presentacion/paseador/guardarCalificacion.php",
    "presentacion/paseador/gestionHorario.php",
    "presentacion/paseador/detallePaseo.php",

    "presentacion/dueño/consultarDueño.php",
    "presentacion/dueño/editarMiPerfil.php",
    "presentacion/dueño/dashboardDueño.php",
    "presentacion/dueño/detallePaseo.php",
    "presentacion/dueño/guardarCalificacion.php",
    "presentacion/perro/consultarPerro.php",
    "presentacion/perro/consultarMisPerros.php",
    "presentacion/perro/crearPerro.php",
    "presentacion/paseito/consultarPaseo.php",
    "presentacion/paseito/consultarPaseoPaseador.php",
    "presentacion/paseito/historialPaseos.php",
    "presentacion/paseito/historialMisPaseos.php",
    "presentacion/solicitud/solicitarPaseo.php",
    "presentacion/solicitud/verPaseosDueño.php",
    "presentacion/solicitud/verPaseosPaseador.php",
    "presentacion/solicitud/procesarSolicitud.php",
    "presentacion/solicitud/procesarAceptar.php",
    "presentacion/solicitud/procesarRechazar.php",
    "presentacion/factura/consultarFacturas.php",
);

if(!isset($_GET["pid"])){
    include ("presentacion/inicio.php");
}else{
    $pid = base64_decode($_GET["pid"]);
    if(in_array($pid, $paginas_sin_autenticacion)){
        include $pid;
    }else if(in_array($pid, $paginas_con_autenticacion)){
        if(!isset($_SESSION["id"])){
            include "presentacion/autenticar.php";
        }else{
            include $pid;
        }
    }else{
        echo "error 404";
    }
}
?>
</body>
</html>