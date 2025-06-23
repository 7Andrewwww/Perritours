<?php
$idDueño = $_SESSION['id'] ?? null;

if (!$idDueño) {
    header("Location: ?pid=" . base64_encode("presentacion/error.php"));
    exit();
}

$historialPaseos = Paseo::consultarHistorialPaseosDueño($idDueño);
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

.tarifa-badge {
    background-color: #4CAF50;
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.85em;
}

.btn-action {
    margin: 0 3px;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

.no-data {
    color: #B388EB;
    font-style: italic;
    padding: 20px;
    text-align: center;
}

.historial-badge {
    background-color: #9C27B0;
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.85em;
}

.rating-badge {
    background-color: #FFC107;
    color: #333;
    border-radius: 12px;
    padding: 3px 8px;
    font-size: 0.85em;
}

.btn-calificar {
    background-color: #6A0DAD;
    border: none;
    transition: all 0.3s;
}

.btn-calificar:hover {
    background-color: #7B1FA2;
    transform: translateY(-2px);
}
</style>
<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
<div class="text-center py-3 hero-text">
    <div class="container glass py-3">
        <h1 class="display-6">Mi Historial de Paseos</h1>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-custom table-hover text-light">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tarifa</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historialPaseos)): ?>
                        <tr>
                            <td colspan="5" class="no-data">No tienes paseos en tu historial</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($historialPaseos as $paseo): 
                            $fechaFormateada = date("d/m/Y", strtotime($paseo->getFecha()));
                            $horaFormateada = date("H:i", strtotime($paseo->getHora()));
                            
                            $esPaseoPasado = strtotime($paseo->getFecha() . ' ' . $paseo->getHora()) < time();
                            $calificacionExistente = $esPaseoPasado ? $paseo->obtenerCalificacionDueño($idDueño) : null;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fechaFormateada) ?></td>
                            <td><?php echo htmlspecialchars($horaFormateada) ?></td>
                            <td><span class="tarifa-badge">$<?php echo number_format($paseo->getTarifa(), 2) ?></span></td>
                            <td>
                                <?php if ($esPaseoPasado): ?>
                                    <span class="historial-badge">Completado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Programado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?pid=<?php echo base64_encode("presentacion/dueño/detallePaseo.php") ?>&idPaseo=<?php echo $paseo->getIdPaseo() ?>" 
                                   class="btn btn-sm btn-primary btn-action" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($esPaseoPasado): ?>
                                    <?php if ($calificacionExistente && $calificacionExistente['puntuacion']): ?>
                                        <span class="rating-badge" title="Ya calificado">
                                            <i class="fas fa-star"></i> <?php echo $calificacionExistente['puntuacion'] ?>
                                        </span>
                                        <?php if ($calificacionExistente['comentario']): ?>
                                            <span class="comentario-badge" title="Comentario enviado">
                                                <i class="fas fa-comment"></i>
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="?pid=<?php echo base64_encode("presentacion/dueño/detallePaseo.php") ?>&idPaseo=<?php echo $paseo->getIdPaseo() ?>" 
                                           class="btn btn-sm btn-info btn-action" 
                                           title="Calificar">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body> 