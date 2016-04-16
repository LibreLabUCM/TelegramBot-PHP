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

}
return array(
  'class' => 'RemPlugin',
  'name' => 'Rem',
  'id' => 'RemPlugin'
);
