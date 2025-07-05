<?php
// Verificar sesión y rol
if ($_SESSION["rol"] != "paseador") {
    $_SESSION['error'] = "Acceso no autorizado";
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Validar datos POST
if (empty($_POST['id_solicitud']) || empty($_POST['tarifa'])) {
    $_SESSION['error'] = "Datos incompletos para procesar la solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
    exit();
}

$idSolicitud = (int)$_POST['id_solicitud'];
$tarifa = (int)$_POST['tarifa'];

// Validar rango de tarifa
if ($tarifa < 10000 || $tarifa > 100000) {
    $_SESSION['error'] = "La tarifa debe estar entre $10.000 y $100.000 COP";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
    exit();
}

// Procesar la aceptación
try {
    $solicitud = new SolicitudPaseo($idSolicitud);
    $solicitud->consultar();
    
    // Verificar propiedad de la solicitud
    if ($solicitud->getPaseador()->getId() != $_SESSION['id']) {
        throw new Exception("No tienes permiso para aceptar esta solicitud");
    }
    
    // Verificar que la solicitud esté pendiente
    if ($solicitud->getEstado()->getIdEstado() != 1) {
        throw new Exception("Esta solicitud ya fue procesada anteriormente");
    }
    
    // Aceptar la solicitud y obtener resultado detallado
    $resultado = $solicitud->aceptar($tarifa);
    
    if ($resultado['success']) {
        $_SESSION['exito'] = "¡Solicitud aceptada correctamente! ID del paseo: " . $resultado['id_paseo'];
    } else {
        $errorMsg = "Error al procesar la solicitud: " . $resultado['message'];
        if (!empty($resultado['errors'])) {
            $errorMsg .= "<ul class='mb-0'>";
            foreach ($resultado['errors'] as $error) {
                $errorMsg .= "<li>{$error['message']}</li>";
            }
            $errorMsg .= "</ul>";
        }
        $_SESSION['error'] = $errorMsg;
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    error_log("Error en procesarAceptar: " . $e->getMessage());
}

header("Location: ?pid=" . base64_encode("presentacion/solicitud/verPaseosPaseador.php"));
exit();
?>