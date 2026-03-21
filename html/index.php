<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
    require_once BASE_PATH . '/lib/database.php';
    require_once BASE_PATH . '/lib/utilities.php';

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /user/login.php');
        exit();
    }

    if (!isset($_GET['date']) || formatErrorYM($_GET['date'])) {
        header('Location: /index.php?date=' . date('Y') . '-' . date('m'));
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Anotador</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/assets/img/diarioapp.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">

    <script type="text/javascript" src="/assets/lib/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="/assets/lib/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        var a_eliminar = 'initial';
        var id_eliminar = -1;
        
        // Modal strings actualizados para B5 (d-none, data-bs-dismiss, text-end, btn-sm)
        var modal_body0 = '<p class="text-center fs-5">¿Seguro que quieres eliminar tus notas del <br><strong id="span"></strong>?</p>';
        var modal_body1 = '<div class="text-center fs-4"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2">Eliminando...</p></div>';
        var modal_body2 = '<p class="text-center fs-4">Eliminado!</p>';
        var modal_footer0 = '<button type="button" class="btn btn-danger px-4" onclick="eliminar()">Sí</button> <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">No</button>';
        var modal_footer2 = '<button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Ok</button>';

        function on_show_modal() {
            $("#modal-body").html(modal_body0);
            $("#span").html(legibleYMD(a_eliminar));
            $("#modal-footer").html(modal_footer0);
        }
        
        function eliminar() {
            $("#modal-body").html(modal_body1);
            $("#modal-footer").addClass('d-none'); // B5 usa d-none
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200 && this.responseText === "ok") {
                        setTimeout(eliminado, 1000);
                    } else {
                        alert("error");
                    }
                }
            };
            xhttp.open("GET", "/entry/delete.php?date=" + a_eliminar, true);
            xhttp.send();
        }
        
        async function eliminado() {
            $("#modal-body").html(modal_body2);
            // Mostramos el footer del modal con el botón "Ok"
            $("#modal-footer").removeClass('d-none').html(modal_footer2);

            var content = '';

            // Columna de Fecha
            content += '<td class="ps-3 align-middle">';
            content +=     legibleYMD(a_eliminar);
            content += '</td>';

            // Columna de Acciones
            content += '<td class="pe-3 text-end text-nowrap align-middle">';
            content +=     '<a href="/entry/new.php?date=' + a_eliminar + '">';
            content +=         '<button class="btn btn-sm btn-info text-white">Escribir</button>';
            content +=     '</a>';
            content += '</td>';

            $("#" + id_eliminar).html(content).addClass('table-light');
        }

        function legibleYMD(ymd) {
            var months = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre"];
            var year = ymd.substr(0,4);
            var month = ymd.substr(5,2);
            var day = ymd.substr(8,2);
            return day + " / " + months[parseInt(month)-1] + " / " + year;
        }
    </script>
</head>
<body class="">
    <div class="container py-4">
        <?php include_once BASE_PATH . '/templates/encabezado.php'; ?>

        <div class="d-flex justify-content-center my-4">
            <div class="d-flex align-items-center btn-group shadow-sm bg-white rounded p-1">
                <a class="btn btn-light border-0 px-3 rounded" href="/index.php?date=<?php echo mesAnterior($_GET['date'])?>">
                    <small class="text-muted text-uppercase fw-bold"><?php echo substr(CONSTANTS['meses'][intval(substr(mesAnterior($_GET['date']), 5)) - 1], 0 , 3) ?></small>
                </a>
                <span class="btn btn-white border-0 fw-bold px-4 text-dark fs-5 align-self-center" style="pointer-events: none;">
                    <?php echo legibleYM($_GET['date']) ?>
                </span>
                <a class="btn btn-light border-0 px-3 rounded" href="/index.php?date=<?php echo mesSiguiente($_GET['date'])?>">
                    <small class="text-muted text-uppercase fw-bold"><?php echo substr(CONSTANTS['meses'][intval(substr(mesSiguiente($_GET['date']), 5)) - 1], 0 , 3) ?></small>
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
            <h1 class="h3 mb-0 fw-bold text-white">Notas de <?php echo legibleYM($_GET['date']) ?></h1>
            <a href="/entry/new.php?date=<?php echo getCurrentDate() ?>" class="d-block">
                <img src="/assets/img/add.png" style="height: 42px; width: 42px;" alt="Escribe tus notas de hoy" title="Escribe tus notas de hoy">
            </a>
        </div>

        <div class="card login-card overflow-hidden border-0 shadow-lg text-dark bg-white" id="panel">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3 py-3 fw-bold">Fecha</th>
                            <th class="pe-3 py-3 text-end fw-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $anho = intval(substr($_GET['date'], 0, 4));
                            $mes = intval(substr($_GET['date'], 5));
                            for ($i=1; $i <= cal_days_in_month(CAL_GREGORIAN, $mes, $anho); $i++) {
                                imprimirFila($anho, $mes, $i);
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-dark border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Eliminar notas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4" id="modal-body"></div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center" id="modal-footer"></div>
                </div>
            </div>
        </div>

        <footer class="mt-4">
            <?php include_once BASE_PATH . '/templates/footer.php'; ?>
        </footer>
    </div>
</body>
</html>

<?php
    #imprime una fila de la tabla
    function imprimirFila($anho, $mes, $dia) {
        $date = toStringYMD($anho, $mes, $dia);

        echo '<tr id='. $dia . ' >';

        echo '<td class="ps-3 align-middle">';
            if (existsEntry($_SESSION['user'], $date)) {
                echo '<i class="bi bi-file-earmark-text text-primary me-2"></i>' . "\n";
                echo '<a href="/entry/view.php?date=' . $date . '" class="text-decoration-none fw-bold">';
                    echo legibleYMD($anho, $mes, $dia);
                echo '</a>' . "\n";
            } else {
                echo legibleYMD($anho, $mes, $dia);
            }
        echo '</td>' . "\n";

        echo '<td class="pe-3 text-end text-nowrap align-middle">';
                if (existsEntry($_SESSION['user'], $date)) {
                echo '<a href="/entry/edit.php?date=' . $date . '">';
                    echo '<button class="btn btn-sm btn-info text-white me-1">Escribir</button>';
                echo '</a>' . "\n";

                echo '<button class="btn btn-sm btn-warning" onclick="a_eliminar=\'' . $date . '\';id_eliminar=' . $dia . ';on_show_modal();" data-bs-toggle="modal" data-bs-target="#myModal">';
                    echo '<i class="bi bi-trash text-dark"></i>';
                echo '</button>';
            } else {
                echo '<a href="/entry/new.php?date=' . $date . '">';
                    echo '<button class="btn btn-sm btn-info text-white">Escribir</button>' . "\n";
                echo '</a>' . "\n";
            }
        echo '</td>' . "\n";

        echo '</tr>' . "\n";
    }
?>
