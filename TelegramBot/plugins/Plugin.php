<?php
require_once('TelegramBot/TelegramApi.php');

abstract class Plugin {
   
   public function Plugin() {
   }
   
   abstract public function onInit();
   abstract public function isHungry(TA_User $user);
   abstract public function onMessageReceived_PreProcess(TA_Message $message);
   abstract public function onMessageReceived(TA_Message $message);
}
