<?php
require_once(__DIR__ . '/TA_User.php');
require_once(__DIR__ . '/TA_Message.php');
require_once(__DIR__ . '/TA_InlineQuery.php');
require_once(__DIR__ . '/TA_InlineQueryResult.php');
require_once(__DIR__ . '/TA_Update.php');
require_once(__DIR__ . '/TA_ReplyMarkup.php');


class TAE_ApiException extends Exception{}

/**
 * Telegram api wrapper.
 *
 * @api
 *
 */
class TelegramApi {
  private $token;


  public function TelegramApi($token) {
    $this->token = $token;
  }

  /**
   * Sends an api request to telegram
   *
   * @param string $method
   *        	the api method
   * @param array $params
   *        	(Optional) the parameters of the method (Default: null)
   * @param void $file
   *        	(Optional) (Unused) (Default: null)
   *
   * @return array the result of the api request
   */
  private function sendApiRequest($method, $params = null, $file = null) {
    if ($params === null) {
      $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
    } else {
      if (is_array ( $params )) {
        $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method . '?' . http_build_query ( $params );
      } else {
        throw new Exception('$params should be an array!');
      }
    }

    $curl = curl_init ();
    curl_setopt_array ( $curl, array (
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url,
      CURLOPT_SSL_VERIFYPEER => false,
      // CURLOPT_FOLLOWLOCATION => true
    ));
    $response = curl_exec ( $curl );
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if(curl_error($curl)) {
      throw new Exception('Request error: ' . curl_error($curl));
    }
    $r = json_decode ( $response, true );
    if ($http_status != 200) {
      throw new Exception('Request error: status ' . $http_status);
    }
    if ($r === FALSE || $r === null || !$r['ok']) {
      throw new TAE_ApiException($r['description']);
    }

    echo '<pre>', json_encode($r, JSON_PRETTY_PRINT), '</pre>';
    return $r['result'];
  }

  /**
   * Sends a getMe request to get info about the bot
   *
   * @return TA_User the result of the api request
   */
  public function getMe() {
    return TA_User::createFromArray($this, $this->sendApiRequest ( 'getMe' ));
  }

