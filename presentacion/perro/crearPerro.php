<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idDueño = $_SESSION["id"];
$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_perro"])) {
    try {
        // Validar y obtener datos del formulario
        $id_perro = (int)$_POST["id_perro"];
        $nombre = trim($_POST["nombre"] ?? '');
        $raza = trim($_POST["raza"] ?? '');
        
        // Validaciones básicas
        if (empty($id_perro) || $id_perro <= 0) {
            throw new Exception("Debe ingresar un ID válido para el perro");
        }
        
        if (empty($nombre)) {
            throw new Exception("El nombre del perro es requerido");
        }
        
        if (strlen($nombre) > 45) {
            throw new Exception("El nombre no puede exceder 45 caracteres");
        }
        
        // Manejo de la imagen
        $foto_url = "assets/img/default-dog.png"; // Valor por defecto
        
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
            $directorio = "assets/img/perros/";
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            $extension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
            $nombreArchivo = $id_perro . '_' . uniqid() . '.' . $extension;
            $rutaCompleta = $directorio . $nombreArchivo;
            
            // Validar tipo de archivo
            $extensionesPermitidas = ["jpg", "jpeg", "png", "gif"];
            if (in_array($extension, $extensionesPermitidas)) {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaCompleta)) {
                    $foto_url = $rutaCompleta;
                } else {
                    throw new Exception("Error al subir la imagen del perro");
                }
            } else {
                throw new Exception("Solo se permiten imágenes JPG, JPEG, PNG o GIF");
            }
        }
        
        // Crear el objeto Perro
        $perro = new Perro(
            $id_perro,
            $nombre,
            $raza,
            $foto_url,
            new Dueño($idDueño)
            );
        
        // Insertar el perro en la base de datos
        if ($perro->insertar()) {
            $mensaje = "Perro registrado exitosamente con ID: " . $id_perro;
            header("Location: ?pid=" . base64_encode("presentacion/perro/consultarMisPerros.php") . "&exito=" . urlencode($mensaje));
            exit();
        } else {
            throw new Exception("Error al registrar el perro. Verifica que el ID no esté en uso.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Registrar Nuevo Perro</h1>
            
            <?php if($error != ""): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="card mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid blueviolet;">
                <div style="border-bottom: 2px dashed blueviolet;" class="card-header display-6 text-light">
                    Datos del Perro
                </div>
                <div class="card-body text-light">
                    <form method="post" action="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="id_perro" class="form-label">ID del Perro</label>
                            <input type="number" class="form-control" id="id_perro" name="id_perro" min="1" required
                                   value="<?php echo Perro::siguienteId(); ?>">
                            <div class="form-text">Debe ser un número único</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Perro</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="45">
                        </div>
                        
                        <div class="mb-3">
                            <label for="raza" class="form-label">Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" required maxlength="45">
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Perro (Opcional)</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos aceptados: JPG, PNG, GIF</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="crear" class="btn btn-primary">Registrar Perro</button>
                            <a href="?pid=<?php echo base64_encode("presentacion/perro/consultarMisPerros.php") ?>" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>