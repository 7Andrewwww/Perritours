<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$idDueño = $_SESSION["id"];
$paseadoresActivos = Paseador::consultarPaseadoresActivos();
$paseadoresConExperiencia = Paseador::consultarPaseadoresConExperiencia($idDueño);
?>
    <style>
        .glass-dueño {
            background: rgba(50, 30, 80, 0.85);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(120, 50, 220, 0.3);
            backdrop-filter: blur(10px);
            color: #f0e6ff;
        }

        .paseador-card {
            background: linear-gradient(135deg, rgba(70, 40, 110, 0.9) 0%, rgba(50, 25, 90, 0.9) 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(140, 80, 240, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .paseador-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(140, 80, 240, 0.4);
        }

        .paseador-img {
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid #8A5AEB;
        }

        .paseador-badge {
            background: #6A0DAD;
            color: white;
            font-weight: normal;
        }

        .rating-stars {
            color: #FFD700;
            font-size: 1.2rem;
        }

        .section-title {
            border-left: 4px solid #8A5AEB;
            padding-left: 10px;
            margin: 25px 0 15px;
            color: #E2D4FF;
        }

        .modal-paseador {
            background: linear-gradient(135deg, #3A225D 0%, #2B1A45 100%);
            color: #f0e6ff;
            border: 1px solid #6A0DAD;
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
        <div class="container glass-dueño py-4">
            <h1 class="display-5 mb-4">Encuentra a tu paseador ideal</h1>
            
            <!-- Sección de Paseadores con experiencia en tus razas -->
            <?php if (!empty($paseadoresConExperiencia)): ?>
            <div class="mb-5">
                <h3 class="section-title text-start">Paseadores con experiencia en tus razas</h3>
                <div class="row">
                    <?php foreach($paseadoresConExperiencia as $paseador): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 paseador-card">
                            <img src="<?= htmlspecialchars($paseador->getFotoUrl() ?: 'img/default-profile.png') ?>" 
                                 class="card-img-top paseador-img" alt="Foto de paseador">
                            <div class="card-body">
                                <h4 class="card-title"><?= htmlspecialchars($paseador->getNombre()) ?></h4>
                                <span class="badge paseador-badge mb-2">
                                    <?= htmlspecialchars($paseador->getEstado()->getEstado()) ?>
                                </span>
                                <div class="mb-3">
                                    <small>(experto en tus razas)</small>
                                </div>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                        data-bs-target="#modalPaseador<?= $paseador->getId() ?>">
                                    Ver perfil completo
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Todos los paseadores disponibles -->
            <div class="mb-4">
                <h3 class="section-title text-start">Todos los paseadores disponibles</h3>
                <div class="row">
                    <?php foreach($paseadoresActivos as $paseador): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 paseador-card">
                            <img src="<?= htmlspecialchars($paseador->getFotoUrl() ?: 'assets/img/default-profile.png') ?>" 
                                 class="card-img-top paseador-img" alt="Foto de paseador">
                            <div class="card-body">
                                <h4 class="card-title"><?= htmlspecialchars($paseador->getNombre()) ?></h4>
                                <span class="badge paseador-badge mb-2">
                                    <?= htmlspecialchars($paseador->getEstado()->getEstado()) ?>
                                </span>
                                <p class="card-text mb-3">
                                    <i class="bi bi-telephone"></i> <?= htmlspecialchars($paseador->getTelefono()) ?>
                                </p>
                                <button class="btn btn-primary" data-bs-toggle="modal" 
                                        data-bs-target="#modalPaseador<?= $paseador->getId() ?>">
                                    Ver perfil completo
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales para cada paseador -->
    <?php 
    // Combinar paseadores sin duplicados
    $todosPaseadores = array_merge($paseadoresActivos, $paseadoresConExperiencia);
    $idsMostrados = [];
    ?>
    
    <?php foreach($todosPaseadores as $paseador): ?>
        <?php if(!in_array($paseador->getId(), $idsMostrados)): ?>
            <?php $idsMostrados[] = $paseador->getId(); ?>
            <div class="modal fade" id="modalPaseador<?= $paseador->getId() ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content modal-paseador">
                        <div class="modal-header border-bottom border-secondary">
                            <h5 class="modal-title">Perfil de <?= htmlspecialchars($paseador->getNombre()) ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="<?= htmlspecialchars($paseador->getFotoUrl() ?: 'img/default-profile.png') ?>" 
                                         class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #8A5AEB;">
                                    <h4><?= htmlspecialchars($paseador->getNombre()) ?></h4>
                                    <span class="badge paseador-badge">
                                        <?= htmlspecialchars($paseador->getEstado()->getEstado()) ?>
                                    </span>
                                </div>
                                <div class="col-md-8">
                                    <h5><i class="bi bi-info-circle"></i> Información de Contacto</h5>
                                    <p><i class="bi bi-telephone"></i> <strong>Teléfono:</strong> <?= htmlspecialchars($paseador->getTelefono()) ?></p>
                                    <p><i class="bi bi-envelope"></i> <strong>Correo:</strong> <?= htmlspecialchars($paseador->getCorreo()) ?></p>                                    
                                    <div class="d-grid gap-2 mt-4">
                                        <a href="?pid=<?= base64_encode("presentacion/paseo/solicitarPaseo.php") ?>&id_paseador=<?= $paseador->getId() ?>" 
                                           class="btn btn-primary btn-lg">
                                            <i class="bi bi-calendar-plus"></i> Solicitar Paseo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</body>