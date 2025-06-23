<?php
// Verificar rol de administrador
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
?>

<style>
.glass {
    background: rgba(50, 30, 80, 0.85);
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(120, 50, 220, 0.3);
    backdrop-filter: blur(10px);
    color: #f0e6ff;
}

.table-custom {
    background-color: #2A1A40;
    border-collapse: collapse;
    color: #f5f0ff;
}

.table-custom th {
    background-color: #6A0DAD; 
    color: #ffffff;
    border-bottom: 2px solid #B388EB;
    text-align: center;
}

.table-custom td {
    background-color: #3D2B56; 
    color: #f5f0ff;
    border-top: 1px solid #6A0DAD;
    vertical-align: middle;
}

.table-custom tr:hover {
    background-color: #5C4B89; 
    transition: background-color 0.3s ease;
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/admin/menuAdmin.php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseadores</h1>

            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paseadores = Paseador::consultarTodos();
                        foreach($paseadores as $paseador) {
                            $estadoClass = '';
                            switch($paseador->getEstado()->getIdEstado()) {
                                case 1: $estadoClass = 'text-success'; break;
                                case 2: $estadoClass = 'text-danger'; break;
                                case 3: $estadoClass = 'text-warning'; break;
                                case 4: $estadoClass = 'text-info'; break;
                            }
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($paseador->getFotoUrl()) ?>"
                                     class="rounded-circle"
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="Foto paseador"
                                     onerror="this.src='img/default-profile.png'">
                            </td>
                            <td><?php echo htmlspecialchars($paseador->getNombre()) ?></td>
                            <td><?php echo htmlspecialchars($paseador->getTelefono()) ?></td>
                            <td><?php echo htmlspecialchars($paseador->getCorreo()) ?></td>
                            <td class="<?php echo $estadoClass ?>">
                                <?php echo htmlspecialchars($paseador->getEstado()->getEstado()) ?>
                            </td>
                            <td>
                                <a href="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>&idPaseador=<?php echo $paseador->getId() ?>" 
                                   class="btn btn-sm btn-primary" 
                                   title="Editar paseador">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
