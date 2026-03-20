<?php

defined("LIBRARY_PATH") || define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
     
defined("TEMPLATES_PATH") || define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

require_once LIBRARY_PATH . '/utilities.php';

$config = array(
    'db' => array(
        'name' => 'postgres',
        'user' => 'postgres',
        'pass' => obtenerEnv('POSTGRES_PASSWORD', 'postgres'),
        'host' => 'postgres'
    )
);

