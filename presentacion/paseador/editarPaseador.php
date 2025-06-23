<?php
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$mensaje = "";
$error = "";
$paseador = null;

if(isset($_GET['idPaseador']) && !empty($_GET['idPaseador'])) {
    $paseador = new Paseador($_GET['idPaseador']);
    
    try {
        $paseador->consultar();

        if(empty($paseador->getNombre())) {
            throw new Exception("Paseador no encontrado");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        $paseador = null;
    }
} else {
    $error = "No se ha especificado un paseador para editar";
}

if(isset($_POST['actualizar']) && $paseador !== null) {
    try {
        $foto_url = $paseador->getFotoUrl();

        if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $directorio = "img/paseadores/";
            if(!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            $nombreArchivo = uniqid() . '_' . basename($_FILES['foto']['name']);
            $rutaCompleta = $directorio . $nombreArchivo;
            
            $tipoArchivo = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
            $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');
            
            if(in_array($tipoArchivo, $extensionesPermitidas)) {
                if(move_uploaded_file($_FILES['foto']['tmp_name'], $rutaCompleta)) {

                    if($foto_url != 'img/default-profile.png' && file_exists($foto_url)) {
                        unlink($foto_url);
                    }
                    $foto_url = $rutaCompleta;
                } else {
                    throw new Exception("Error al subir la imagen");
                }
            } else {
                throw new Exception("Solo se permiten archivos JPG, JPEG, PNG o GIF");
            }
        }

        $paseador->setNombre($_POST['nombre']);
        $paseador->setCorreo($_POST['correo']);
        $paseador->setTelefono($_POST['telefono']);
        $paseador->setFotoUrl($foto_url);
        $paseador->setEstado(new Estado($_POST['estado'], ""));
        
        if($paseador->actualizar()) {
            $mensaje = "Paseador actualizado exitosamente";
        } else {
            $error = "Error al actualizar el paseador";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if(isset($_POST['actualizarClave']) && $paseador !== null) {
    try {
        if(empty($_POST['nuevaClave']) || empty($_POST['confirmarClave'])) {
            throw new Exception("Ambos campos de contraseña son requeridos");
        }
        
        if($_POST['nuevaClave'] != $_POST['confirmarClave']) {
            throw new Exception("Las contraseñas no coinciden");
        }
        
        $paseador->actualizarClave($_POST['nuevaClave']);
        $mensaje = "Contraseña actualizada exitosamente";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<body>
    <?php
    include ("presentacion/fondo.php");
    include ("presentacion/boton.php");
    include("presentacion/admin/menuAdmin.php");
    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Editar Paseador</h1>
            
            <?php if($mensaje != ""): ?>
                <div class="alert alert-success"><?php echo $mensaje ?></div>
            <?php endif; ?>
            
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?php echo $error ?></div>
            <?php endif; ?>
            
            <?php if($paseador !== null): ?>
                <div class="card mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid blueviolet;">
                    <div style="border-bottom: 2px dashed blueviolet;" class="card-header display-6 text-light">
                        Datos del Paseador
                    </div>
                    <div class="card-body text-light">
                        <form method="post" action="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>&idPaseador=<?php echo $paseador->getId() ?>" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $paseador->getId() ?>">
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paseador->getNombre()) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($paseador->getCorreo()) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paseador->getTelefono()) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto del Paseador</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div class="form-text">Formatos aceptados: JPG, PNG, GIF. Dejar en blanco para mantener la actual.</div>
                                <?php if($paseador->getFotoUrl()): ?>
                                    <img src="<?php echo htmlspecialchars($paseador->getFotoUrl()) ?>" class="img-thumbnail mt-2" style="max-width: 150px;">
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="1" <?php echo $paseador->getEstado()->getIdEstado() == 1 ? 'selected' : '' ?>>Activo</option>
                                    <option value="2" <?php echo $paseador->getEstado()->getIdEstado() == 2 ? 'selected' : '' ?>>Inactivo</option>
                                    <option value="3" <?php echo $paseador->getEstado()->getIdEstado() == 3 ? 'selected' : '' ?>>Suspendido</option>
                                    <option value="4" <?php echo $paseador->getEstado()->getIdEstado() == 4 ? 'selected' : '' ?>>Vacacionando</option>
                                    <option value="4" <?php echo $paseador->getEstado()->getIdEstado() == 5 ? 'selected' : '' ?>>Inhabilitado</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Datos</button>
                        </form>
                        
                        <hr class="my-4" style="border-color: blueviolet;">
                        
                        <h5 class="mt-4">Cambiar Contraseña</h5>
                        <form method="post" action="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>&idPaseador=<?php echo $paseador->getId() ?>">
                            <div class="mb-3">
                                <label for="nuevaClave" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="nuevaClave" name="nuevaClave">
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmarClave" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmarClave" name="confirmarClave">
                            </div>
                            
                            <button type="submit" name="actualizarClave" class="btn btn-warning">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No se puede mostrar el formulario de edición. Por favor, seleccione un paseador válido desde la lista.
                </div>
                <a href="?pid=<?php echo base64_encode("presentacion/paseador/consultarPaseador.php") ?>" class="btn btn-primary mt-3">Volver a la lista de paseadores</a>
            <?php endif; ?>
        </div>
    </div>
</body>