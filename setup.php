<?php
ini_set('display_errors','1'); error_reporting(E_ALL);

require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');
require_once('./config_file.php'); // $_BOT_CONFIG

$config = BotConfig::create()
  -> setToken($_BOT_CONFIG['token'])
  -> setWebhookUrl($_BOT_CONFIG['baseUrl'].'bot.php')
  -> setDb($_BOT_CONFIG['db'])
  -> setAdmins($_BOT_CONFIG['admins'])
  -> setHookKey($_BOT_CONFIG['hookKey'])
  -> validate();

try {
  $bot = new TelegramBot($config);
  unset($_BOT_CONFIG);
  unset($config);
  return $bot;
} catch (InvalidConfigException $e) {
  http_response_code(500);
  die('Error on creating bot: '.$e->getMessage());
}