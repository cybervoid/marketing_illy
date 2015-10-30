<?php

$db = new PDO(getenv('DSN'));


$db->exec("
    CREATE TABLE user (
      id INTEGER PRIMARY KEY,
      created_ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      modified_ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      username VARCHAR(255) NOT NULL,
      password VARCHAR(40) NOT NULL,
      role Boolean NOT NULL
    );
");



$db->exec("
  INSERT INTO
    `user` (
      username, password, role
    )
  VALUES (
    'illy', 'test', 'FALSE'
  )
");

$db->exec("
  INSERT INTO
    `user` (
      username, password, role
    )
  VALUES (
    'admin', 'test', 'TRUE'
  )
");


/*
$result = $db->query("SELECT * FROM user",PDO::FETCH_ASSOC);
var_dump($result->fetchAll()); die();
*/