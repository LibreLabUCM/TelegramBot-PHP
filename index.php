<?php
ini_set('display_errors','1'); error_reporting(E_ALL);

require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');

$testId = 1;
$token = '123456';


$config = BotConfig::create()
   -> setToken($token)
   -> setWebhookUrl('WebhookUrl')
   -> setDb('DB')
   -> setAdmins('Admins')
   -> setHookKey('HookKey')
   -> validate();


$bot = new TelegramBot($config);

$update = file_get_contents("php://input");
echo $bot->processUpdate($update);
