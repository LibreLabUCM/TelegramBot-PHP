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

    if ($updateObj->hasMessage()) {
      //return $this->api->sendMessage($updateObj->getMessage()->getFrom(), print_r(json_decode($update, true)['message'], true));
      $this->processMessage($updateObj->getMessage());
    } else if ($updateObj->hasInlineQuery()) {
      $this->processInlineQuery($updateObj->getInlineQuery());
    } else if ($updateObj->hasChosenInlineResult()) {
      $this->processChosenInlineResult($updateObj->getChosenInlineResult());
    } else {
      throw new Exception('This is not a message or an inline query!');
    }
  }

  public function processMessage(TA_Message $message) {
    if ($message->hasText()) {
      if ($message->getText() === "/help" || $message->getText() === "/start") {
        $t = $this->api->sendMessage($message->getFrom(), "Developing... If you want to /test ...");
      } else if ($message->getText() === "/test") {
        $k = new TA_ReplyKeyboardMarkup([['/test'],['/test_reply'],['/test_typing'],['/test_forceReplay'],['/test_keyboard'],['/test_hideKeyboard'],['/test_profilephotos']], null, true);
        $this->api->sendMessage($message->getFrom(), "/test\n/test_reply\n/test_typing\n/test_forceReplay\n/test_keyboard\n/test_hideKeyboard\n/test_profilephotos", null, null, $k);
      } else if ($message->getText() === "/test_keyboard") {
        $k = new TA_ReplyKeyboardMarkup([[' - - - ']]); // 0
        $k->addRow()->addOption("/test_hideKeyboard") // 1
          ->addRow()->addOption("A") // 2
          ->addRow()->addOption("C")->addOption("D") // 2
          ->addOption("B", 2); // Add "B" to row 2
        $this->api->sendMessage($message->getFrom(), "Keyboard! Hide with /test_hideKeyboard", null, null, $k);
      } else if ($message->getText() === "/test_hideKeyboard") {
        $this->api->sendMessage($message->getFrom(), "Hide!", null, null, new TA_ReplyKeyboardHide());
      } else if ($message->getText() === "/test_reply") {
        // $this->api->sendMessage($message->getFrom(), "Reply to message with id: " . $message->getMessageId(), null, $message->getMessageId());
        $message->sendReply("Reply to message with id: " . $message->getMessageId());
      } else if ($message->getText() === "/test_typing") {
        $this->api->sendChatAction($message->getFrom(), "typing");
      } else if ($message->getText() === "/test_forceReplay") {
        $this->api->sendMessage($message->getFrom(), "Reply to me!", null, null, new TA_ForceReply());
      } else if ($message->getText() === "/test_profilephotos") {
        $profilePhotos = $this->api->getUserProfilePhotos($message->getFrom());
        //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto($profilePhotos->getNumberOfPhotos() - 1), "First"); // Gets first profile photo
        //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto(0), "Current"); // Gets last (current) profile photo
        foreach ($profilePhotos->getAll() as $key => $photo) {
          $this->api->sendPhoto($message->getFrom(), $photo, $key, $message);
        }
      } else if ($message->getText() === "/id" || $message->getText() === "/start id") {
        $message->sendReply($message->getFrom()->getId());
      } else {
        $this->api->sendMessage($message->getFrom(), '@'.$message->getFrom()->getUsername() . ' ('.date('m/d/y h:i:s', $message->getDate()).'):'."\n" . $message);
      }
    } else if ($message->hasMedia()) {
      $f = $message->getMedia();
      if ($f->hasFile()) {
        $this->api->sendMessage($message->getFrom(), "I haven't downloaded your ".$message->getMediaType()."....\nI have deactivated it ;)");

        /*
        // Download the media file and answer with the link to the file downloaded
        $this->api->sendChatAction($message->getFrom(), "typing");
        $finalPath = 'files/'.$message->getDate() .'-'. $message->getMediaType() .'.'. $f->getFileExtension();
        $downloadPath = $f->downloadFile();
        rename($downloadPath, $finalPath);
        $this->api->sendMessage($message->getFrom(), $message->getMediaType()."!\n" .  $this->config->getWebhookUrl().$finalPath);
        */
      } else {
        if ($message->isLocation()) {
          $this->api->sendMessage($message->getFrom(), "So... you are at\n" . $f->getLongitude() . "\n" . $f->getLatitude());
        } else if ($message->isContact()) {
          $this->api->sendMessage($message->getFrom(), "Name: ".$f->getFirstName()."\nPhone: ".$f->getPhoneNumber());
        } else {
          $this->api->sendMessage($message->getFrom(), "I can't understand that media message!");
        }
      }
    } else {
      $this->api->sendMessage($message->getFrom(), "What have you sent me???");
    }
  }

  public function processInlineQuery(TA_InlineQuery $inline_query) {
    $jpg = 'https://pixabay.com/static/uploads/photo/2015/10/01/21/39/background-image-967820_960_720.jpg';
    $ans = new TA_InlineQueryResultArray();
    $ans

      // Articles
      ->addResult(new TA_InlineQueryResultArticle($this->api, TA_InlineQueryResult::$incrementalId++, "Query", empty($inline_query->getQuery())?'Empty!':$inline_query->getQuery()))
      ->addResult(new TA_InlineQueryResultArticle($this->api, TA_InlineQueryResult::$incrementalId++, "User", '@'.$inline_query->getFrom()->getUsername()))
      ->addResult(new TA_InlineQueryResultArticle($this->api, TA_InlineQueryResult::$incrementalId++, "Bold", "B*ol*d!", "Markdown", null, null, null, "Bold 'ol'!"))
      ->addResult(new TA_InlineQueryResultArticle($this->api, TA_InlineQueryResult::$incrementalId++, "Italic", "It_ali_c!", "Markdown", null, null, null, "Italic 'ali'!"))

      // Photos:
      ->addResult(new TA_InlineQueryResultPhoto($this->api, TA_InlineQueryResult::$incrementalId++, $jpg, $jpg, $jpg))
      ->addResult(new TA_InlineQueryResultPhoto($this->api, TA_InlineQueryResult::$incrementalId++, $jpg, $jpg, $jpg));

    $ret = (bool)$this->api->answerInlineQuery($inline_query->getId(), $ans);
    if ($ret === false) {
      throw new Exception('Couldn\'t answer the inline query!');
    }
  }
}
