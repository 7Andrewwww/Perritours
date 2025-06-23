<?php
if (!isset($_SESSION["rol"])) {
    header("Location: ?pid=" . base64_encode("presentacion/inicio.php"));
    exit();
}
?>

<style>
.card-perro {
    background: rgba(50, 30, 80, 0.85);
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(120, 50, 220, 0.3);
    backdrop-filter: blur(10px);
    color: #f0e6ff;
    transition: transform 0.3s ease;
    margin-bottom: 20px;
}

.card-perro:hover {
    transform: translateY(-5px);
}

.perro-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}

.raza-badge {
    background-color: #8A4FCF;
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.85em;
    display: inline-block;
    margin-top: 5px;
}

.dueño-info {
    font-size: 0.9em;
    color: #B388EB;
    margin-top: 10px;
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/admin/menuAdmin.php");
    ?>

    <div class="container py-5">
        <h1 class="text-center mb-4 text-light">Nuestros Perros</h1>
        
        <div class="row">
            <?php
            $perros = Perro::consultarTodos();
            foreach($perros as $perro) {
            ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card card-perro h-100">
                    <img src="<?php echo htmlspecialchars($perro->getFotoUrl()) ?>" 
                         class="perro-img" 
                         alt="<?php echo htmlspecialchars($perro->getNombre()) ?>"
                         onerror="this.src='img/default-dog.png'">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($perro->getNombre()) ?></h5>
                        <span class="raza-badge"><?php echo htmlspecialchars($perro->getRaza()) ?></span>
                        <div class="dueño-info">
                            Dueño: <?php echo htmlspecialchars($perro->getDueño()->getNombre()) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>