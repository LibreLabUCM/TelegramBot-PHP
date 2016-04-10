<?php
require_once(__DIR__ . '/../PluginManager.php');


class HelpPlugin extends TB_Plugin {
  public function HelpPlugin($api, $bot) {
    parent::__construct($api, $bot);
  }

  /**
   * %condition date isNew
   * %condition text matches ^\/(?:help|start)(?:@{#USERNME})?$
   */
  public function onMessageReceived($message) {
    $message->sendReply("Developing... If you want to /test ...");
  }
}
return array(
  'class' => 'HelpPlugin',
  'name' => 'Help',
  'id' => 'HelpPlugin'
);
