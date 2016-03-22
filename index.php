<?php
ini_set('display_errors','1'); error_reporting(E_ALL);

require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');
require_once('./config_file.php'); // $_BOT_CONFIG

$config = BotConfig::create()
  -> setToken($_BOT_CONFIG['token'])
  -> setWebhookUrl($_BOT_CONFIG['webhookUrl'])
  -> setDb($_BOT_CONFIG['db'])
  -> setAdmins($_BOT_CONFIG['admins'])
  -> setHookKey($_BOT_CONFIG['hookKey'])
  -> validate();

try {
  $bot = new TelegramBot($config);
} catch (InvalidConfigException $e) {
  die('Error on creating bot: '.$e->getMessage());
}

if (isset($_GET['setwebhook'])) {
  if ($bot->setWebhook() == 1) {
    echo 'Webhook set!';
  } else {
    echo 'Webhook NOT set!';
  }
  exit();
}

if (empty($update))
  $update = file_get_contents("php://input");

if (!empty($update)) {
  echo $bot->processUpdate($update);
} else {
  echo 'Nothing to see here...';
}
