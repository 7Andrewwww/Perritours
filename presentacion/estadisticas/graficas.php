<?php
// Obtener datos para las gráficas
$paseosMensuales = Paseo::obtenerCrecimientoMensual();
$momentosPopulares = Paseo::obtenerMomentosPopulares();
$totalPerros = Perro::contarTotal();
$promedioTarifas = Paseo::obtenerPromedioTarifas();
$totalPaseos = Paseo::contarTotalPaseos();
$satisfaccionUsuarios = Dueño::obtenerPromedioSatisfaccion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Perritours</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container-graficas {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 100px;
        }
        
        .grafica-box {
            margin: 20px 0;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilos para el grid de gráficas pequeñas */
        .grid-graficas {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .grid-graficas {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Incluir componentes externos -->
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/encabezado.php");
    ?>
    
    <!-- Contenedor principal de gráficas -->
    <div class="container-graficas">
        <!-- Gráficas grandes (ancho completo) -->
        <div class="grafica-box" id="grafica_paseos" style="width: 100%; height: 400px;"></div>
        <div class="grafica-box" id="grafica_momentos" style="width: 100%; height: 400px;"></div>
        
        <!-- Gráficas pequeñas en grid -->
        <div class="grid-graficas">
            <div class="grafica-box" id="grafica_satisfaccion" style="height: 300px;"></div>
            <div class="grafica-box" id="grafica_total_perros" style="height: 300px;"></div>
            <div class="grafica-box" id="grafica_promedio_tarifas" style="height: 300px;"></div>
            <div class="grafica-box" id="grafica_total_paseos" style="height: 300px;"></div>
        </div>
    </div>

    <script>
        // Cargar la API de Google Charts
        google.charts.load('current', {'packages':['corechart', 'gauge']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // 1. Gráfico de crecimiento mensual (Línea)
            const paseosData = new google.visualization.DataTable();
            paseosData.addColumn('string', 'Mes');
            paseosData.addColumn('number', 'Paseos');
            paseosData.addRows([
                <?php 
                foreach($paseosMensuales as $index => $fila) {
                    if($index === 0) continue; // Saltar encabezados
                    echo "['".$fila[0]."', ".$fila[1]."],";
                }
                ?>
            ]);
            
            const paseosOptions = {
                title: 'Crecimiento de paseos mensuales',
                curveType: 'function',
                legend: { position: 'bottom' },
                hAxis: { title: 'Mes' },
                vAxis: { title: 'Cantidad de paseos', minValue: 0 },
                colors: ['#e74c3c'],
                backgroundColor: 'transparent'
            };
            
            const chartPaseos = new google.visualization.LineChart(document.getElementById('grafica_paseos'));
            chartPaseos.draw(paseosData, paseosOptions);

            // 2. Momentos populares (Barras)
            const momentosData = new google.visualization.DataTable();
            momentosData.addColumn('string', 'Franja Horaria');
            momentosData.addColumn('number', 'Cantidad');
            momentosData.addRows([
                <?php 
                foreach($momentosPopulares as $index => $fila) {
                    if($index === 0) continue; // Saltar encabezados
                    echo "['".$fila[0]."', ".$fila[1]."],";
                }
                ?>
            ]);
            
            const momentosOptions = { 
                title: 'Momentos más populares para paseos',
                hAxis: { title: 'Franja horaria' },
                vAxis: { title: 'Cantidad de paseos', minValue: 0 },
                colors: ['#3498db'],
                backgroundColor: 'transparent'
            };
            
            const chartMomentos = new google.visualization.ColumnChart(document.getElementById('grafica_momentos'));
            chartMomentos.draw(momentosData, momentosOptions);

            // 3. Satisfacción usuarios (Gauge)
            const satisfaccionData = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Satisfacción', <?php echo $satisfaccionUsuarios; ?>]
            ]);
            
            const satisfaccionOptions = {
                title: 'Nivel de satisfacción (1-5)',
                width: '100%', 
                height: '100%',
                redFrom: 0, redTo: 2.5,
                yellowFrom: 2.5, yellowTo: 4,
                greenFrom: 4, greenTo: 5,
                minorTicks: 5,
                max: 5,
                backgroundColor: 'transparent'
            };
            
            const chartSatisfaccion = new google.visualization.Gauge(document.getElementById('grafica_satisfaccion'));
            chartSatisfaccion.draw(satisfaccionData, satisfaccionOptions);

            // 4. Total perros (Donut)
            const perrosData = google.visualization.arrayToDataTable([
                ['Item', 'Cantidad'],
                ['Perros registrados', <?php echo $totalPerros; ?>],
                ['Restante', <?php echo max(0, 100 - $totalPerros); ?>]
            ]);
            
            const perrosOptions = { 
                title: 'Perros registrados (Meta: 100)',
                pieHole: 0.4,
                slices: {
                    0: { color: '#2ecc71' },
                    1: { color: '#f1f1f1' }
                },
                legend: 'none',
                backgroundColor: 'transparent'
            };
            
            const chartPerros = new google.visualization.PieChart(document.getElementById('grafica_total_perros'));
            chartPerros.draw(perrosData, perrosOptions);

            // 5. Promedio tarifas (Donut)
            const tarifasData = google.visualization.arrayToDataTable([
                ['Item', 'Valor'],
                ['Promedio', <?php echo $promedioTarifas; ?>],
                ['Diferencia', <?php echo max(0, 50000 - $promedioTarifas); ?>]
            ]);
            
            const tarifasOptions = { 
                title: 'Promedio de tarifas (COP)',
                pieHole: 0.4,
                slices: {
                    0: { color: '#9b59b6' },
                    1: { color: '#f1f1f1' }
                },
                legend: 'none',
                backgroundColor: 'transparent'
            };
            
            const chartTarifas = new google.visualization.PieChart(document.getElementById('grafica_promedio_tarifas'));
            chartTarifas.draw(tarifasData, tarifasOptions);

            // 6. Total paseos (Donut)
            const paseosTotalesData = google.visualization.arrayToDataTable([
                ['Item', 'Cantidad'],
                ['Paseos realizados', <?php echo $totalPaseos; ?>],
                ['Restante', <?php echo max(0, 100 - $totalPaseos); ?>]
            ]);
            
            const totalesOptions = { 
                title: 'Paseos realizados (Meta: 100)',
                pieHole: 0.4,
                slices: {
                    0: { color: '#e67e22' },
                    1: { color: '#f1f1f1' }
                },
                legend: 'none',
                backgroundColor: 'transparent'
            };
            
            const chartTotales = new google.visualization.PieChart(document.getElementById('grafica_total_paseos'));
            chartTotales.draw(paseosTotalesData, totalesOptions);
            
            // Redibujar gráficos cuando cambie el tamaño de la ventana
            window.addEventListener('resize', function() {
                chartPaseos.draw(paseosData, paseosOptions);
                chartMomentos.draw(momentosData, momentosOptions);
                chartSatisfaccion.draw(satisfaccionData, satisfaccionOptions);
                chartPerros.draw(perrosData, perrosOptions);
                chartTarifas.draw(tarifasData, tarifasOptions);
                chartTotales.draw(paseosTotalesData, totalesOptions);
            });
        }
    </script>
</body>
</html>