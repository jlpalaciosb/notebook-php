<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
    require_once BASE_PATH . '/lib/database.php';
    require_once BASE_PATH . '/lib/utilities.php';

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /user/login.php');
        exit();
    }

    if (!isset($_GET['date']) || empty($_GET['date']) || formatErrorYMD($_GET['date'])) {
        http_response_code(400);
        include_once BASE_PATH . '/templates/400.php';
        exit();
    }

    $stmt = db()->prepare('SELECT * FROM entries WHERE owner=:o AND date=:d');
    $stmt->bindParam(':o', $_SESSION['user']);
    $stmt->bindParam(':d', $_GET['date']);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        http_response_code(404);
        include_once BASE_PATH . '/templates/404.php';
        exit();
    }
    $content = decrypt($result[0]['content'], $_SESSION['crypt_key']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ver - Anotador</title>
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>

    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">

    <script src="/assets/lib/jquery-3.7.1.min.js"></script>
    <script src="/assets/lib/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/lib/autosize.min.js"></script>
</head>
<body class="">
    <div class="container py-4">
        <?php include_once BASE_PATH . '/templates/encabezado.php' ?>

        <main class="mt-4">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h1 class="h2 mb-4 fw-bold text-white">
                        <?php echo legibleDate($_GET['date']) ?>
                    </h1>

                    <div class="card login-card text-dark">
                        <div class="card-body p-4">
                            <label for="ta" class="form-label fw-bold text-muted">Tus notas</label>
                            <textarea readonly id="ta"
                                    class="form-control border-0 note-text-area fs-5"
                                    style="resize: none;"
                                    rows="5"
                                    placeholder="Nota vacía, prueba editarla."
                            ><?php echo $content ?></textarea>

                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-lg px-5 shadow"
                                href="<?php echo '/entry/edit.php?date=' . $_GET['date']; ?>">
                                    <i class="bi bi-pencil me-2"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="mt-5">
            <?php include_once BASE_PATH . '/templates/footer.php' ?>
        </footer>
    </div>

    <script>
        $(document).ready(function(){
            autosize($('textarea'));
        });
    </script>
</body>
</html>
