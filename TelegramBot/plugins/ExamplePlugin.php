<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class ExamplePlugin extends Plugin {
   private $api;
   
   public function ExamplePlugin() {
      $this->api = TelegramApi::getInstance();
   }
   
   public function onInit() {
      
   }
   
   public function isHungry(TA_User $user) {
      return false;
   }
   
   public function onMessageReceived_PreProcess(TA_Message $message) {
      
   }
   
   public function onMessageReceived(TA_Message $message) {
      if ($message->getText() === "/example") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "Answer!");
         return $t->getText();
      }
      return false;
   }
}
