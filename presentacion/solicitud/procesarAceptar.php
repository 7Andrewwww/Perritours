<?php
// Verificar sesión
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Validar entrada
$idSolicitud = $_POST['id_solicitud'] ?? null;
if (!$idSolicitud) {
    $_SESSION['mensaje'] = "No se especificó la solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
    exit();
}

// Procesar la aceptación
try {
    $solicitud = new SolicitudPaseo($idSolicitud);
    $solicitud->consultar();
    
    // Verificar propiedad de la solicitud
    if ($solicitud->getPaseador()->getId() != $_SESSION['id']) {
        throw new Exception("No tienes permiso para esta acción");
    }
    
    // Tarifa podría venir de un formulario
    $tarifa = 15000; // Valor por defecto o $_POST['tarifa']
    
    if ($solicitud->aceptar($tarifa)) {
        $_SESSION['mensaje'] = "¡Solicitud aceptada correctamente!";
    } else {
        throw new Exception("Error al procesar la aceptación");
    }
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = $e->getMessage();
    error_log($e->getMessage());
}

header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
exit();
?>