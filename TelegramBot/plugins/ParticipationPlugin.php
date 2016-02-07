<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class ParticipationPlugin extends Plugin {
   
   public function ParticipationPlugin() {
      //PluginManager::getInstance()->addPlugin($this);
   }
   
   public function onInit() {
      
   }
   
   public function isHungry(TA_User $user) {
      return false;
   }
   
   public function onMessageReceived_PreProcess(TA_Message $message) {
      
   }
   
   public function onMessageReceived(TA_Message $message) {
      if ($message->getText() === "/participate") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "Participate!");
         return $t->getText();
      } else if ($message->getText() === "/retreat") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "Don't participate!");
         return $t->getText();
      }
      return false;
   }
}
