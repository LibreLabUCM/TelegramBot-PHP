<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class GetWarriorPlugin extends Plugin {
   
   public function GetWarriorPlugin() {
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
      if ($message->getText() === "/getwarrior") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "get warrior");
         // Cookies! (Hungry)
         return $t->getText();
      }
      return false;
   }
}
