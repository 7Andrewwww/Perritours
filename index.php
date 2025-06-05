<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perritours</title>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('/Perritours/imagenes/fondo.png') no-repeat center center fixed;
      background-size: cover;
      color: #e5def5;
      font-family: 'Segoe UI', sans-serif;
    }

    .glass {
      background: rgba(20, 3, 18, 0.7);
      border-radius: 20px;
      backdrop-filter: blur(8px);
      padding: 2rem;
    }

    .navbar {
      background-color: rgba(0, 0, 20, 0.85);
    }

    .hero-text {
      padding-top: 7rem;
      padding-bottom: 5rem;
    }

    .btn-primary {
      background-color: #8e2ab2;
      border: none;
    }

    .btn-primary:hover {
      background-color: #5c0a63;
    }

    .section-title {
      font-weight: bold;
      color: #c5f5dc;
      margin-bottom: 1.5rem;
    }

    footer {
      background-color: rgba(10, 10, 30, 0.95);
      color: #ccc;
      padding: 2rem 0;
    }

    footer a {
      color: #c5f5dc;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .rounded-card {
      border-radius: 1.5rem;
    }

    .img-fluid {
      border-radius: 1rem;
      max-height: 300px;
      object-fit: cover;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-xl px-4">
      <a class="navbar-brand fs-4" href="#">🐾 Perritours</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#about">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="#services">Servicios</a></li>
          <li class="nav-item"><a class="nav-link" href="#stats">Estadísticas</a></li>
          <li class="nav-item">
            <a href="autenticar.php" class="btn btn-primary ms-3">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="text-center hero-text">
    <div class="container-xl glass">
      <h1 class="display-4">¡Paseos felices, dueños tranquilos!</h1>
      <p class="lead">Con Perritours, puedes agendar paseos seguros, pagar con confianza y hacer feliz a tu mascota.</p>
      <a href="#about" class="btn btn-primary btn-lg mt-3">Descubre más</a>
    </div>
  </section>

  <!-- ABOUT SECTION -->
  <section id="about" class="py-5">
    <div class="container-xl">
      <div class="row align-items-center glass">
        <div class="col-md-6">
          <h2 class="section-title">¿Quiénes somos?</h2>
          <p>Perritours es una plataforma que conecta dueños de mascotas con paseadores de confianza. Ofrecemos una forma fácil y segura de programar paseos, pagar electrónicamente y obtener reportes y facturas. Cada paseo es monitoreado y verificado para que tú estés tranquilo mientras tu perrito disfruta.</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="/Perritours/imagenes/quienes.png" class="img-fluid" alt="Nosotros">
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES SECTION -->
  <section id="services" class="py-5">
    <div class="container-xl">
      <div class="row align-items-center glass">
        <div class="col-md-6 text-center">
          <img src="/Perritours/imagenes/servicios.png" class="img-fluid" alt="Servicios">
        </div>
        <div class="col-md-6">
          <h2 class="section-title">Nuestros Servicios</h2>
          <ul>
            <li>🧾 Facturación en PDF con código QR</li>
            <li>🗓️ Agenda de paseos con selección de fecha, hora y paseador</li>
            <li>📱 Gestión de usuarios con roles claros (dueños, paseadores, admins)</li>
            <li>📊 Consulta de historial y estadísticas de actividad</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- STATISTICS SECTION -->
  <section id="stats" class="py-5">
    <div class="container-xl">
      <div class="row align-items-center glass">
        <div class="col-md-6">
          <h2 class="section-title">Estadísticas</h2>
          <p>Visualiza datos relevantes para el negocio: frecuencia de paseos, ingresos por paseador, calificaciones y más. Presentamos la información de forma clara para que puedas tomar decisiones acertadas.</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="/Perritours/imagenes/analisis.png" class="img-fluid" alt="Estadísticas">
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="text-center">
    <div class="container-xl">
      <div class="row mb-3">
        <div class="col-md-4">
          <h6>Contacto</h6>
          <p>Email: info@perritours.com</p>
          <p>Tel: +57 300 123 4567</p>
        </div>
        <div class="col-md-4">
          <h6>Redes Sociales</h6>
          <p><a href="#">Instagram</a> | <a href="#">Facebook</a> | <a href="#">TikTok</a></p>
        </div>
        <div class="col-md-4">
          <h6>Enlaces útiles</h6>
          <p><a href="#about">Sobre nosotros</a> | <a href="#services">Servicios</a></p>
        </div>
      </div>
      <hr style="background-color: #444;">
      <p class="small">&copy; 2025 Perritours. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
