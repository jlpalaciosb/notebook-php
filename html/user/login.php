<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
    require_once BASE_PATH . '/lib/database.php';

    session_start();

    if(isset($_SESSION['user'])) { # Already started session
        header('Location: /index.php');
        exit();
    }

    $user = $error = '';
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (authenticate($_POST['user'], $_POST['password'])) {
            $_SESSION['user'] = $_POST['user'];
            $_SESSION['crypt_key'] = md5($_POST['user'] . $_POST['password']); # key for encrypting and decrypting diarys
            header('Location: /index.php');
            exit();
        } else {
            $error = 'Nombre de usuario o contraseña incorrectos.';
            $user = $_POST['user'];
        }
    }
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo ($_SESSION['theme'] ?? 'l') === 'd' ? 'dark' : 'light'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Notas</title>
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>

    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
</head>
<body class="d-flex align-items-center py-4 min-vh-100">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="mb-4">
                    <?php require_once BASE_PATH . '/templates/logo.php' ?>
                </div>

                <div class="card login-card">
                    <div class="card-body p-4 p-sm-5">
                        <h2 class="text-center fw-bold mb-4">Inicia Sesión</h2>

                        <form method="post" action="/user/login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" name="user" id="username"
                                       class="form-control form-control-lg"
                                       placeholder="Tu usuario"
                                       value="<?php echo $user;?>"
                                       autofocus required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" id="password"
                                       class="form-control form-control-lg"
                                       placeholder="Tu contraseña"
                                       required>
                            </div>

                            <?php if ($error) { ?>
                                <div class="error-text mb-3 text-center">
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    <span>Ingresar</span>
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0 text-muted">¿No tienes cuenta?
                                <a href="/user/register.php" class="text-decoration-none fw-bold">Crear Cuenta</a>
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

    <script src="/assets/lib/bootstrap-5.3.8/js/bootstrap.min.js"></script>
</body>
</html>
