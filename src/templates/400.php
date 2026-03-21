<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Bad Request</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <?php include_once BASE_PATH . '/templates/encabezado.php' ?>
        <div class="cuadro">
            <p class="error margintop">Error!</p>
            <p>Bad Request</p>
        </div>
        <?php include_once BASE_PATH . '/templates/footer.php' ?>
    </div>
</body>
</html>
