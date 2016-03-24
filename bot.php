<?php
$bot = require_once('setup.php');

$update = file_get_contents("php://input");

try {
  $bot->processUpdate($update);
  http_response_code(200);
  echo 'Ok';
} catch (Exception $e) {
  http_response_code(500);
  echo 'Error: '.$e->getMessage();
}
