<?php
require_once(__DIR__ . '/../PluginManager.php');


class HelloWorldPlugin extends TB_Plugin {
  public function HelloWorldPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   * %condition text matches ^hi$
   */
  public function onMessageReceived($message) {
    $message->sendReply("Hello world from HelloWorldPlugin!");
  }

}
return array(
  'class' => 'HelloWorldPlugin',
  'name' => 'Hello World',
  'id' => 'HelloWorldPlugin'
);
