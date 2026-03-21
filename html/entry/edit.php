<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
    require_once BASE_PATH . '/lib/database.php';
    require_once BASE_PATH . '/lib/utilities.php';

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /user/login.php');
        exit();
    }

    $content = '';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        hacerEnGet();
    } else {
        hacerEnPost();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar - Anotador</title>
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>

    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">

    <script src="/assets/lib/jquery-3.7.1.min.js"></script>
    <script src="/assets/lib/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/lib/autosize.min.js"></script>

    <script>
        $(document).ready(function(){
            autosize($('textarea'));

            var searchInput = $('#ta');
            var strLength = searchInput.val().length * 2;
            searchInput.focus();
            searchInput[0].setSelectionRange(strLength, strLength);
        });

        // Prevenir salida accidental
        window.onbeforeunload = function() {
            return "Es posible que los cambios no se guarden";
        };
    </script>
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
                            <form method="post" action="/entry/edit.php">
                                <input type="hidden" name="date" value="<?php echo $_GET["date"]; ?>">

                                <div class="mb-3">
                                    <label for="ta" class="form-label fw-bold text-muted">Escribe tus notas</label>
                                    <textarea id="ta" name="content"
                                              class="form-control border-0 note-text-area fs-5"
                                              rows="5"
                                              style="resize: none; outline: none; box-shadow: none;"
                                              placeholder="¿Qué tienes en mente?"><?php echo $content ?></textarea>
                                </div>

                                <div class="d-flex mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow"
                                            onclick="window.onbeforeunload=null">
                                        <i class="bi bi-floppy me-2"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="mt-5">
            <?php include_once BASE_PATH . '/templates/footer.php' ?>
        </footer>
    </div>

</body>
</html>

<?php
    function hacerEnGet() {
        global $content;
        if (isset($_GET['date']) && !empty($_GET['date']) && !formatErrorYMD($_GET['date'])) {
            $stmt = db()->prepare('SELECT * FROM entries WHERE date=:d AND owner=:o');
            $stmt->bindParam(':d', $_GET['date']);
            $stmt->bindParam(':o', $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                http_response_code(404);
                include_once BASE_PATH . '/templates/404.php';
                exit();
            }
            $content = decrypt($result[0]['content'], $_SESSION['crypt_key']);
        } else {
            http_response_code(400);
            include_once BASE_PATH . '/templates/400.php';
            exit();
        }
    }

    function hacerEnPost() {
        if(isset($_POST['content']) && isset($_POST['date']) && !empty($_POST['date']) && !formatErrorYMD($_POST['date'])) {
            $stmt = db()->prepare('UPDATE entries SET content=:c WHERE date=:d AND owner=:o');
            $stmt->bindParam(':c', $_POST['content']);
            $stmt->bindParam(':d', $_POST['date']);
            $stmt->bindParam(':o', $_SESSION['user']);
            $_POST['content'] = encrypt($_POST['content'], $_SESSION['crypt_key']);
            $stmt->execute();
            header('Location: /entry/view.php?date=' . $_POST['date']);
            exit();
        } else {
            http_response_code(400);
            include_once BASE_PATH . '/templates/400.php';
            exit();
        }
    }
?>
