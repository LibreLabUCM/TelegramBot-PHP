<?php
require_once(__DIR__ . '/../PluginManager.php');

return 'HelpPlugin';
class HelpPlugin extends TB_Plugin {
  public function HelpPlugin($api) {
    parent::__construct($api);
  }

  public function onMessageReceived($message) {
    if ($message->hasText()) {
      if ($message->getText() === "/help" || $message->getText() === "/start") {
        $this->api->sendMessage($message->getFrom(), "Developing... If you want to /test ...");
      }
    }
  }
}
