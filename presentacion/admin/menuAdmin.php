<?php 
$id = $_SESSION["id"];
?>
<style>
.dropdown-item:hover {
    background-color: #3A3A72 !important; */
    color: #ffffff !important;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1A1A2E;">
    <div class="container">
        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionAdmin.php") ?>">🐾 Perritours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdminContent"
            aria-controls="navbarAdminContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdminContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Menú Paseadores -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Paseadores
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/crearPaseador.php") ?>">Crear Paseador</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/consultarPaseador.php") ?>">Consultar Paseadores</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>">Editar Paseadores</a></li>
                    </ul>
                </li>

                <!-- Menú Dueños -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dueños
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/dueño/consultarDueño.php") ?>">Consultar Dueños</a></li>
                    </ul>
                </li>

                <!-- Menú Perros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Perros
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerro.php") ?>">Consultar Perros</a></li>
                    </ul>
                </li>

                <!-- Menú Paseos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu" style="background-color: #23234A;">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/consultarPaseo.php") ?>">Consultar Paseos</a></li>
                    </ul>
                </li>

            </ul>
            <div class="d-flex">
                <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                    class="btn btn-outline-light" type="submit">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>
