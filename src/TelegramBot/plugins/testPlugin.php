<?php
require_once(__DIR__ . '/../PluginManager.php');


class TestPlugin extends TB_Plugin {
  public function TestPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition text matches ^\/test.*
   */
  public function onTextMessageReceived($message, &$eventData, $method) {
    $t = str_replace('@'.$this->bot->getBotUsername(), '', trim($message->getText()));
    if ($t === "/test") {
      $k = new TA_ReplyKeyboardMarkup([['/test', '/test_reply', '/test_typing', '/test_edit'],['/test_forceReplay', '/test_keyboard', '/test_inline_keyboard'],['/test_hideKeyboard', '/test_profilephotos', '/test_info'],['/test_entities mentions like @telegram or commands like /cmd, or even links like http://example.com']], null, true);
      $this->api->sendMessage($message->getFrom(), "/test\n/test_reply\n/test_typing\n/test_forceReplay\n/test_keyboard\n/test_inline_keyboard\n/test_hideKeyboard\n/test_profilephotos\n/test_entities mentions, link and commands here\n/test_info\n/test_edit", null, null, $k);
    } else if ($t === "/test_keyboard") {
      $k = new TA_ReplyKeyboardMarkup(); // 0
      $k->addRow()->addOption("/test_hideKeyboard") // 1
        ->addRow()->addOption("A") // 2
        ->addOption("B", 2) // Add "B" to row 2
        ->addRow()
          ->addOption(new TA_KeyboardButton('Contact', true))
          ->addOption(new TA_KeyboardButton('Location', null, true));
      $this->api->sendMessage($message->getFrom(), "Keyboard! Hide with /test_hideKeyboard", null, null, $k);
    } else if ($t === "/test_inline_keyboard") {
      $k = new TA_InlineKeyboardMarkup(); // 0
      $k->addOption(new TA_InlineKeyboardButton('Inline!', null, null, ":)")) // 2
        ->addRow()
          ->addOption(new TA_InlineKeyboardButton('Maths!', null, "0"))
          ->addOption(new TA_InlineKeyboardButton('Maths!', null, "0"));
      $this->api->sendMessage($message->getFrom(), "Keyboard! Hide with /test_hideKeyboard", null, null, $k);
    } else if ($t === "/test_hideKeyboard") {
      $this->api->sendMessage($message->getFrom(), "Hide!", null, null, new TA_ReplyKeyboardHide());
    } else if ($t === "/test_reply") {
      // $this->api->sendMessage($message->getFrom(), "Reply to message with id: " . $message->getMessageId(), null, $message->getMessageId());
      $message->sendReply("Reply to message with id: " . $message->getMessageId());
    } else if ($t === "/test_typing") {
      $this->api->sendChatAction($message->getFrom(), "typing");
    } else if ($t === "/test_forceReplay") {
      $this->api->sendMessage($message->getFrom(), "Reply to me!", null, null, new TA_ForceReply());
    } else if ($t === "/test_profilephotos") {
      $profilePhotos = $this->api->getUserProfilePhotos($message->getFrom());
      //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto($profilePhotos->getNumberOfPhotos() - 1), "First"); // Gets first profile photo
      //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto(0), "Current"); // Gets last (current) profile photo
      foreach ($profilePhotos->getAll() as $key => $photo) {
        $this->api->sendPhoto($message->getFrom(), $photo, $key, $message);
      }
    } else if ($t === "/test_info") {
      $p = $method['priority'];
      $t = $message->getText();
      $c = $eventData['cancelled'] ? 'yes' : 'no';
      $message->sendReply("Priority: $p\nText: $t\nCancelled: $c");
    } else if ($t === "/test_ban") {
      $this->api->kickChatMember($message->getChat(), $message->getReplyToMessage()->getFrom());
    } else if ($t === "/test_unban") {
      $this->api->unbanChatMember($message->getChat(), $message->getReplyToMessage()->getFrom());
    } else if(substr($t, 0, strlen('/test_entities')) === '/test_entities') {
      //$message->sendReply(print_r($message->getEntities(), true));
      $msg = '';
      foreach($message->loopEntities() as $entity) {
        $msg .= $entity->getType().': '.$message->getEntityText($entity)."\n";
      }
      $message->sendReply($msg);
    } else if ($t === "/test_edit") {

      $m = $this->api->sendMessage($message->getFrom(), "0", null, null, null, 'Markdown');
      // $this->api->editMessageText($m->getChat(), $m->getMessageId(), "Edit!");
      for($i = 1; $i <= 5; $i++) {
        usleep(10000);
        $m->editMessageText($i);
      }
      usleep(400000); $m->editMessageText("Bum!");
    }
  }

  public function getChangeLog() {
    return [
      '1459382160' => [
        'version'=>[0, 0, 0, 'alpha'],
        'changes' => [
          'Created plugin',
        ],
      ],
      '1459709160' => [
        'version'=>[0, 1, 0, 'alpha'],
        'changes' => [
          'Added "isNew" condition',
          'Changed plugin structure',
        ],
      ],
      '1460732820' => [
        'version'=>[0, 2, 0, 'alpha'],
        'changes' => [
          'Added "/test_edit" command',
        ],
      ],
      '1460746020' => [
        'version'=>[0, 3, 0, 'alpha'],
        'changes' => [
          'Improved keyboard tests and added inline keyboard test',
        ],
      ],
      '1460804040' => [
        'version'=>[0, 4, 0, 'alpha'],
        'changes' => [
          'Improved inline keyboard',
        ],
      ],
      '1460810520' => [
        'version'=>[0, 5, 0, 'alpha'],
        'changes' => [
          'Added "getChangeLog" function',
        ],
      ],
    ];
  }

}
return array(
  'class' => 'TestPlugin',
  'name' => 'Test',
  'id' => 'TestPlugin',
  'version' => [0, 5, 0, 'alpha'],
);
