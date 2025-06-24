<?php
// Verificar sesión de dueño
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Validar datos POST
$idPaseador = $_POST['id_paseador'] ?? null;
$perrosSeleccionados = $_POST['id_perro'] ?? [];
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;

// Validaciones básicas
if (!$idPaseador || empty(array_filter($perrosSeleccionados)) || !$fecha || !$hora) {
    $_SESSION['mensaje'] = "Datos incompletos para la solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php")."&id_paseador=".$idPaseador);
    exit();
}

// Validar máximo 2 perros
if (count(array_filter($perrosSeleccionados)) > 2) {
    $_SESSION['mensaje'] = "Solo puedes seleccionar máximo 2 perros por solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php")."&id_paseador=".$idPaseador);
    exit();
}

// Verificar propiedad de los perros
$idsUnicos = [];
$conexion = new Conexion();
$conexion->abrir();

foreach (array_filter($perrosSeleccionados) as $idPerro) {
    // Verificar que el perro pertenece al dueño
    $query = "SELECT id_dueño FROM perro WHERE id_perro = $idPerro";
    $conexion->ejecutar($query);
    
    if ($conexion->filas() == 0 || $conexion->registro()[0] != $_SESSION['id']) {
        $_SESSION['mensaje'] = "Uno de los perros seleccionados no pertenece a tu cuenta";
        header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php")."&id_paseador=".$idPaseador);
        exit();
    }
    
    // Verificar duplicados
    if (in_array($idPerro, $idsUnicos)) {
        $_SESSION['mensaje'] = "No puedes seleccionar el mismo perro más de una vez";
        header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php")."&id_paseador=".$idPaseador);
        exit();
    }
    $idsUnicos[] = $idPerro;
}

// Verificar disponibilidad del paseador (sin parámetros preparados)
$query = "SELECT COUNT(*) FROM solicitud_paseo
          WHERE id_paseador = $idPaseador
          AND fecha_paseo = '$fecha'
          AND hora_inicio = '$hora'
          AND id_estado IN (1, 2)"; // Estados pendiente y aceptado

$conexion->ejecutar($query);
$count = $conexion->registro()[0];

if (($count + count($idsUnicos)) > 2) {
    $_SESSION['mensaje'] = "Este paseador ya tiene $count perro(s) asignados para esa franja horaria";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php")."&id_paseador=".$idPaseador);
    exit();
}

// Crear solicitudes
$resultado = true;
foreach ($idsUnicos as $idPerro) {
    $solicitud = new SolicitudPaseo(
        0,
        new Dueño($_SESSION['id']),
        new Paseador($idPaseador),
        new Perro($idPerro),
        new EstadoSolicitud(1,"",""), 
        $fecha,
        $hora
        );
    
    if (!$solicitud->crear()) {
        $resultado = false;
        break;
    }
}

$conexion->cerrar();

if ($resultado) {
    $_SESSION['mensaje'] = "¡Solicitud(es) enviada(s) con éxito para ".count($idsUnicos)." perro(s)!";
} else {
    $_SESSION['mensaje'] = "Error al enviar alguna de las solicitudes";
}

header("Location: ?pid=" . base64_encode("presentacion/paseador/consultarPaseadores.php"));
exit();
?>