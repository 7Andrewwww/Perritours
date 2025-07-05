<?php
// Verificar sesión de dueño
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Validar datos POST
$idPaseador = $_POST['id_paseador'] ?? null;
$idPerro = $_POST['id_perro'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;

// Validaciones básicas
if (!$idPaseador || !$idPerro || !$fecha || !$hora) {
    $_SESSION['mensaje'] = "Datos incompletos para la solicitud";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php") . "&id_paseador=" . $idPaseador);
    exit();
}

// Verificar propiedad del perro
$conexion = new Conexion();
$conexion->abrir();

$query = "SELECT id_dueño FROM perro WHERE id_perro = $idPerro";
$conexion->ejecutar($query);

if ($conexion->filas() == 0 || $conexion->registro()[0] != $_SESSION['id']) {
    $_SESSION['mensaje'] = "El perro seleccionado no pertenece a tu cuenta";
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php") . "&id_paseador=" . $idPaseador);
    $conexion->cerrar();
    exit();
}

// Verificar disponibilidad del paseador
$query = "SELECT COUNT(*) FROM solicitud_paseo
          WHERE id_paseador = $idPaseador
          AND fecha_paseo = '$fecha'
          AND hora_inicio = '$hora'
          AND id_estado IN (1, 2)"; // Pendiente o Aceptado

$conexion->ejecutar($query);
$count = $conexion->registro()[0];

if ($count >= 2) {
    $_SESSION['mensaje'] = "El paseador ya tiene $count perro(s) asignados para esa franja horaria";
    $conexion->cerrar();
    header("Location: ?pid=" . base64_encode("presentacion/solicitud/solicitudPaseo.php") . "&id_paseador=" . $idPaseador);
    exit();
}

// Crear la solicitud
$solicitud = new SolicitudPaseo(
    0,
    new Dueño($_SESSION['id']),
    new Paseador($idPaseador),
    new Perro($idPerro),
    new EstadoSolicitud(1, "", ""), // Estado pendiente
    $fecha,
    $hora
    );

$resultado = $solicitud->crear();
$conexion->cerrar();

if ($resultado) {
    $_SESSION['mensaje'] = "¡Solicitud enviada con éxito!";
} else {
    $_SESSION['mensaje'] = "Error al enviar la solicitud";
}

header("Location: ?pid=" . base64_encode("presentacion/paseador/consultarPaseadores.php"));
exit();
?>
