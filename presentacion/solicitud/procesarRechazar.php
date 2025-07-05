<?php
// Verificar sesión de paseador
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idSolicitud = $_POST['id_solicitud'] ?? null;

if (!$idSolicitud) {
    $_SESSION['error'] = "Solicitud inválida";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
    exit();
}

$solicitud = new SolicitudPaseo($idSolicitud);
$solicitud->consultar();

// Verificar que la solicitud le pertenece a este paseador
if ($solicitud->getPaseador()->getId() != $_SESSION['id']) {
    $_SESSION['error'] = "No tienes permiso para modificar esta solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Rechazar solicitud
if ($solicitud->rechazar()) {
    $_SESSION['exito'] = "Solicitud rechazada correctamente.";
} else {
    $_SESSION['error'] = "No se pudo rechazar la solicitud. Intenta nuevamente.";
}

header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
exit();
?>
