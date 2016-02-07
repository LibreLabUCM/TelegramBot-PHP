<?php
require_once('Plugin.php');
require_once('TelegramBot/TelegramApi.php');

class HelpPlugin extends Plugin {
   
   public function HelpPlugin() {
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
      if ($message->getText() === "/help") {
         $t = TelegramApi::getInstance()->sendMessage($message->getFrom(), 
            "You can submit warriors with /sendwarrior\n".
            "If you want, you can /participate or /retreat for the following tournaments.\n".
            "When you participate, your fighter (choose with /choosewarrior ) will fight in the core of the next tournaments.\n".
            "Tournaments happen from time to time, you will be notified of the results.\n".
            "Full list of /commands here!\n".
            "This bot is in *alpha*!\n".
            "(No tournaments scheduled)");
         return $t->getText();
      }
      return false;
   }
}
