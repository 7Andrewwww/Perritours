<?php
if(isset($_GET["sesion"])) {
    if($_GET["sesion"] == "false") {
        session_destroy();
    }
}
$error = false;
if(isset($_POST["autenticar"])) {
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    
    $admin = new Admin("", "", $correo, $clave);
    if($admin->autenticar()) {
        $_SESSION["id"] = $admin->getId();
        $_SESSION["rol"] = "admin";
        header("Location: ?pid=" . base64_encode("presentacion/sesionAdmin.php"));
        exit();
    }
    else {
        $paseador = new Paseador("", "", $correo, $clave);
        if($paseador->autenticar()) {
            $_SESSION["id"] = $paseador->getId();
            $_SESSION["rol"] = "paseador";
            header("Location: ?pid=" . base64_encode("presentacion/sesionPaseador.php"));
            exit();
        }
        else {
            $dueño = new Dueño("", "", $correo, $clave);
            if($dueño->autenticar()) {
                $_SESSION["id"] = $dueño->getId();
                $_SESSION["rol"] = "dueño";
                header("Location: ?pid=" . base64_encode("presentacion/sesionDueño.php"));
                exit();
            } else {
                $error = true;
            }
        }
    }
}
?>

<body>
<?php 
include ("presentacion/encabezado.php");
include ("presentacion/fondo.php");
include ("presentacion/boton.php");
?>

  <div class="login-container glass mx-auto p-4" style="max-width: 450px; margin-top: 5rem;">
    <div class="text-center mb-4">
        <div class="logo mb-3" style="font-size: 2.5rem; color: #c5f5dc;">
            <i class="fas fa-paw me-2"></i>Perritours
        </div>
        <h4 class="text-white">Iniciar Sesión</h4>
    </div>

    <form action="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>" method="POST" class="px-3">
        <div class="mb-4">
            <label for="correo" class="form-label text-white mb-2">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent text-white border-end-0">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" name="correo" class="form-control bg-transparent text-white border-start-0" 
                       id="correo" required placeholder="usuario@correo.com" style="border-left: none !important;">
            </div>
        </div>

        <div class="mb-4">
            <label for="clave" class="form-label text-white mb-2">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent text-white border-end-0">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="clave" class="form-control bg-transparent text-white border-start-0" 
                       id="clave" required placeholder="********" style="border-left: none !important;">
            </div>
        </div>

        <button type="submit" name="autenticar" 
                class="btn btn-primary w-100 py-2 mt-2 fw-bold" 
                style="background: #8e2ab2; border: none; border-radius: 8px;">
            Iniciar sesión
        </button>
        
        <?php if($error): ?>
            <div class="alert alert-danger mt-3 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                Credenciales incorrectas
            </div>
        <?php endif; ?>
    </form>
</div>
</body>