<?php
// Verificar sesión de paseador
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Obtener ID del paseador desde sesión
$idPaseador = $_SESSION["id"];
$solicitudesPendientes = SolicitudPaseo::consultarPendientesPorPaseador($idPaseador);
?>

<style>
.card-solicitud {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    border: 1px solid rgba(106, 13, 173, 0.5);
    transition: all 0.3s;
    margin-bottom: 1.5rem;
}

.card-solicitud:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(106, 13, 173, 0.2);
}

.perro-info {
    background: rgba(106, 13, 173, 0.1);
    border-radius: 0.5rem;
    padding: 0.75rem;
}

.btn-aceptar {
    background-color: #28a745;
    border: none;
    transition: all 0.3s;
}

.btn-aceptar:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.btn-rechazar {
    background-color: #dc3545;
    border: none;
    transition: all 0.3s;
}

.btn-rechazar:hover {
    background-color: #c82333;
    transform: scale(1.05);
}

.fecha-badge {
    background-color: #6A0DAD;
    font-size: 0.9rem;
}

.contacto-info {
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.contacto-info i {
    margin-right: 0.5rem;
    color: #6A0DAD;
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/paseador/menuPaseador.php");
    ?>
    
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">Solicitudes de Paseo Pendientes</h2>
            
            <?php if (empty($solicitudesPendientes)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No tienes solicitudes de paseo pendientes en este momento.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($solicitudesPendientes as $solicitud): 
                        $perro = $solicitud->getPerro();
                        $dueño = $solicitud->getDueño();
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="card-solicitud p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge fecha-badge">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?= date('d M Y', strtotime($solicitud->getFecha())) ?> 
                                    <i class="far fa-clock ms-2 me-1"></i>
                                    <?= date('H:i', strtotime($solicitud->getHora())) ?>
                                </span>
                            </div>
                            
                            <div class="perro-info mb-3">
                                <h5 class="text-white">
                                    <i class="fas fa-dog"></i> <?= htmlspecialchars($perro->getNombre()) ?>
                                </h5>
                                <p class="mb-1 text-muted">
                                    <strong>Raza:</strong> <?= htmlspecialchars($perro->getRaza()) ?>
                                </p>
                            </div>
                            
                            <div class="contacto-info">
                                <p class="mb-1">
                                    <i class="fas fa-user"></i> 
                                    <strong>Dueño:</strong> <?= htmlspecialchars($dueño->getNombre()) ?>
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-phone"></i> 
                                    <strong>Contacto:</strong> <?= htmlspecialchars($dueño->getTelefono()) ?>
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <form action="?pid=<?= base64_encode("presentacion/solicitud/procesarAceptar.php") ?>" method="post" class="w-50 me-2">
                                    <input type="hidden" name="id_solicitud" value="<?= $solicitud->getIdSolicitud() ?>">
                                    <button type="submit" class="btn btn-aceptar btn-block text-white py-2">
                                        <i class="fas fa-check me-2"></i>Aceptar
                                    </button>
                                </form>
                                
                                <form action="?pid=<?= base64_encode("presentacion/solicitud/procesarRechazar.php") ?>" method="post" class="w-50">
                                    <input type="hidden" name="id_solicitud" value="<?= $solicitud->getIdSolicitud() ?>">
                                    <button type="submit" class="btn btn-rechazar btn-block text-white py-2">
                                        <i class="fas fa-times me-2"></i>Rechazar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>