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
    if (!is_array($update)) $update = json_decode($update, true);
    $update_id = $update['update_id'];

    if (isset($update['message'])) {
      //return $this->api->sendMessage(TA_Message::createFromArray($this->api, $update['message'])->getFrom(), print_r($update['message'], true));
      return $this->processMessage(TA_Message::createFromArray($this->api, $update['message']));
    } else if (isset($update['inline_query'])) {
      return $inline_query = TA_InlineQuery::createFromArray($this->api, $update['inline_query']);
    } else {
      throw new Exception('This is not a message or an inline query!');
    }
  }

  public function processMessage(TA_Message $message) {
    if ($message->hasText()) {
      if ($message->getText() === "/help" || $message->getText() === "/start") {
        $t = $this->api->sendMessage($message->getFrom(), "Developing...");
        return $t->getText();
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
    } else  {
      return $this->api->sendMessage($message->getFrom(), "What have you sent me???");
    }
    return false;
  }

  public function processInlineQuery(TA_InlineQuery $inline_query) {
    return false;
  }
}
