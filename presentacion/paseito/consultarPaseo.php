<?php
// Verificar rol de administrador
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
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
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/boton.php");
    include("presentacion/admin/menuAdmin.php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseos</h1>

            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tarifa</th>
                            <th>Paseador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paseos = Paseo::consultarTodos();
                        foreach($paseos as $paseo) {
                            $fechaFormateada = date("d/m/Y", strtotime($paseo->getFecha()));
                            $horaFormateada = date("H:i", strtotime($paseo->getHora()));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fechaFormateada) ?></td>
                            <td><?php echo htmlspecialchars($horaFormateada) ?></td>
                            <td><span class="tarifa-badge">$<?php echo number_format($paseo->getTarifa(), 2) ?></span></td>
                            <td><?php echo htmlspecialchars($paseo->getPaseador()->getNombre()) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</body>