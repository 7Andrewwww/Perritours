<?php
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$mensaje = "";
$error = "";

if(isset($_POST['idPaseador']) && !empty($_POST['idPaseador'])) {
    try {
        $paseador = new Paseador($_POST['idPaseador']);
        
        $paseador->consultar();
        
        if($paseador->eliminar()) {
            $_SESSION['mensaje'] = "Paseador eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el paseador";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
} else {
    $_SESSION['error'] = "No se especificó un paseador para eliminar";
}

header("Location: ?pid=" . base64_encode("presentacion/paseador/consultarPaseador.php"));
exit();
?>