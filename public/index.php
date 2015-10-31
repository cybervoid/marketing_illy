<?php

define('ROOT', realpath(__DIR__ . '/../'));

require_once ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

require_once ROOT . '/app/routes.php';