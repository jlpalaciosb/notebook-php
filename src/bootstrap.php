<?php

define("BASE_PATH", realpath(__DIR__));

require_once BASE_PATH . '/lib/utilities.php';

$config = array(
    'db' => array(
        'name' => 'postgres',
        'user' => 'postgres',
        'pass' => obtenerEnv('POSTGRES_PASSWORD', 'postgres'),
        'host' => 'postgres'
    )
);
