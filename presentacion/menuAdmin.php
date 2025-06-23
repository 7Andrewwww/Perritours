<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fs-4">🐾 Perritours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="#">Estadísticas</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Gestión Usuarios
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li><a class="dropdown-item text-light" href="#">Paseadores</a></li>
                        <li><a class="dropdown-item text-light" href="#">Dueños</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li>
                            <a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseo/consultarPaseo.php") ?>">Consultar</a>
                        </li>
                        <li>
                            <a class="dropdown-item text-light" href="#">Modificar</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <a class="nav-link active mx-3" href="?pid=<?php echo base64_encode("presentacion/sesionAdmin.php") ?>">Usuario <i
                class="fa-regular fa-user"></i></a>

            <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                class="btn btn-outline-light" type="submit">Cerrar Sesión</a>
        </div>
    </div>
</nav>
