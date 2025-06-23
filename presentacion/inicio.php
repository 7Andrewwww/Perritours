<body>
<?php 
include ("presentacion/encabezado.php");
include ("presentacion/fondo.php");
?>
  <!-- HERO SECTION -->
  <section class="text-center hero-text">
    <div class="container-xl glass">
      <h1 class="display-4">¡Paseos felices, dueños tranquilos!</h1>
      <p class="lead">Con Perritours, puedes agendar paseos seguros, pagar con confianza y hacer feliz a tu mascota.</p>
      <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>" class="btn btn-primary btn-lg mt-3">Ingresa</a>
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

