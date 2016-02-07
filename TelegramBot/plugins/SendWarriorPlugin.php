<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class SendWarriorPlugin extends Plugin {
   
   public function SendWarriorPlugin() {
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
      if ($message->getText() === "/sendwarrior") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "send warrior");
         // Cookies! (Hungry)
         return $t->getText();
      }
      return false;
   }
}
