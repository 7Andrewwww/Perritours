<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idDueño = $_SESSION["id"];
$perros = array();
$mensaje = "";
$error = "";

try {
    // Verificar si se acaba de agregar un perro
    if(isset($_GET['exito'])) {
        $mensaje = $_GET['exito'];
    }
    
    // Consultar los perros del dueño
    $perros = Perro::consultarPorDueño($idDueño);
    
    if(empty($perros)) {
        $error = "No tienes perros registrados todavía.";
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Perros</title>
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
        .perro-card {
            background-color: rgba(35, 35, 74, 0.7);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .perro-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .perro-img {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
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
            <h1 class="display-6">Mis Perritos Registrados</h1>
            
            <?php if($mensaje): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $mensaje ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-info alert-dismissible fade show">
                    <?php echo $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="text-end mb-4">
                <a href="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>" 
                   class="btn btn-success">
                   <i class="bi bi-plus-circle"></i> Registrar Nuevo Perro
                </a>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach($perros as $perro): ?>
                <div class="col">
                    <div class="card h-100 perro-card text-light">
                        <?php if($perro->getFotoUrl()): ?>
                            <img src="<?php echo htmlspecialchars($perro->getFotoUrl()) ?>" 
                                 class="card-img-top perro-img" 
                                 alt="Foto de <?php echo htmlspecialchars($perro->getNombre()) ?>">
                        <?php else: ?>
                            <img src="assets/img/default-dog.png" 
                                 class="card-img-top perro-img" 
                                 alt="Perro sin foto">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($perro->getNombre()) ?></h5>
                            <p class="card-text">
                                <strong>Raza:</strong> <?php echo htmlspecialchars($perro->getRaza()) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>