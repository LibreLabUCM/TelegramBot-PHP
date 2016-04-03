<?php
require_once(__DIR__ . '/BotConfig.php');
require_once(__DIR__ . '/TelegramApi/TelegramApi.php');
require_once(__DIR__ . '/PluginManager.php');
date_default_timezone_set('Europe/Madrid');


class InvalidConfigException extends Exception { }
class InvalidKeyException extends Exception { }

class TelegramBot {
  private $config;
  private $api;
  private $pluginManager;
  private $username = 'DevPGSVbot'; // Shouldn't be hardcoded

  public function TelegramBot(BotConfig $config) {
    if (!$config->isValid ()) {
      throw new InvalidConfigException('Bot is NOT configured properly!');
    }
    $this->config = $config;
    $this->api = new TelegramApi ( $config->getToken () );
    $this->pluginManager = new PluginManager($this->api, $this);
    $this->pluginManager->registerAll();
    //$username = $this->api->getMe()->getUsername();
    //echo '<a href="https://telegram.me/'.$username.'" target="_blank">@'.$username."</a><br>\n";
  }

  public function setWebhook() {
    $url = $this->config->getWebhookUrl() . '?key=' . $this->config->getHookKey();
    return $this->api->setWebhook($url);
  }

  public function getBotUsername() {
    return $this->username;
  }

  public function processUpdate($update) {
    if (empty($update)) throw new Exception('Empty update!');
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
    //$message->sendReply($message->getMessageId());
    //$message->sendReply($message->getMedia()->getFileId());
    //return;
    if ($message->hasText()) {
      $this->pluginManager->onEvent('textMessageReceived', $message);
      // $this->api->sendMessage($message->getFrom(), '@'.$message->getFrom()->getUsername() . ' ('.date('m/d/y h:i:s', $message->getDate()).'):'."\n" . $message);
    } else if ($message->hasMedia()) {
      $this->pluginManager->onEvent('mediaMessageReceived', $message);
    } else if ($message->isNewChatParticipant()) {
      $this->api->sendMessage($message->getChat(), "Welcome " . $message->getNewChatParticipant()->getFirstName());
    } else if ($message->isLeftChatParticipant()) {
      if ($message->getLeftChatParticipant()->getUsername() !== 'DevPGSVbot')
        $this->api->sendMessage($message->getChat(), "Bye " . $message->getLeftChatParticipant()->getFirstName());
    } else if ($message->isGroupChatCreated()) {
      $this->api->sendMessage($message->getChat(), "Howdy!");
    } else if ($message->isGroupMigratedToChatId()) {
      $this->api->sendMessage($message->getMigratedToChatId(), "So... this is now a supergroup!");
    } else if ($message->isGroupMigratedFromChatId()) {
      $this->api->sendMessage($message->getChat(), "So... this is no longer a group!");
    }
    /*
    $myfile = fopen("./files/log.txt", "a");
    fwrite($myfile, "\n--------------------\n".print_r($message, true)."\n--------------------\n");
    fclose($myfile);
    $this->api->sendMessage($message->getFrom(), "What have you sent me???");
    */

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

  public function processChosenInlineResult(TA_ChosenInlineResult $inlineQueryResult) {
    $myfile = fopen("./files/log.txt", "a");
    fwrite($myfile, "\n--------------------\n".$inlineQueryResult->getQuery()."\n--------------------\n");
    fclose($myfile);
  }

  public function sendMsg($from, $text) {
    $this->api->sendMessage($from, $text);
  }

  public function sendPhoto($to, $photo) {
    $this->api->sendPhoto($to, $photo);
  }
}
