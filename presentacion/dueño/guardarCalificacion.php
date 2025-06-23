<?php
// Verificar rol de dueño
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Verificar datos POST
if (!isset($_POST['idPaseo']) || !isset($_POST['puntuacion']) || !isset($_POST['comentario'])) {
    $_SESSION['mensaje'] = "Datos incompletos para la calificación";
    header("Location: ?pid=" . base64_encode("presentacion/paseito/historialMisPaseos.php"));
    exit();
}

$idPaseo = $_POST['idPaseo'];
$puntuacion = (int)$_POST['puntuacion'];
$comentario = htmlspecialchars(trim($_POST['comentario']));
$idDueño = $_SESSION['id'];

// Validaciones
if (empty($comentario)) {
    $_SESSION['mensaje'] = "Por favor escribe un comentario antes de enviar la calificación";
    header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
    exit();
}

if ($puntuacion < 1 || $puntuacion > 5) {
    $_SESSION['mensaje'] = "La puntuación debe estar entre 1 y 5 estrellas";
    header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
    exit();
}

try {
    $paseo = new Paseo($idPaseo);
    $paseo->consultar();
    
    // Verificar que el paseo sea pasado y pertenezca al dueño
    $fechaHoraPaseo = strtotime($paseo->getFecha() . ' ' . $paseo->getHora());
    $esPaseoPasado = $fechaHoraPaseo < time();
    
    if (!$esPaseoPasado || $paseo->getDueño()->getId() != $idDueño) {
        $_SESSION['mensaje'] = "No puedes calificar este paseo";
        header("Location: ?pid=" . base64_encode("presentacion/paseito/historialMisPaseos.php"));
        exit();
    }
    
    $idPaseador = $paseo->getPaseador()->getId();
    $resultado = $paseo->calificarPaseador($puntuacion, $idPaseador, $comentario);
    
    if ($resultado) {
        $_SESSION['mensaje'] = "¡Calificación del paseador guardada con éxito!";
    } else {
        $_SESSION['mensaje'] = "Error al guardar la calificación. Por favor intenta nuevamente.";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Ocurrió un error inesperado: " . $e->getMessage();
}

header("Location: ?pid=" . base64_encode("presentacion/dueño/detallePaseo.php") . "&idPaseo=" . $idPaseo);
exit();
?>