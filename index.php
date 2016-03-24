<?php
$bot = require_once('setup.php');

if (isset($_GET['setwebhook'])) {
  if ($bot->setWebhook() == 1) {
    echo 'Webhook set!';
  } else {
    echo 'Webhook NOT set!';
  }
  exit();
}