  /**
   * Sends a message by sending a sendMessage api request
   *
   * @param int $chat_id
   *         id of the user the message is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $text
   *         text to send as a message
   * @param bool $link_previews
   *         (Optional) If link previews should be shown (Default: true)
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendMessage($chat_id, $text, $link_previews = true, $reply_id = null, $reply_markup = null, $parse_mode = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();
    if ($chat_id instanceof TA_Chat) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['text'] = (string)$text;

    if ($link_previews === true || $link_previews === null) {
      $options ['disable_web_page_preview'] = false;
    } else if ($link_previews === false) {
      $options ['disable_web_page_preview'] = true;
    }

    if ($reply_id !== null) {
      if ($reply_id instanceof TA_Message) $reply_id = $reply_id->getMessageId();
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = (string)$reply_markup;
    }
    if ($parse_mode !== null) {
      $options ['parse_mode'] = (string)$parse_mode;
    }
    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendMessage', $options ));
  }

  /**
   * Forwards a message from origin to destination chat
   *
   * @param int $chat_id
   *         destination chat
   * @param int $from_chat_id
   *         origin chat
   * @param int $message_id
   *         id of the message in origin chat to fordward
   *
   * @return mixed the result of the api request
   */
  public function forwardMessage($chat_id, $from_chat_id, $message_id) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['from_chat_id'] = $from_chat_id;
    $options ['message_id'] = $message_id;
    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'forwardMessage', $options ));
  }

  /**
   * Sends a photo
   *
   * @param int $chat_id
   *         id of the user the photo is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $photo
   *         file_id of the photo
   * @param string $caption
   *         (Optional) Caption for the photo (Default: "")
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendPhoto($chat_id, $photo, $caption = "", $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;

    if ($photo instanceof TA_Photo) $options ['photo'] = $photo->getFileByIndex()->getFile()->getFileId();
    else if ($photo instanceof TA_PhotoSize) $options ['photo'] = $photo->getFile()->getFileId();
    else $options ['photo'] = $photo;

    if ($caption !== null && $caption !== "") {
      $options ['caption'] = $caption;
    }

    if ($reply_id !== null) {
      if ($reply_id instanceof TA_Message) $reply_id = $reply_id->getMessageId();
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendPhoto', $options ));
  }

  /**
   * Sends an audio
   *
   * @param int $chat_id
   *         id of the user the audio is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $audio
   *         file_id of the audio
   * @param int $duration
   *         (Optional) Duration of the audio (Default: "")
   * @param string $performer
   *         (Optional) performer of the audio (Default: "")
   * @param string $title
   *         (Optional) title of the audio (Default: "")
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendAudio($chat_id, $audio, $duration = "", $performer = "", $title = "", $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['audio'] = $audio;

    if ($duration !== null && $duration !== "") {
      $options ['duration'] = $duration;
    }

    if ($performer !== null && $performer !== "") {
      $options ['performer'] = $performer;
    }

    if ($title !== null && $title !== "") {
      $options ['title'] = $title;
    }

    if ($reply_id !== null) {
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendAudio', $options ));
  }

  /**
   * Sends a document
   *
   * @param int $chat_id
   *         id of the user the document is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $document
   *         file_id of the document
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendDocument($chat_id, $document, $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['document'] = $document;

    if ($reply_id !== null) {
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendDocument', $options ));
  }

  /**
   * Sends a sticker
   *
   * @param int $chat_id
   *         id of the user the sticker is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $sticker
   *         file_id of the sticker
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendSticker($chat_id, $sticker, $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['sticker'] = $sticker;

    if ($reply_id !== null) {
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendSticker', $options ));
  }

  // Untested
  public function sendVideo($chat_id, $video, $duration = null, $caption = "", $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['video'] = $video;

    if ($duration !== null) {
      $options ['duration'] = $duration;
    }

    if ($caption !== null && $caption !== "") {
      $options ['caption'] = $caption;
    }

    if ($reply_id !== null) {
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendVideo', $options ));
  }

  /**
   * Sends a voice message
   *
   * @param int $chat_id
   *         id of the user the voice message is going to be sent to (accepted: int, TA_User, TA_Chat)
   * @param string $voice
   *         file_id of the voice message
   * @param int $duration
   *         Duration of the audio
   * @param boolean $disable_notification
   *         (Optional) If the message should not generate a notification (Default: false)
   * @param int $reply_id
   *         (Optional) Mark the message as a reply to other message in the same conversation (accepted: int, TA_Message)(Default: null)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendVoice($chat_id, $voice, $duration, $disable_notification = false, $reply_id = null, $reply_markup = null) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['voice'] = $voice;

    if ($duration !== null) {
      $options ['duration'] = $duration;
    }

    $options ['disable_notification'] = $disable_notification;

    if ($reply_id !== null) {
      $options ['reply_to_message_id'] = $reply_id;
    }

    if ($reply_markup !== null) {
      $options ['reply_markup'] = json_encode ( $reply_markup );
    }

    return TA_Message::createFromArray($this, $this->sendApiRequest ( 'sendVoice', $options ));
  }

  /**
   * Sends an api request to show a chat action for the client
   *
   * @param int $chat_id target chat
   * @param string $action string representing the action.
   *
   * @return mixed the result of the api request
   */
  public function sendChatAction($chat_id, $action) {
    if ($chat_id instanceof TA_User) $chat_id = $chat_id->getId();

    $availableActions = array (
      'typing',
      'upload_photo',
      'record_video',
      'upload_video',
      'record_audio',
      'upload_audio',
      'upload_document',
      'find_location'
    );

    if (! in_array ( $action, $availableActions )) {
      throw new TAE_Exception('Unknown action: ' . $action);
    }

    $options = array ();
    $options ['chat_id'] = $chat_id;
    $options ['action'] = $action;
    return (bool)$this->sendApiRequest ( 'sendChatAction', $options );
  }

  /**
   * Gets the user profile photos
   *
   * @param  int     $user_id user id
   * @param  int     $offset  (Optional) Sequential number of the first photo to be returned. By default, all photos are returned. (Default: 0)
   * @param  int     $limit   (Optional) Limits the number of photos to be retrieved. Values between 1—100 are accepted. (Defaults: 100)
   *
   * @return TA_UserProfilePhotos      the user profile photos
   */
  public function getUserProfilePhotos($user_id, $offset = 0, $limit = 100) {
    if ($user_id instanceof TA_User) $user_id = $user_id->getId();

    $options = array ();
    $options ['user_id'] = $user_id;
    $options ['offset'] = $offset;
    $options ['limit'] = $limit;
    return TA_UserProfilePhotos::createFromArray($this, $this->sendApiRequest ( 'getUserProfilePhotos', $options ));
  }

  /**
   * Gets a file by its file id
   *
   * @param  string $file_id the id of the file
   *
   * @return TA_File file object
   */
  public function getFile($file_id) {
    $options = array ();
    $options ['file_id'] = $file_id;
    return TA_File::createFromArray($this, $this->sendApiRequest ( 'getFile', $options ));
  }

  // Certificate not used (for now)
  /**
   * Sets the webhook url
   *
   * @param string $url         new webhook. Use an empty string or null to remove the webhook
   * @param mixed $certificate  not used!
   *
   * @return bool if the webhook was set correctly
   */
  public function setWebhook($url = null, $certificate = null) {
    $options = array ();
    if ($url !== null)
      $options ['url'] = $url;
    else
      $options ['url'] = '';
    return (bool)$this->sendApiRequest ( 'setWebhook', $options );
  }


  /**
   * Answer an inline query request
   *
   * @param string $inline_query_id id of the query to answer
   * @param TA_InlineQueryResultArray $results results to show to the user
   * @param int $cache_time (Optional) time the server can save the answer in cache (Default: 0)
   * @param bool $is_personal (Optional) if the answer should not be the same for other users (Default: true)
   * @param string $next_offset  (Optional) (Default: "")
   *
   * @return mixed the result of the api request
   */
  public function answerInlineQuery($inline_query_id, TA_InlineQueryResultArray $results, $cache_time = 0, $is_personal = true, $next_offset = "") {
    $options = array ();
    $options ['inline_query_id'] = $inline_query_id;
    $options ['results'] = $results->toJson();
    $options ['cache_time'] = $cache_time;
    $options ['is_personal'] = $is_personal;
    $options ['next_offset'] = $next_offset;
    return $this->sendApiRequest ( 'answerInlineQuery', $options );
  }

  /**
   * Download a TA_File by its file_path
   *
   * @param  string $file_path file_path provided by getFile()
   *
   * @return string            path to the downloaded temp file
   */
  public function downloadFile($file_path) {
    $url = 'https://api.telegram.org/file/bot'.$this->token.'/'.$file_path;

    $newfname = tempnam(sys_get_temp_dir(), 'Tg');
    $file = fopen ($url, 'rb');
    if ($file) {
      $newf = fopen ($newfname, 'wb');
      if ($newf) {
        while(!feof($file)) {
          fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
        }
      }
    }
    if ($file) {
      fclose($file);
    }
    if ($newf) {
      fclose($newf);
    }
    return $newfname;
  }

}
