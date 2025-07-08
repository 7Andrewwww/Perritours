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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paseador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .hero-text {
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .estado-btn.active {
            font-weight: bold;
            transform: scale(1.05);
        }
    </style>
</head>
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
                            
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Datos</button>
                        </form>
                        
                        <hr class="my-4" style="border-color: blueviolet;">

                        <div class="mb-3 border-top pt-3">
                            <h5>Estado Actual del Paseador</h5>
                            <div id="estadoContainer" class="d-flex flex-wrap gap-2 mt-2">
                                <button type="button" class="btn estado-btn <?php echo $paseador->getEstado()->getIdEstado() == 1 ? 'btn-success active' : 'btn-outline-success' ?>" data-estado="1">Activo</button>
                                <button type="button" class="btn estado-btn <?php echo $paseador->getEstado()->getIdEstado() == 2 ? 'btn-secondary active' : 'btn-outline-secondary' ?>" data-estado="2">Inactivo</button>
                                <button type="button" class="btn estado-btn <?php echo $paseador->getEstado()->getIdEstado() == 3 ? 'btn-warning active' : 'btn-outline-warning' ?>" data-estado="3">Suspendido</button>
                                <button type="button" class="btn estado-btn <?php echo $paseador->getEstado()->getIdEstado() == 4 ? 'btn-info active' : 'btn-outline-info' ?>" data-estado="4">Vacacionando</button>
                                <button type="button" class="btn estado-btn <?php echo $paseador->getEstado()->getIdEstado() == 5 ? 'btn-danger active' : 'btn-outline-danger' ?>" data-estado="5">Inhabilitado</button>
                            </div>
                            <div id="estadoFeedback" class="mt-2"></div>
                        </div>
                        
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
    <?php if ($paseador !== null): ?>
    <script>
    $(document).ready(function(){
        // Función para cambiar el estado
        function cambiarEstado(nuevoEstado) {
            var idPaseador = <?php echo $paseador->getId(); ?>;
            var estadoContainer = $("#estadoContainer");
            var feedback = $("#estadoFeedback");
            
            // Mostrar feedback visual de carga
            feedback.html('<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Actualizando estado...</div>');
            
            // Deshabilitar botones temporalmente
            estadoContainer.find('.estado-btn').prop('disabled', true);
            
            $.ajax({
                url: "actualizarEstadoPaseador.php",
                method: "GET",
                data: {
                    idPaseador: idPaseador,
                    idNuevoEstado: nuevoEstado
                },
                success: function(response){
                    // Actualizar la interfaz
                    estadoContainer.find('.estado-btn').removeClass('active btn-success btn-secondary btn-warning btn-info btn-danger')
                        .addClass('btn-outline-success btn-outline-secondary btn-outline-warning btn-outline-info btn-outline-danger');
                    
                    var botonActual = estadoContainer.find('[data-estado="' + nuevoEstado + '"]');
                    var claseActiva = '';
                    
                    switch(nuevoEstado) {
                        case '1': claseActiva = 'btn-success'; break;
                        case '2': claseActiva = 'btn-secondary'; break;
                        case '3': claseActiva = 'btn-warning'; break;
                        case '4': claseActiva = 'btn-info'; break;
                        case '5': claseActiva = 'btn-danger'; break;
                    }
                    
                    botonActual.removeClass('btn-outline-' + claseActiva.split('-')[1])
                              .addClass(claseActiva + ' active');
                    
                    // Mostrar feedback positivo
                    feedback.html('<div class="alert alert-success">Estado actualizado correctamente' + response + '</div>');
                    
                    // Ocultar feedback después de 3 segundos
                    setTimeout(function(){
                        feedback.empty();
                    }, 3000);
                },
                error: function(xhr, status, error){
                    // Mostrar feedback de error
                    feedback.html('<div class="alert alert-danger">Error al actualizar el estado: ' + error + '</div>');
                },
                complete: function(){
                    // Rehabilitar botones
                    estadoContainer.find('.estado-btn').prop('disabled', false);
                }
            });
        }
        
        // Manejar clic en los botones de estado
        $(".estado-btn").click(function(){
            if(!$(this).hasClass('active')) {
                var nuevoEstado = $(this).data('estado');
                cambiarEstado(nuevoEstado);
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>