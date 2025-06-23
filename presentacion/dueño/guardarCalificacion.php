<?php
// Verificar rol de dueño
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Verificar datos POST
if (!isset($_POST['idPaseo']) || !isset($_POST['puntuacion'])) {
    $_SESSION['mensaje'] = "Datos incompletos para la calificación";
    header("Location: ?pid=" . base64_encode("presentacion/paseito/historialMisPaseos.php"));
    exit();
}

$idPaseo = $_POST['idPaseo'];
$puntuacion = (int)$_POST['puntuacion'];
$comentario = $_POST['comentario'] ?? null;
$idDueño = $_SESSION['id'];

// Validar rango de puntuación
if ($puntuacion < 1 || $puntuacion > 5) {
    $_SESSION['mensaje'] = "La puntuación debe estar entre 1 y 5 estrellas";
    header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
    exit();
}

// Validar longitud del comentario
if ($comentario && strlen($comentario) > 500) {
    $_SESSION['mensaje'] = "El comentario no puede exceder los 500 caracteres";
    header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
    exit();
}

$paseo = new Paseo($idPaseo);
$paseo->consultar();

// Verificar que el paseo sea pasado y pertenezca al dueño
$fechaHoraPaseo = strtotime($paseo->getFecha() . ' ' . $paseo->getHora());
$esPaseoPasado = $fechaHoraPaseo < time();

$perteneceAlDueño = false;
foreach ($paseo->getPerro() as $perro) {
    if ($perro->getDueño()->getId() == $idDueño) {
        $perteneceAlDueño = true;
        break;
    }
}

if (!$esPaseoPasado || !$perteneceAlDueño) {
    $_SESSION['mensaje'] = "No puedes calificar este paseo";
    header("Location: ?pid=" . base64_encode("presentacion/paseito/historialMisPaseos.php"));
    exit();
}

// Calificar el paseo
$resultado = $paseo->calificarDueño($puntuacion, $idDueño, $comentario);

if ($resultado) {
    $_SESSION['mensaje'] = "¡Calificación guardada con éxito!";
} else {
    $_SESSION['mensaje'] = "Error al guardar la calificación";
}

header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
exit();
?>