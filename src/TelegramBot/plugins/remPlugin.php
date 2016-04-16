<?php
require_once(__DIR__ . '/../PluginManager.php');


class RemPlugin extends TB_Plugin {
  public function RemPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   * %condition text matches ^rem\?$
   */
  public function onMessageReceived($message) {
    $message->sendReply("Rem!");
  }

  public function getChangeLog() {
    return [
      '1460722200' => [
        'version'=>[0, 0, 0, 'alpha'],
        'changes' => [
          'Created plugin',
        ],
      ],
      '1460810520' => [
        'version'=>[0, 1, 0],
        'changes' => [
          'Added "getChangeLog" function',
        ],
      ],
    ];
  }

}
return array(
  'class' => 'RemPlugin',
  'name' => 'Rem',
  'id' => 'RemPlugin',
  'version' => [0, 1, 0, 'alpha'],
);
