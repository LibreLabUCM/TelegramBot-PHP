<?php
ini_set('display_errors','1'); error_reporting(E_ALL);

require_once('./TelegramBot/TelegramBot.php');
require_once('./TelegramBot/BotConfig.php');

$testId = 1;
$token = '123456';


$config = BotConfig::create()
   -> setToken($token)
   -> setWebhookUrl('https://example.com')
   -> setDb('DB')
   -> setAdmins('Admins')
   -> setHookKey('KEY')
   -> validate();


$bot = new TelegramBot($config);

if (isset($_GET['setwebhook'])) {
	if ($bot->setWebhook() == 1) {
		echo 'Webhook set!';
	} else {
		echo 'Webhook NOT set!';
	}
	exit();
}

$update = file_get_contents("php://input");
if (!empty($update)) {
	echo $bot->processUpdate($update);
} else {
	echo 'Nothing to see here...';
}

