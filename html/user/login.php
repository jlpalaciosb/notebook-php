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
<html lang="es" class="h-100">
<head>
    <title>Login - Anotador</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
</head>
<body class="h-100">
    <div class="container d-flex align-items-center min-h-100" style="max-width: 400px;">
        <div class="form-container w-100">
            <img src="/assets/img/diarioapp2.png" alt="Logo"
            style="margin-bottom: 5px; height: 60px;">
            <form class="cuadro" method="post" action="/user/login.php">
                <h2 class="text-center mt-2">Inicia Sesión</h2>
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input autofocus required class="form-control" type="text" name="user" placeholder="Ingresa tu nombre de usuario" value="<?php echo $user;?>">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input required class="form-control" type="password" name="password" placeholder="Ingresa tu contraseña">
                </div>
                <p class="error"><?php echo $error; ?></p>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
                <p style="text-align: right;margin-top: 15px;margin-bottom: 0px;"><a href="/user/register.php">Crear Cuenta</a></p>
            </form>

            <div style="margin-top: 10px;"></div>
            <!--Footer-->
            <?php include_once BASE_PATH . '/templates/footer.php'; ?>
        </div>
    </div>
</body>
</html>
