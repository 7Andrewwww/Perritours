<?php
// Verificar sesión de dueño
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Obtener parámetros
$idPaseador = $_GET['id_paseador'] ?? null;
if (!$idPaseador) {
    header("Location: ?pid=" . base64_encode("presentacion/dueño/consultarPaseadores.php"));
    exit();
}

// Consultar datos necesarios
$paseador = new Paseador($idPaseador);
$paseador->consultar();
$misPerros = Perro::consultarPorDueño($_SESSION['id']);
?>

<style>
.glass-form {
    background: rgba(50, 30, 80, 0.85);
    border-radius: 1rem;
    padding: 2rem;
    backdrop-filter: blur(10px);
    color: #f0e6ff;
}

.paseador-header {
    background: rgba(106, 13, 173, 0.2);
    border-radius: 0.75rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.form-select, .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid #6A0DAD;
    color: white !important; /* Forzar color blanco */
}

.form-select option {
    color: #000 !important; /* Texto negro para mejor visibilidad */
    background-color: #fff !important;
}

.form-select:focus, .form-control:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: #9C27B0;
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(106, 13, 173, 0.25);
}

.btn-solicitar {
    background-color: #6A0DAD;
    border: none;
    transition: all 0.3s;
}

.btn-solicitar:hover {
    background-color: #7B1FA2;
    transform: translateY(-2px);
}

.perro-select-group {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(106, 13, 173, 0.1);
    border-radius: 0.5rem;
}

.error-message {
    color: #ff6b6b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-form">
                <div class="paseador-header d-flex align-items-center">
                    <img src="<?= htmlspecialchars($paseador->getFotoUrl() ?? 'img/default-profile.png') ?>" 
                         class="rounded-circle me-3" width="80" height="80">
                    <div>
                        <h3 class="mb-0">Solicitar paseo con <?= htmlspecialchars($paseador->getNombre()) ?></h3>
                        <small class="text-white"><?= htmlspecialchars($paseador->getTelefono()) ?></small>
                    </div>
                </div>
                
                <form id="formSolicitud" action="?pid=<?= base64_encode("presentacion/solicitud/procesarSolicitud.php") ?>" method="post">
                    <input type="hidden" name="id_paseador" value="<?= $idPaseador ?>">
                    
                    <div class="perro-select-group">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Perro 1 (Obligatorio)</label>
                                <select class="form-select perro-select" name="id_perro[]" required>
                                    <option value="">Seleccione su perro</option>
                                    <?php foreach($misPerros as $perro): ?>
                                    <option value="<?= $perro->getIdPerro() ?>">
                                        <?= htmlspecialchars($perro->getNombre()) ?> (<?= htmlspecialchars($perro->getRaza()) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Perro 2 (Opcional)</label>
                                <select class="form-select perro-select" name="id_perro[]">
                                    <option value="">Ningún perro adicional</option>
                                    <?php foreach($misPerros as $perro): ?>
                                    <option value="<?= $perro->getIdPerro() ?>">
                                        <?= htmlspecialchars($perro->getNombre()) ?> (<?= htmlspecialchars($perro->getRaza()) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="error-message" id="error-perros">No puedes seleccionar el mismo perro dos veces</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-solicitar btn-lg text-light">
                            <i class="fas fa-paper-plane me-2"></i> Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formSolicitud');
    const perroSelects = document.querySelectorAll('.perro-select');
    const errorMessage = document.getElementById('error-perros');
    
    // Validar selección de perros no repetidos
    function validarPerros() {
        const valores = [];
        let valido = true;
        
        perroSelects.forEach(select => {
            if (select.value) valores.push(select.value);
        });
        
        // Verificar duplicados
        if (new Set(valores).size !== valores.length) {
            errorMessage.style.display = 'block';
            valido = false;
        } else {
            errorMessage.style.display = 'none';
        }
        
        return valido;
    }
    
    // Asignar eventos a los selects
    perroSelects.forEach(select => {
        select.addEventListener('change', validarPerros);
    });
    
    // Validar antes de enviar
    form.addEventListener('submit', function(e) {
        if (!validarPerros()) {
            e.preventDefault();
            return false;
        }
        return true;
    });
    
    // Mejorar visibilidad de las opciones
    document.querySelectorAll('.form-select option').forEach(option => {
        option.style.color = '#000';
        option.style.backgroundColor = '#fff';
    });
});
</script>
</body>