<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class CommandsPlugin extends Plugin {
   
   public function CommandsPlugin() {
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
      if ($message->getText() === "/commands") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "commands");
         return $t->getText();
      }
      return false;
   }
}
