<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class RegisterPlugin extends Plugin {
   
   public function RegisterPlugin() {
      //PluginManager::getInstance()->addPlugin($this);
   }
   
   public function onInit() {
      //echo 'RegisterPlugin loaded!';
   }
   
   public function isHungry(TA_User $user) {
      return false;
   }
   
   public function onMessageReceived_PreProcess(TA_Message $message) {
      
   }
   
   public function onMessageReceived(TA_Message $message) {
      if ($message->getText() === "/register") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "Registering is no longer necessary!");
         return $t->getText();
      }
      return false;
   }
}
