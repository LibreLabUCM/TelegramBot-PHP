<?php
require_once(__DIR__ . '/BotConfig.php');
require_once(__DIR__ . '/TelegramApi/TelegramApi.php');
date_default_timezone_set('Europe/Madrid');


class InvalidConfigException extends Exception { }
class InvalidKeyException extends Exception { }

class TelegramBot {
  private $config;
  private $api;

  public function TelegramBot(BotConfig $config) {
    if (empty($_GET['key']) || $_GET['key'] !== $config->getHookKey()) {
      throw new InvalidConfigException('Invalid key!');
    }
    if (!$config->isValid ()) {
      throw new InvalidConfigException('Bot is NOT configured properly!');
    }
    $this->config = $config;
    $this->api = new TelegramApi ( $config->getToken () );
    $username = $this->api->getMe()->getUsername();
    echo '<a href="https://telegram.me/'.$username.'" target="_blank">@'.$username."</a><br>\n";
  }

  public function setWebhook() {
    $url = $this->config->getWebhookUrl() . '?key=' . $this->config->getHookKey();
    return $this->api->setWebhook($url);
  }

  public function processUpdate($update) {
    if (!is_array($update)) $updateObj = TA_Update::createFromJson($this->api, $update);
    else                    $updateObj = TA_Update::createFromArray($this->api, $update);
    echo 'Update: '.$updateObj->getType()."<br>\n";
    if ($updateObj->hasMessage()) {
      //return $this->api->sendMessage($updateObj->getMessage()->getFrom(), print_r(json_decode($update, true)['message'], true));
      return $this->processMessage($updateObj->getMessage());
    } else if ($updateObj->hasInlineQuery()) {
      return $this->processInlineQuery($updateObj->getInlineQuery());
    } else if ($updateObj->hasChosenInlineResult()) {
      return $this->processChosenInlineResult($updateObj->getChosenInlineResult());
    } else {
      throw new Exception('This is not a message or an inline query!');
    }
  }

  public function processMessage(TA_Message $message) {
    if ($message->hasText()) {
      if ($message->getText() === "/help" || $message->getText() === "/start") {
        $t = $this->api->sendMessage($message->getFrom(), "Developing... If you want to /test ...");
        return $t->getText();
      } else if ($message->getText() === "/test") {
        $k = new TA_ReplyKeyboardMarkup([['/test'],['/test_reply'],['/test_typing'],['/test_keyboard'],['/test_hideKeyboard']], null, true);
        return $this->api->sendMessage($message->getFrom(), "/test\n/test_reply\n/test_typing\n/test_keyboard\n/test_hideKeyboard", null, null, $k);
      } else if ($message->getText() === "/test_keyboard") {
        $k = new TA_ReplyKeyboardMarkup([[' - - - ']]); // 0
        $k->addRow()->addOption("/test_hideKeyboard") // 1
          ->addRow()->addOption("A") // 2
          ->addRow()->addOption("C")->addOption("D") // 2
          ->addOption("B", 2); // Add "B" to row 2
        return $this->api->sendMessage($message->getFrom(), "Keyboard! Hide with /test_hideKeyboard", null, null, $k);
      } else if ($message->getText() === "/test_hideKeyboard") {
        return $this->api->sendMessage($message->getFrom(), "Hide!", null, null, new TA_ReplyKeyboardHide());
      } else if ($message->getText() === "/test_reply") {
        //return $this->api->sendMessage($message->getFrom(), "Reply to message with id: " . $message->getMessageId(), null, $message->getMessageId());
        return $message->sendReply("Reply to message with id: " . $message->getMessageId());
      } else if ($message->getText() === "/test_typing") {
        $this->api->sendChatAction($message->getFrom(), "typing");
        return 'Typing';
      } else if ($message->getText() === "/id" || $message->getText() === "/start id") {
        return $message->sendReply($message->getFrom()->getId());
      } else {
        return $this->api->sendMessage($message->getFrom(), '@'.$message->getFrom()->getUsername() . ' ('.date('m/d/y h:i:s', $message->getDate()).'):'."\n" . $message);
      }
    } else if ($message->hasMedia()) {
      $f = $message->getMedia();
      if ($f->hasFile()) {
        return $this->api->sendMessage($message->getFrom(), "I haven't downloaded your ".$message->getMediaType()."....\nI have deactivated it ;)");

        /*
        // Download the media file and answer with the link to the file downloaded
        $finalPath = 'files/'.$message->getDate() .'-'. $message->getMediaType() .'.'. $f->getFileExtension();
        $downloadPath = $f->downloadFile();
        rename($downloadPath, $finalPath);
        return $this->api->sendMessage($message->getFrom(), $message->getMediaType()."!\n" .  $this->config->getWebhookUrl().$finalPath);
        */
      } else {
        if ($message->isLocation()) {
          return $this->api->sendMessage($message->getFrom(), "So... you are at\n" . $f->getLongitude() . "\n" . $f->getLatitude());
        } else if ($message->isContact()) {
          return $this->api->sendMessage($message->getFrom(), "Name: ".$f->getFirstName()."\nPhone: ".$f->getPhoneNumber());
        } else {
          return $this->api->sendMessage($message->getFrom(), "I can't understand that media message!");
        }
      }
    }
    return $this->api->sendMessage($message->getFrom(), "What have you sent me???");
  }

  public function processInlineQuery(TA_InlineQuery $inline_query) {
    return false;
  }
}
