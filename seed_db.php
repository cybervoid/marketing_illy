#!/usr/bin/php
<?php

define('ROOT', realpath(__DIR__));

require_once ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$db = new PDO(sprintf(getenv('DSN'), ROOT));

$db->exec("
    CREATE TABLE user (
      id INTEGER PRIMARY KEY,
      created_ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      modified_ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      username VARCHAR(255) NOT NULL,
      password VARCHAR(40) NOT NULL,
      role INTEGER NOT NULL
    );
");


$db->exec("
  INSERT INTO
    `user` (
      username, password, role
    )
  VALUES (
    'illyuser', 'illymap', 1
  )
");

$db->exec("
  INSERT INTO
    `user` (
      username, password, role
    )
  VALUES (
    'admin', 'Welcome!123', 0
  )
");


/*
$result = $db->query("SELECT * FROM user",PDO::FETCH_ASSOC);
var_dump($result->fetchAll()); die();
*/