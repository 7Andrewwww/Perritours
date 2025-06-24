<?php
// Verificar sesión de paseador
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idSolicitud = $_POST['id_solicitud'] ?? null;
if (!$idSolicitud) {
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
    exit();
}

$solicitud = new SolicitudPaseo($idSolicitud);
$solicitud->consultar();

// Verificar que la solicitud es para este paseador
if ($solicitud->getPaseador()->getId() != $_SESSION['id']) {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

if ($solicitud->rechazar()) {
    $_SESSION['mensaje'] = "Solicitud rechazada correctamente";
} else {
    $_SESSION['mensaje'] = "Error al rechazar la solicitud";
}

header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
exit();
?>