<?php

define('ROOT', realpath(__DIR__ . '/../'));

require_once ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

if (!file_exists(ROOT . '/storage/data.db'))
{
    require_once ROOT . '/seed_db.php';
}

require_once ROOT . '/app/routes.php';