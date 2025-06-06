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
                                <button class="btn btn-sm btn-danger btn-eliminar" 
                                        data-id="<?php echo $paseador->getId() ?>" 
                                        data-nombre="<?php echo htmlspecialchars($paseador->getNombre()) ?>"
                                        title="Eliminar paseador">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmarEliminacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al paseador <strong id="nombrePaseador"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar" method="post" action="?pid=<?php echo base64_encode("presentacion/paseador/eliminarPaseador.php") ?>">
                        <input type="hidden" name="idPaseador" id="idPaseador">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const botonesEliminar = document.querySelectorAll('.btn-eliminar');
        const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacion'));
        const nombrePaseador = document.getElementById('nombrePaseador');
        const idPaseador = document.getElementById('idPaseador');
        const formEliminar = document.getElementById('formEliminar');
        
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function() {
                nombrePaseador.textContent = this.getAttribute('data-nombre');
                idPaseador.value = this.getAttribute('data-id');
                modal.show();
            });
        });

        formEliminar.addEventListener('submit', function(e) {
        });
    });
    </script>
</body>
