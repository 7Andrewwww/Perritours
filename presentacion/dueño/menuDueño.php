<?php
$id = $_SESSION["id"];
?>
<style>
.dropdown-item:hover {
    background-color: #3A3A72 !important;
    color: #ffffff !important;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1A1A2E;">
    <div class="container">
        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionDueño.php") ?>">🐾 Perritours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDueñoContent"
            aria-controls="navbarDueñoContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarDueñoContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Menú Perfil -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Mi Perfil
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/dueño/editarMiPerfil.php") ?>">Editar Información</a></li>
                    </ul>
                </li>

                <!-- Menú Mis Perros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Mis Perritos
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/consultarMisPerros.php") ?>">Ver mis perritos</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>">Añadir un perrito</a></li>
                    </ul>
                </li>

                <!-- Menú Paseos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/consultarPaseadores.php") ?>">Paseadores disponibles</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseo/solicitarPaseo.php") ?>">Solicitar paseo</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseo/historialPaseos.php") ?>">Historial de paseos</a></li>
                    </ul>
                </li>

                <!-- Menú Facturación -->
                <li class="nav-item">
                    <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/factura/consultarFacturas.php") ?>">
                        Mis Facturas
                    </a>
                </li>
                <!-- Dentro de tu menú existente -->
				<li class="nav-item">
    				<a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/dueño/dashboardDueño.php") ?>">
        			<i class="bi bi-speedometer2"></i> Dashboard
    				</a>
				</li>

            </ul>
            <div class="d-flex">
                <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                    class="btn btn-outline-light" type="submit">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>