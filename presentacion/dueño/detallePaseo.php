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
$fechaHoraPaseo = strtotime($paseo->getFecha() . ' ' . $paseo->getHora());
$esPaseoPasado = ($fechaHoraPaseo !== false) && ($fechaHoraPaseo < time());
$calificacionExistente = $esPaseoPasado ? $paseo->obtenerCalificacionPaseador($paseo->getPaseador()->getId()) : null;
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
    cursor: help;
    position: relative;
}

.rating-badge:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    white-space: nowrap;
    z-index: 100;
    margin-bottom: 5px;
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

.rating-stars {
    display: flex;
    justify-content: center;
    direction: rtl;
    margin-bottom: 20px;
}

.rating-stars input {
    display: none;
}

.rating-stars label {
    font-size: 2rem;
    color: #444;
    cursor: pointer;
    transition: color 0.2s;
    margin: 0 5px;
}

.rating-stars input:checked ~ label,
.rating-stars input:hover ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}

.comentario-box {
    margin-top: 20px;
}

.comentario-box textarea {
    width: 100%;
    min-height: 100px;
    padding: 10px;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid #6A0DAD;
    color: white;
    resize: vertical;
}

.comentario-box textarea:focus {
    outline: none;
    border-color: #9C27B0;
    box-shadow: 0 0 0 2px rgba(156, 39, 176, 0.3);
}

.estado-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
}

.estado-completado {
    background-color: #4CAF50;
    color: white;
}

.estado-pendiente {
    background-color: #FFC107;
    color: #333;
}

.pet-avatar {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #6A0DAD;
}

.detail-label {
    color: #B388EB;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.detail-value {
    color: white;
    font-size: 1.1rem;
}

.detail-card {
    border-radius: 15px;
    margin-bottom: 20px;
}

.glass-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid #6A0DAD;
    color: white;
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: #9C27B0;
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(106, 13, 173, 0.25);
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
                        <p class="text-light mb-1"><?php echo htmlspecialchars($paseo->getPerro()->getRaza()) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass p-4 detail-card">
                <div class="text-center mb-4">
                    <img src="<?php echo $paseo->getPaseador()->getFotoUrl() ?? 'img/default-profile.png' ?>" 
                         class="pet-avatar mb-3">
                    <h3 class="text-light"><?php echo $paseo->getPaseador()->getNombre()?></h3>
                    <p class="text-light mb-1">Paseador</p>
                </div>

                <?php if ($esPaseoPasado): ?>
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-calificar text-light" data-bs-toggle="modal" data-bs-target="#modalCalificar">
                            <i class="fas fa-star me-2"></i>
                            <?php echo ($calificacionExistente && $calificacionExistente['puntuacion']) ? 'Actualizar calificación' : 'Calificar paseador' ?>
                        </button>
                        <?php if ($calificacionExistente && $calificacionExistente['puntuacion']): ?>
                            <div class="text-center mt-2">
                                <span class="rating-badge" title="<?php echo htmlspecialchars($calificacionExistente['comentario'] ?? 'Sin comentario') ?>">
                                    Tu calificación: <?php echo $calificacionExistente['puntuacion'] ?> estrellas
                                </span>
                            </div>
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
                    
                    <div class="comentario-box">
                        <label for="comentario" class="text-light mb-2">Comentario (obligatorio)</label>
                        <textarea id="comentario" name="comentario" placeholder="Escribe tu experiencia con este paseador..." required><?php echo ($calificacionExistente && $calificacionExistente['comentario']) ? htmlspecialchars($calificacionExistente['comentario']) : '' ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formCalificar').addEventListener('submit', function(e) {
    const comentario = document.getElementById('comentario').value.trim();
    if (!comentario) {
        e.preventDefault();
        alert('Por favor escribe un comentario antes de enviar la calificación');
        document.getElementById('comentario').focus();
    }
});
</script>
</body>