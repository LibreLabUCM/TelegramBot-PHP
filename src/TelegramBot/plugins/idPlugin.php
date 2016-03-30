<?php
require_once(__DIR__ . '/../PluginManager.php');

return 'IdPlugin';
class IdPlugin extends TB_Plugin {
  public function IdPlugin($api) {
    parent::__construct($api);
  }

  public function onMessageReceived($message) {
    if ($message->hasText()) {
      if ($message->getText() === "/id" || $message->getText() === "/start id") {
        $message->sendReply($message->getFrom()->getId());
      }
    }
    if ($message->isForwarded()) {
      $message->sendReply("Forwarded from: ".$message->getForwardFrom()->getId());
    }
  }
}
