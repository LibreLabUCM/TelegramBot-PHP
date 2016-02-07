<?php
ini_set('display_errors','1'); error_reporting(E_ALL);

require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');
require_once('./TelegramBot/TelegramApi.php');

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

$api = new TelegramApi($config->getToken());

echo '<pre>', print_r($api->getMe(), true), '</pre>';
//echo '<pre>', print_r($api->sendMessage($testId, "Test"), true), '</pre>';
//echo '<pre>', print_r($api->sendChatAction($testId, "typing"), true), '</pre>';
//echo '<pre>', print_r($api->getUserProfilePhotos($testId), true), '</pre>';

