<?php
require_once(__DIR__ . '/../PluginManager.php');

return 'HelloWorldPlugin';
class HelloWorldPlugin extends TB_Plugin {
  public function HelloWorldPlugin($api) {
    parent::__construct($api);
  }

  public function onMessageReceived($message) {
    if ($message->hasText()) {
      if ($message->getText() === "hi") {
        $this->api->sendMessage($message->getFrom(), 'Hello world from HelloWorldPlugin');
      }
    }
  }
}
