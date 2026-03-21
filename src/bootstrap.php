<?php

define("BASE_PATH", realpath(__DIR__));

require_once BASE_PATH . '/lib/utilities.php';

define('CONFIG', [
    'db' => [
        'name' => 'postgres',
        'user' => 'postgres',
        'pass' => obtenerEnv('POSTGRES_PASSWORD', 'postgres'),
        'host' => 'postgres'
    ]
]);

define('CONSTANTS', [
    'meses' => [
        "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre"
    ]
]);
