<?php

$bot = require_once('setup.php');

if (empty($_GET['key']) || $_GET['key'] !== $config->getHookKey()) {
  throw new InvalidConfigException('Invalid key!');
}

$update = file_get_contents("php://input");

try {
  $bot->processUpdate($update);
  http_response_code(200);
  echo 'Ok';
} catch (Exception $e) {
  http_response_code(500);
  die('Error: '.$e->getMessage());
}
