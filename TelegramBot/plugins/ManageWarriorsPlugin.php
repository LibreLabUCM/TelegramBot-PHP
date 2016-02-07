<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class ManageWarriorsPlugin extends Plugin {
   
   public function ManageWarriorsPlugin() {
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
      if ($message->getText() === "/managewarriors") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), "manage warriors");
         // Cookies! (Hungry)
         return $t->getText();
      }
      return false;
   }
}
