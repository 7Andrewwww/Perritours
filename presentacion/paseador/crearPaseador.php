<?php
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$mensaje = "";
$error = "";

if(isset($_POST['crear'])) {
    try {
        $foto_url = 'img/default-profile.png';
        
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
                    $foto_url = $rutaCompleta;
                } else {
                    throw new Exception("Error al subir la imagen");
                }
            } else {
                throw new Exception("Solo se permiten archivos JPG, JPEG, PNG o GIF");
            }
        }
        
        $paseador = new Paseador(
            $_POST['id'],
            $_POST['nombre'],
            $_POST['correo'],
            $_POST['clave'],
            $_POST['telefono'],
            $foto_url,
            new Estado($_POST['estado'], "")
            );
        
        if($paseador->crear()) {
            $mensaje = "Paseador creado exitosamente";
        } else {
            $error = "Error al crear el paseador";
        }
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
            <h1 class="display-6">Crear Nuevo Paseador</h1>
            
            <?php if($mensaje != ""): ?>
                <div class="alert alert-success"><?php echo $mensaje ?></div>
            <?php endif; ?>
            
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?php echo $error ?></div>
            <?php endif; ?>
            
            <div class="card mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid blueviolet;">
                <div style="border-bottom: 2px dashed blueviolet;" class="card-header display-6 text-light">
                    Datos del Paseador
                </div>
                <div class="card-body text-light">
                    <form method="post" action="?pid=<?php echo base64_encode("presentacion/paseador/crearPaseador.php") ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID del Paseador</label>
                            <input type="number" class="form-control" id="id" name="id" 
                                   value="<?php echo Paseador::siguienteId(); ?>" min="1" required>
                            <div class="form-text">El siguiente ID disponible</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Paseador</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos aceptados: JPG, PNG, GIF</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                                <option value="3">Suspendido</option>
                                <option value="4">Vacacionando</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="crear" class="btn btn-primary">Crear Paseador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>