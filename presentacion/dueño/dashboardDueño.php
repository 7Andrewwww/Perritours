<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

// Obtener estadísticas
$idDueño = $_SESSION["id"];
$estadisticas = new EstadisticasDueño($idDueño);
$datos = $estadisticas->obtenerEstadisticas();
?>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Mi Dashboard</h1>
            
            <!-- Tarjetas de resumen -->
            <div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
                <div class="col">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="bi bi-dog"></i>
                            </div>
                            <h5 class="card-title"><?= $datos['total_perros'] ?></h5>
                            <p class="card-text">Perros Registrados</p>
                        </div>
                    </div>
                </div>
                
                <div class="col">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <h5 class="card-title"><?= $datos['total_paseos'] ?></h5>
                            <p class="card-text">Paseos Realizados</p>
                        </div>
                    </div>
                </div>
                
                <div class="col">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h5 class="card-title">$<?= number_format($datos['gasto_total'], 2) ?></h5>
                            <p class="card-text">Total Gastado</p>
                        </div>
                    </div>
                </div>
                
                <div class="col">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <h5 class="card-title"><?= $datos['paseador_favorito'] ?? 'N/A' ?></h5>
                            <p class="card-text">Paseador Favorito</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos y tablas -->
            <div class="row">
                <!-- Gráfico de paseos por mes -->
                <div class="col-lg-6 mb-4">
                    <div class="card glass-card h-100">
                        <div class="card-header text-light">
                            <h5>Paseos por Mes</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="paseosChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico de gastos -->
                <div class="col-lg-6 mb-4">
                    <div class="card glass-card h-100">
                        <div class="card-header text-light">
                            <h5>Distribución de Gastos</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="gastosChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Paseadores frecuentes -->
            <div class="card glass-card mb-4">
                <div class="card-header text-light">
                    <h5>Mis Paseadores Frecuentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Paseos</th>
                                    <th>Calificación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($datos['paseadores_frecuentes'] as $paseador): ?>
                                <tr>
                                    <td>
                                        <img src="<?= $paseador['foto_url'] ?: 'assets/img/default-profile.png' ?>" 
                                             class="rounded-circle paseador-img" alt="Foto de perfil">
                                    </td>
                                    <td><?= $paseador['nombre'] ?></td>
                                    <td><?= $paseador['paseos'] ?></td>
                                    <td>
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-star-fill <?= $i < round($paseador['calificacion']) ? 'text-warning' : 'text-secondary' ?>"></i>
                                        <?php endfor; ?>
                                    </td>
                                    <td>
                                        <a href="?pid=<?= base64_encode("presentacion/paseador/verPerfil.php") ?>&id=<?= $paseador['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Ver Perfil
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de paseos por mes
        const ctx1 = document.getElementById('paseosChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($datos['paseos_mes'], 'mes')) ?>,
                datasets: [{
                    label: 'Paseos realizados',
                    data: <?= json_encode(array_column($datos['paseos_mes'], 'cantidad')) ?>,
                    backgroundColor: 'rgba(138, 43, 226, 0.7)',
                    borderColor: 'rgba(138, 43, 226, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Gráfico de gastos
        const ctx2 = document.getElementById('gastosChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($datos['gastos_mes'], 'mes')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($datos['gastos_mes'], 'total')) ?>,
                    backgroundColor: [
                        'rgba(138, 43, 226, 0.7)',
                        'rgba(75, 0, 130, 0.7)',
                        'rgba(147, 112, 219, 0.7)',
                        'rgba(186, 85, 211, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });
    </script>

    <style>
        /* Estilos personalizados para el dashboard */
        .stat-card {
            background: rgba(35, 35, 74, 0.7);
            border: 1px solid blueviolet;
            border-radius: 10px;
            color: white;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .stat-icon {
            font-size: 2rem;
            color: blueviolet;
            margin-bottom: 10px;
        }
        
        .stat-card .card-title {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .glass-card {
            background: rgba(35, 35, 74, 0.7);
            border: 1px solid blueviolet;
            border-radius: 10px;
            color: white;
        }
        
        .paseador-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        
        .table-dark {
            background-color: rgba(26, 26, 46, 0.7);
        }
        
        .table-dark th {
            border-bottom: 2px solid blueviolet;
        }
    </style>
</body>