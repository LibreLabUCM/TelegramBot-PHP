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

  public function getChangeLog() {
    return [
      '1459717139' => [
        'version'=>[0, 0, 0, 'alpha'],
        'changes' => [
          'Welcomes new chat members when the join and bids farewell when they leave',
        ],
      ],
      '1460314018' => [
        'version'=>[0, 1, 0, 'alpha'],
        'changes' => [
          'Added condition "isNew"',
        ],
      ],
      '1460810520' => [
        'version'=>[0, 2, 0, 'alpha'],
        'changes' => [
          'Added "getChangeLog" function',
        ],
      ],
    ];
  }

}
return array(
  'class' => 'WelcomePlugin',
  'name' => 'Welcome Plugin',
  'id' => 'WelcomePlugin',
  'version' => [0, 2, 0, 'alpha'],
);
