<?php
require_once(__DIR__ . '/../PluginManager.php');


class WelcomePlugin extends TB_Plugin {
  public function WelcomePlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   */
  public function onMessageReceived($message) {
    if ($message->isNewChatParticipant()) {
      if ($message->getNewChatParticipant()->getUsername() !== $this->bot->getBotUsername())
        $message->sendReply("Welcome " . $message->getNewChatParticipant()->getFirstName());
      else
        $message->sendReply("Hello! :)");
    } else if ($message->isLeftChatParticipant()) {
      if ($message->getLeftChatParticipant()->getUsername() !== $this->bot->getBotUsername())
        $message->sendReply("Bye " . $message->getLeftChatParticipant()->getFirstName());
    }
  }

}
return array(
  'class' => 'WelcomePlugin',
  'name' => 'Welcome Plugin',
  'id' => 'WelcomePlugin'
);
