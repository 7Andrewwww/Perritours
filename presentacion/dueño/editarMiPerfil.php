<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$mensaje = "";
$error = "";
$dueño = null;

$idDueño = $_SESSION["id"];

try {
    $dueño = new Dueño($idDueño);
    $dueño->consultar();
    
    if(empty($dueño->getNombre())) {
        throw new Exception("Perfil no encontrado");
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    $dueño = null;
}

if(isset($_POST['actualizar']) && $dueño !== null) {
    try {
        $dueño->setNombre($_POST['nombre']);
        $dueño->setCorreo($_POST['correo']);
        $dueño->setTelefono($_POST['telefono']);
        
        if($dueño->actualizarInformacion()) {
            $mensaje = "Perfil actualizado exitosamente";
        } else {
            $error = "Error al actualizar el perfil";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if(isset($_POST['actualizarClave']) && $dueño !== null) {
    try {
        if(empty($_POST['claveActual']) || empty($_POST['nuevaClave']) || empty($_POST['confirmarClave'])) {
            throw new Exception("Todos los campos de contraseña son requeridos");
        }
        
        if(!$dueño->verificarClave($_POST['claveActual'])) {
            throw new Exception("La contraseña actual es incorrecta");
        }
        
        if($_POST['nuevaClave'] != $_POST['confirmarClave']) {
            throw new Exception("Las nuevas contraseñas no coinciden");
        }
        
        if(strlen($_POST['nuevaClave']) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres");
        }
        
        $dueño->cambiarClave($_POST['nuevaClave']);
        $mensaje = "Contraseña actualizada exitosamente";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mi Perfil - Dueño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .hero-text {
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Editar Mi Perfil</h1>
            
            <?php if($mensaje != ""): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $mensaje ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if($error != ""): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if($dueño !== null): ?>
                <div class="card mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid blueviolet;">
                    <div style="border-bottom: 2px dashed blueviolet;" class="card-header display-6 text-light">
                        Mis Datos Personales
                    </div>
                    <div class="card-body text-light">
                        <form method="post" action="?pid=<?php echo base64_encode("presentacion/dueño/editarMiPerfil.php") ?>">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($dueño->getNombre()) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" 
                                       value="<?php echo htmlspecialchars($dueño->getCorreo()) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($dueño->getTelefono()) ?>" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="actualizar" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                        
                        <hr class="my-4" style="border-color: blueviolet;">
                        
                        <h5 class="mt-4">Cambiar Contraseña</h5>
                        <form method="post" action="?pid=<?php echo base64_encode("presentacion/dueño/editarMiPerfil.php") ?>">
                            <div class="mb-3">
                                <label for="claveActual" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" id="claveActual" name="claveActual" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nuevaClave" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="nuevaClave" name="nuevaClave" required>
                                <div class="form-text">Mínimo 8 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmarClave" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="confirmarClave" name="confirmarClave" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="actualizarClave" class="btn btn-warning">Cambiar Contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    Error al cargar tu perfil. Por favor, contacta al administrador.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            let telefono = document.getElementById('telefono').value;
            if(!/^\d+$/.test(telefono)) {
                alert('El teléfono solo debe contener números');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>