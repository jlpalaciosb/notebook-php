<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>No encontrado - Anotador</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
</head>
<body class="">
    <div class="container py-5">
        <?php include_once BASE_PATH . '/templates/encabezado.php' ?>

        <div class="d-flex justify-content-center align-items-center mt-5">
            <div class="card login-card border-0 shadow-lg text-center p-5" style="max-width: 500px;">
                <div class="card-body text-dark">
                    <div class="mb-4">
                        <i class="bi bi-search text-secondary opacity-50" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="fw-bold text-dark mb-2">
                        No encontrado
                    </h1>
                    <p class="text-muted mb-4">
                        Lo sentimos, la página que estás buscando no existe o ha sido movida.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="/index.php" class="btn btn-primary rounded-pill py-2 fw-bold">
                            <i class="bi bi-house-door me-2"></i>Volver al Inicio
                        </a>
                        <button onclick="window.history.back()" class="btn btn-outline-secondary rounded-pill py-2">
                            <i class="bi bi-arrow-left me-2"></i>Volver atrás
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center">
            <?php include_once BASE_PATH . '/templates/footer.php' ?>
        </div>
    </div>
</body>
</html>
