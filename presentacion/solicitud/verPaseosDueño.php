<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idDueño = $_SESSION["id"];
$historialSolicitudes = SolicitudPaseo::consultarHistorialPorDueño($idDueño);
?>

<style>
.bg-purple {
    background-color: #6a0dad;
}
.card-solicitud {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(106, 13, 173, 0.3);
    border-radius: 10px;
    transition: all 0.3s ease;
}
.card-solicitud:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(106, 13, 173, 0.2);
    border-color: rgba(106, 13, 173, 0.5);
}
.perro-info, .paseador-info {
    background: rgba(106, 13, 173, 0.1);
}
.text-purple {
    color: #9c27b0;
}
.estado-pendiente {
    background-color: #ffc107;
    color: #000;
}
.estado-aceptado {
    background-color: #28a745;
    color: #fff;
}
.estado-rechazado {
    background-color: #dc3545;
    color: #fff;
}
.estado-cancelado {
    background-color: #6c757d;
    color: #fff;
}
.fecha-badge {
    background-color: #6A0DAD;
    font-size: 0.9rem;
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">Historial de Solicitudes</h2>
            
            <?php if (empty($historialSolicitudes)): ?>
                <div class="alert alert-info bg-dark text-white border-0">
                    <i class="fas fa-info-circle me-2"></i> No has realizado ninguna solicitud de paseo.
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($historialSolicitudes as $solicitud): 
                        $perro = $solicitud->getPerro();
                        $paseador = $solicitud->getPaseador();
                        $estado = $solicitud->getEstado()->getNombre();

                        $claseEstado = [
                            'Pendiente' => 'estado-pendiente',
                            'Aceptado' => 'estado-aceptado',
                            'Rechazado' => 'estado-rechazado',
                            'Cancelado' => 'estado-cancelado'
                        ][$estado] ?? 'bg-primary';
                    ?>
                    <div class="col-lg-6">
                        <div class="card-solicitud p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-purple">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?= date('d M Y', strtotime($solicitud->getFecha())) ?>
                                    <i class="far fa-clock ms-2 me-1"></i>
                                    <?= date('H:i', strtotime($solicitud->getHora())) ?>
                                </span>
                                <span class="badge <?= $claseEstado ?>">
                                    <?= htmlspecialchars($estado) ?>
                                </span>
                            </div>
                            
                            <div class="perro-info mb-3 p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-dog text-purple me-2"></i>
                                    <h5 class="text-white mb-0"><?= htmlspecialchars($perro->getNombre()) ?></h5>
                                </div>
                                <p class="mb-0 text-light">
                                    <i class="fas fa-paw text-purple me-2"></i>
                                    <strong>Raza:</strong> <?= htmlspecialchars($perro->getRaza() ?: 'No especificada') ?>
                                </p>
                            </div>
                            
                            <div class="paseador-info p-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-purple me-2"></i>
                                    <h6 class="text-white mb-0">Información del Paseador</h6>
                                </div>
                                <p class="mb-1 text-light">
                                    <i class="fas fa-signature text-purple me-2"></i>
                                    <strong>Nombre:</strong> <?= htmlspecialchars($paseador->getNombre()) ?>
                                </p>
                                <p class="mb-0 text-light">
                                    <i class="fas fa-phone text-purple me-2"></i>
                                    <strong>Teléfono:</strong> <?= htmlspecialchars($paseador->getTelefono() ?: 'No disponible') ?>
                                </p>
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