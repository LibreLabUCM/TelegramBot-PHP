<?php
require_once('./TelegramBot/BotConfig.php');
require_once('./TelegramBot/TelegramApi.php');


class TelegramBot {
	private $config;
	private $api;
	
	public function TelegramBot(BotConfig $config) {
		if ($config->isValid ()) {
			$this->config = $config;
			$this->api = new TelegramApi ( $config->getToken () );
		} else {
			echo 'Bot is NOT configured properly!';
			// Exception!
		}
	}
	
	public function processUpdate($update) {
		if (!is_array($update)) $update = json_decode($update, true);
		$update_id = $update['update_id'];
		
		if (isset($update['message'])) {
			return $this->processMessage(TA_Message::createFromArray($update['message']));
		} else if (isset($update['inline_query'])) {
			return $inline_query = TA_InlineQuery::createFromArray($update['inline_query']);
		} else {
			// Exception! This is not a message or an inline query....
		}
	}
	
	public function processMessage(TA_Message $message) {
		if ($message->getText() === "/test") {
			$t = $this->api->sendMessage($message->getFrom(), "Answer!");
			return $t->getText();
		} else {
			$t = $this->api->sendMessage($message->getFrom(), "We are refactoring!");
			return $t->getText();
		}
		return true;
	}
	
	public function processInlineQuery(TA_InlineQuery $inline_query) {
		
		return true;
	}
}
