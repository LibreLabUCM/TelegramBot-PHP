<?php
require_once('./TelegramBot/BotConfig.php');
require_once('./TelegramBot/TelegramApi.php');


class TelegramBot {
   private $config;
   private $api;
   
   
   public function TelegramBot(BotConfig $config) {
      if ($config->isValid()) {
         $this->config = $config;
         $this->api = new TelegramApi($config->getToken());
      } else {
         echo 'Bot is NOT configured properly!';
         // Exception!
      }
   }
}
