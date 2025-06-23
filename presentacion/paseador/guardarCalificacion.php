<?php
// Verificar rol de paseador
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Verificar datos POST
if (!isset($_POST['idPaseo']) || !isset($_POST['puntuacion'])) {
    $_SESSION['mensaje'] = "Datos incompletos para la calificación";
    header("Location: ?pid=" . base64_encode("presentacion/paseador/historialPaseos.php"));
    exit();
}

$idPaseo = $_POST['idPaseo'];
$puntuacion = (int)$_POST['puntuacion'];
$idPaseador = $_SESSION['id']; // Usar la variable correcta de sesión

// Validar rango de puntuación
if ($puntuacion < 1 || $puntuacion > 5) {
    $_SESSION['mensaje'] = "La puntuación debe estar entre 1 y 5 estrellas";
    header("Location: ?pid=" . base64_encode("presentacion/paseador/detallePaseo.php") . "&idPaseo=" . $idPaseo);
    exit();
}

$paseo = new Paseo($idPaseo);
$paseo->consultar();

// Verificar que el paseo sea pasado y pertenezca al paseador
$fechaHoraPaseo = strtotime($paseo->getFecha() . ' ' . $paseo->getHora());
$esPaseoPasado = $fechaHoraPaseo < time();

if (!$esPaseoPasado || $paseo->getPaseador()->getId() != $idPaseador) {
    $_SESSION['mensaje'] = "No puedes calificar este paseo";
    header("Location: ?pid=" . base64_encode("presentacion/paseador/historialPaseos.php"));
    exit();
}

$resultado = $paseo->calificar($puntuacion, $idPaseador);

if ($resultado) {
    $_SESSION['mensaje'] = "¡Calificación guardada con éxito!";
} else {
    $_SESSION['mensaje'] = "Error al guardar la calificación";
}

header("Location: ?pid=" . base64_encode("presentacion/paseador/detallePaseo.php") . "&idPaseo=" . $idPaseo);
exit();
?>