<?php
ini_set('display_errors','1'); error_reporting(E_ALL);
if (!file_exists('./config_file.php')) {
  header('Location: install.php');
  echo 'install first!';
  exit();
} else {
    require_once('./config_file.php'); // $_BOT_CONFIG
    if (!isset($_BOT_CONFIG)) {
      header('Location: install.php');
      echo 'install first!';
      exit();
    }
}
if (!extension_loaded("mongodb")) {
  echo 'MongoDB not found!';
  exit();
}

require_once(__DIR__ . '/../vendor/autoload.php');
$db = (new MongoDB\Client($_BOT_CONFIG['db']['host']))
  ->selectDatabase($_BOT_CONFIG['db']['database']);


require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');


$config = BotConfig::create()
  -> setToken($_BOT_CONFIG['token'])
  -> setWebhookUrl($_BOT_CONFIG['baseUrl'].'bot.php')
  -> setBaseUrl($_BOT_CONFIG['baseUrl'])
  -> setDb($_BOT_CONFIG['db'])
  -> setAdmins($_BOT_CONFIG['admins'])
  -> setHookKey($_BOT_CONFIG['hookKey'])
  -> validate();
unset($_BOT_CONFIG);

try {
  $bot = new TelegramBot($config, $db);
  return $bot;
} catch (InvalidConfigException $e) {
  http_response_code(500);
  die('Error on creating bot: '.$e->getMessage());
}
