<?php
$_BOT_CONFIG = array(
  'token' => '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11',
  'baseUrl' => 'https://example.com/TelegramBot/src/', //
  'db' => array(
    'host' => 'mongodb://localhost:27017', // Database host
    'database' => 'TelegramBot' // Database name
  ),
  'admins' => array(12345678, 87654321, 11111111), // Array of admin id's. Not used right now...
  'hookKey' => 'ChangeThisToSomethingRandom' // Something like: 561gbrz566zsw . After changing this, you should set the webhook again!
);
