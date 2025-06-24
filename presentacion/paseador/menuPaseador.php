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
        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionPaseador.php") ?>">🐾 Perritours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPaseadorContent"
            aria-controls="navbarPaseadorContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPaseadorContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Menú Perfil -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Mi Perfil
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/editarMiPerfil.php") ?>">Editar Información</a></li>
                    </ul>
                </li>

                <!-- Menú Paseos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Mis Paseos
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/consultarPaseoPaseador.php") ?>">Paseos Programados</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/historialPaseos.php") ?>">Historial de Paseos</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/solicitud/verPaseosPaseador.php") ?>">Solicitudes Pendientes</a></li>
                        
                    </ul>
                </li>

                <!-- Menú Disponibilidad -->
                <li class="nav-item">
                    <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseador/gestionHorario.php") ?>">
                        Mi Disponibilidad
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