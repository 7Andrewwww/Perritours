<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
?>
<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/paseador/menuPaseador.php");
    $id = $_SESSION["id"];
    $paseador = new Paseador($id);
    $paseador->consultar();
    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">¡Hola, <?php echo $paseador->getNombre() ?>!</h1>
            <img src="<?php echo $paseador->getFotoUrl() ?>" class="rounded-circle"
                style="width: 100%; max-width: 150px;" alt="Foto de perfil">

            <div class="card m-3 mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid blueviolet;">
                 <div style="border-bottom: 2px dashed blueviolet;" class="card-header display-6 text-light">
                    Información
                </div>>
                <div class="card-body text-light">
                    <p class="card-text lead"><strong>Rol: </strong>Paseador</p>
                    <p class="lead"><strong>Teléfono: </strong><?php echo $paseador->getTelefono() ?></p>
                    <p class="lead"><strong>Correo: </strong><?php echo $paseador->getCorreo() ?></p>
                    <p class="lead"><strong>Estado: </strong><?php echo $paseador->getEstado()->getEstado() ?></p>
                    <p class="lead" style="color: #4CAF50">Gestiona tus paseos y perritos en el <i>Menú</i>.</p>
                </div>
            </div>
        </div>
    </div>
</body>