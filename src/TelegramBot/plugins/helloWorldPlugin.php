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

  public function getChangeLog() {
    return [
      '1459382160' => [
        'version'=>[0, 0, 0, ''],
        'changes' => [
          'Created plugin',
        ],
      ],
    ];
  }

}
return array(
  'class' => 'HelloWorldPlugin',
  'name' => 'Hello World',
  'id' => 'HelloWorldPlugin',
  'version' => [0, 0, 0, ''],
);
