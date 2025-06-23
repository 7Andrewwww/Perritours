<?php
$idPaseo = $_GET['idPaseo'] ?? null;
if (!$idPaseo) {
    header("Location: ?pid=" . base64_encode("presentacion/paseito/historialMisPaseos.php"));
    exit();
}

$idDueño = $_SESSION['id'] ?? null;
if (!$idDueño) {
    header("Location: ?pid=" . base64_encode("presentacion/error.php"));
    exit();
}

// Consultar los detalles del paseo
$paseo = new Paseo($idPaseo);
$paseo->consultar();

// Formatear fechas y horas
$fechaFormateada = date("d/m/Y", strtotime($paseo->getFecha()));
$horaFormateada = date("H:i", strtotime($paseo->getHora()));

// Verificar si es un paseo pasado y obtener calificación
$esPaseoPasado = strtotime($paseo->getFecha() . ' ' . $paseo->getHora()) < time();
$calificacionExistente = $esPaseoPasado ? $paseo->obtenerCalificacionDueño($idDueño) : null;
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
<div class="container py-4">
    <h1 class="text-light mb-4">Detalles del Paseo</h1>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="glass p-4 detail-card">
                <div class="detail-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-light mb-0">Información del Paseo</h3>
                        <span class="estado-badge <?php echo $esPaseoPasado ? 'estado-completado' : 'estado-pendiente' ?>">
                            <?php echo $esPaseoPasado ? 'COMPLETADO' : 'PROGRAMADO' ?>
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="detail-label">Fecha</p>
                        <p class="detail-value"><?php echo htmlspecialchars($fechaFormateada) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="detail-label">Hora</p>
                        <p class="detail-value"><?php echo htmlspecialchars($horaFormateada) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="detail-label">Duración</p>
                        <p class="detail-value">60 minutos</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="detail-label">Tarifa</p>
                        <p class="detail-value"><span class="tarifa-badge">$<?php echo number_format($paseo->getTarifa(), 2) ?></span></p>
                    </div>
                </div>
            </div>

            <div class="glass p-4 detail-card mt-4">
    <h4 class="text-light mb-3"><i class="fas fa-dog me-2"></i>Información del Perro</h4>
    <div class="d-flex align-items-center">
        <img src="<?php echo htmlspecialchars($paseo->getPerro()->getFotoUrl() ?? 'assets/img/default-pet.png') ?>" 
             class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
        <div>
            <h5 class="text-light mb-1"><?php echo htmlspecialchars($paseo->getPerro()->getNombre()) ?></h5>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($paseo->getPerro()->getRaza()) ?></p>
        </div>
    </div>
</div>
        </div>

        <div class="col-lg-4">
            <div class="glass p-4 detail-card">
                <div class="text-center mb-4">
                    <img src="<?php echo htmlspecialchars($paseo->getPaseador()->getFotoUrl() ?? 'img/default-profile.png') ?>" 
                         class="pet-avatar mb-3" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                    <h3 class="text-light"><?php echo htmlspecialchars($paseo->getPaseador()->getNombre()) ?></h3>
                    <p class="text-muted">Paseador</p>
                </div>

                <div class="mb-3">
                    <p class="detail-label">Contacto</p>
                    <p class="detail-value"><?php echo htmlspecialchars($paseo->getPaseador()->getTelefono()) ?></p>
                </div>

                <?php if ($esPaseoPasado): ?>
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-calificar text-light" data-bs-toggle="modal" data-bs-target="#modalCalificar">
                            <i class="fas fa-star me-2"></i>
                            <?php echo ($calificacionExistente && $calificacionExistente['puntuacion']) ? 'Actualizar calificación' : 'Calificar paseador' ?>
                        </button>
                        <?php if ($calificacionExistente && $calificacionExistente['puntuacion']): ?>
                            <div class="text-center mt-2">
                                <span class="rating-badge">
                                    Tu calificación actual: <?php echo $calificacionExistente['puntuacion'] ?> estrellas
                                </span>
                            </div>
                            <?php if ($calificacionExistente['comentario']): ?>
                                <div class="comentario-box mt-3">
                                    <p class="detail-label mb-2">Tu comentario:</p>
                                    <p class="detail-value"><?php echo htmlspecialchars($calificacionExistente['comentario']) ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCalificar" tabindex="-1" aria-labelledby="modalCalificarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass" style="background: rgba(42, 26, 64, 0.95); border: 1px solid #6A0DAD;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-light" id="modalCalificarLabel">Calificar Paseador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCalificar" action="?pid=<?php echo base64_encode("presentacion/dueño/guardarCalificacion.php") ?>" method="POST">
                    <input type="hidden" name="idPaseo" value="<?php echo $paseo->getIdPaseo() ?>">
                    
                    <div class="text-center mb-4">
                        <h5 class="text-light mb-3">¿Cómo calificarías a este paseador?</h5>
                        <div class="rating-stars">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i ?>" name="puntuacion" value="<?php echo $i ?>" 
                                    <?php echo ($calificacionExistente && $calificacionExistente['puntuacion'] == $i) ? 'checked' : '' ?>>
                                <label for="star<?php echo $i ?>" class="star-label">
                                    <i class="fas fa-star"></i>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentario" class="form-label text-light">Comentario (opcional)</label>
                        <textarea class="form-control glass-input" id="comentario" name="comentario" rows="3" 
                            placeholder="¿Cómo fue tu experiencia con este paseador?"><?php echo ($calificacionExistente && $calificacionExistente['comentario']) ? htmlspecialchars($calificacionExistente['comentario']) : '' ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body> 