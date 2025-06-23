<?php
// Verificar autenticación y rol
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$fechaActual = new DateTime();
if (isset($_GET['mes']) && isset($_GET['anio'])) {
    $mes = (int)$_GET['mes'];
    $anio = (int)$_GET['anio'];
    
    // Validar mes (1-12)
    if ($mes >= 1 && $mes <= 12) {
        $fechaActual->setDate($anio, $mes, 1);
    }
}

$idPaseador = $_SESSION['id'];
$mesActual = $fechaActual->format('n');
$anioActual = $fechaActual->format('Y');

// Consultar paseos del mes actual
$paseosProgramados = Paseo::consultarPaseosProgramados($idPaseador, $mesActual, $anioActual);

// Organizar paseos por fecha
$paseosPorFecha = [];
foreach ($paseosProgramados as $paseo) {
    $fecha = $paseo->getFecha();
    $paseosPorFecha[$fecha][] = $paseo->getHora();
}

// Configuración del calendario
$diasMes = $fechaActual->format('t');
$primerDiaSemana = $fechaActual->format('N'); // 1 (Lunes) a 7 (Domingo)
$hoy = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Horario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .calendar-container {
            background: rgba(42, 26, 64, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(120, 50, 220, 0.3);
            margin-top: 20px;
        }
        
        .calendar-header {
            background: #6A0DAD;
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .day-header {
            background: #4A366B;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .day-cell {
            background: #3D2B56;
            min-height: 100px;
            border-radius: 5px;
            padding: 8px;
            position: relative;
        }
        
        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .today {
            border: 2px solid #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }
        
        .has-events {
            background: rgba(255, 193, 7, 0.2);
        }
        
        .event-time {
            background: #FFC107;
            color: #333;
            font-size: 0.8rem;
            padding: 2px 5px;
            border-radius: 3px;
            margin: 2px 0;
            display: block;
            font-weight: bold;
        }
        
        .empty-day {
            opacity: 0.5;
            background: #2A1A40;
        }
        
        .month-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .legend {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/paseador/menuPaseador.php");
    ?>
    
    <div class="container py-4">
        <div class="calendar-container">
            <div class="calendar-header">
                <div class="month-nav">
                    <?php
                    $prevMonth = clone $fechaActual;
                    $prevMonth->modify('-1 month');
                    ?>
                    <a href="?pid=<?= base64_encode("presentacion/paseador/gestionHorario.php") ?>&mes=<?= $prevMonth->format('n') ?>&anio=<?= $prevMonth->format('Y') ?>" 
                       class="btn btn-sm btn-outline-light">
                        <i class="fas fa-chevron-left"></i> Mes anterior
                    </a>
                    
                    <h3 class="text-center mb-0"><?= strftime('%B %Y', $fechaActual->getTimestamp()) ?></h3>
                    
                    <?php
                    $nextMonth = clone $fechaActual;
                    $nextMonth->modify('+1 month');
                    ?>
                    <a href="?pid=<?= base64_encode("presentacion/paseador/gestionHorario.php") ?>&mes=<?= $nextMonth->format('n') ?>&anio=<?= $nextMonth->format('Y') ?>" 
                       class="btn btn-sm btn-outline-light">
                        Mes siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="calendar-grid">
                <!-- Encabezados de días -->
                <?php 
                $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                foreach ($diasSemana as $dia): ?>
                    <div class="day-header"><?= $dia ?></div>
                <?php endforeach; ?>
                
                <!-- Días vacíos al inicio -->
                <?php for ($i = 1; $i < $primerDiaSemana; $i++): ?>
                    <div class="day-cell empty-day"></div>
                <?php endfor; ?>
                
                <!-- Días del mes -->
                <?php for ($dia = 1; $dia <= $diasMes; $dia++): 
                    $fechaActual->setDate($anioActual, $mesActual, $dia);
                    $fechaFormato = $fechaActual->format('Y-m-d');
                    $esHoy = ($fechaFormato == $hoy);
                    $tieneEventos = isset($paseosPorFecha[$fechaFormato]);
                ?>
                <div class="day-cell <?= $esHoy ? 'today' : '' ?> <?= $tieneEventos ? 'has-events' : '' ?>">
                    <div class="day-number"><?= $dia ?></div>
                    
                    <?php if ($tieneEventos): 
                        foreach ($paseosPorFecha[$fechaFormato] as $hora): ?>
                            <span class="event-time">
                                <i class="fas fa-clock"></i> <?= substr($hora, 0, 5) ?>
                            </span>
                        <?php endforeach;
                    endif; ?>
                </div>
                <?php endfor; ?>
                
                <!-- Días vacíos al final -->
                <?php 
                $ultimoDia = clone $fechaActual;
                $ultimoDia->setDate($anioActual, $mesActual, $diasMes);
                $diasRestantes = 7 - $ultimoDia->format('N');
                
                for ($i = 0; $i < $diasRestantes; $i++): ?>
                    <div class="day-cell empty-day"></div>
                <?php endfor; ?>
            </div>
            
            <div class="legend">
                <span class="badge bg-warning me-3"><i class="fas fa-clock me-1"></i> Paseo programado</span>
                <span class="badge bg-success me-3"><i class="fas fa-calendar-day me-1"></i> Hoy</span>
                <span class="badge bg-primary"><i class="fas fa-square me-1"></i> Días laborables</span>
            </div>
        </div>
    </div>
</body>
</html>