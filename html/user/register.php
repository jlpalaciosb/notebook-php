<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
    require_once BASE_PATH . '/lib/database.php';

    session_start();

    if(isset($_SESSION['user'])) { # Already started session
        header('Location: /index.php');
        exit();
    }

    $error = $user = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_POST['user'];
        if (registered($_POST['user'])) {
            $error = 'El nombre ya está en uso.';
        } elseif (strlen($_POST['user']) < 4) {
            $error = 'Nombre muy corto.';
        } elseif (strlen($_POST['user']) > 10) {
            $error = 'Nombre muy largo.';
        } elseif (preg_match('/[^A-Za-z0-9]/', $_POST['user'])) {
            $error = 'Nombre no válido.';
        } elseif (strlen($_POST['password']) < 8) {
            $error = 'La contraseña debe tener como mínimo 8 caracteres.';
        } elseif ($_POST['password'] != $_POST['password_confirm']) {
            $error = 'Las contraseñas no coinciden.';
        }

        if ($error == '') {
            register($_POST['user'], $_POST['password']);
            $_SESSION['user'] = $_POST['user'];
            $_SESSION['crypt_key'] = md5($_POST['user'] .$_POST['password']); # key for encrypting and decrypting diarys
            header('Location: /index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Anotador</title>
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
</head>
<body class="d-flex align-items-center py-4 min-vh-100">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="text-center mb-4">
                    <img src="/assets/img/diarioapp2.png" alt="Logo" style="height: 70px;">
                </div>

                <div class="card login-card">
                    <div class="card-body p-4 p-sm-5">
                        <h2 class="text-center fw-bold mb-4">Crea una cuenta</h2>

                        <form method="post" action="/user/register.php">
                            <div class="mb-3">
                                <label for="new_user" class="form-label">Nombre de Usuario</label>
                                <input type="text" name="user" id="new_user"
                                       class="form-control form-control-lg"
                                       placeholder="Elige un nombre de usuario"
                                       value="<?php echo $user; ?>"
                                       autofocus required>
                            </div>

                            <div class="mb-3">
                                <label for="pass" class="form-label">Contraseña</label>
                                <input type="password" name="password" id="pass"
                                       class="form-control form-control-lg"
                                       placeholder="Ingresa una contraseña"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="pass_conf" class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirm" id="pass_conf"
                                       class="form-control form-control-lg"
                                       placeholder="Repite tu contraseña"
                                       required>
                            </div>

                            <?php if ($error) { ?>
                                <div class="error-text mb-3 text-center">
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-plus me-2"></i>
                                    <span>Registrarse</span>
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0 text-muted">¿Ya tienes cuenta?
                                <a href="/user/login.php" class="text-decoration-none fw-bold">Iniciar Sesión</a>
                            </p>
                        </div>
                    </div>
                </div>

                <footer class="mt-4 text-center text-muted">
                    <?php include_once BASE_PATH . '/templates/footer.php'; ?>
                </footer>

            </div>
        </div>
    </main>

    <script src="/assets/bootstrap-5.3.8/js/bootstrap.min.js"></script>
</body>
</html>
