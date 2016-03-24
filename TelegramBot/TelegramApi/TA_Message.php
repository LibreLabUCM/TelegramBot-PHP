<?php
require_once(__DIR__ . '/TelegramApi.php');
require_once(__DIR__ . '/TA_User.php');
require_once(__DIR__ . '/TA_Chat.php');
require_once(__DIR__ . '/TA_File.php');


/**
 * Telegram Api Message
 *
 * @api
 *
 */
class TA_Message {
  private $_api; // TelegramApi
  private $message_id;
  private $from; // TA_User
  private $date;
  private $chat; // TA_Chat
  private $fordward_from; // TA_User
  private $forward_date;
  private $reply_to_message; // TA_Message
  private $text;
  private $audio; // TA_Audio
  private $document; // TA_Document
  private $photo;
  private $sticker; // TA_Sticker
  private $video; // TA_Video
  private $voice; // TA_Voice
  private $caption;
  private $contact; // TA_Contact
  private $location; // TA_Location
  private $new_chat_participant; // TA_User
  private $left_chat_participant; // TA_User
  private $new_chat_title;
  private $new_chat_photo;
  private $delete_chat_photo;
  private $group_chat_created;
  private $channel_chat_created;
  private $migrate_to_chat_id;
  private $migrate_from_chat_id;

  private function TA_Message(TelegramApi $api, $message_id, $date, TA_Chat $chat, TA_User $from = null, TA_User $fordward_from = null, $forward_date = null, TA_Message $reply_to_message = null,
      $text = null, TA_Audio $audio = null, TA_Document $document = null, TA_Photo $photo = null, TA_Sticker $sticker = null, TA_Video $video = null, TA_Voice $voice = null, $caption = null, TA_Contact $contact = null, TA_Location $location = null,
      TA_User $new_chat_participant = null, TA_User $left_chat_participant = null, $new_chat_title = null, $new_chat_photo = null, $delete_chat_photo = null,
      $group_chat_created = null, $channel_chat_created = null, $migrate_to_chat_id = null, $migrate_from_chat_id = null) {

    $this->_api = $api;
    $this->message_id = $message_id;
    $this->date = $date;
    $this->chat = $chat;
    $this->from = $from;
    $this->fordward_from = $fordward_from; // TA_User
    $this->forward_date = $forward_date;
    $this->reply_to_message = $reply_to_message; // TA_Message
    $this->text = $text;
    $this->audio = $audio; // TA_Audio
    $this->document = $document; // TA_Document
    $this->photo = $photo; // TA_Photo
    $this->sticker = $sticker; // TA_Sticker
    $this->video = $video; // TA_Video
    $this->voice = $voice; // TA_Voice
    $this->caption = $caption;
    $this->contact = $contact; // TA_Contact
    $this->location = $location; // TA_Location
    $this->new_chat_participant = $new_chat_participant; // TA_User
    $this->left_chat_participant = $left_chat_participant; // TA_User
    $this->new_chat_title = $new_chat_title;
    $this->new_chat_photo = $new_chat_photo;
    $this->delete_chat_photo = $delete_chat_photo;
    $this->group_chat_created = $group_chat_created;
    $this->channel_chat_created = $channel_chat_created;
    $this->migrate_to_chat_id = $migrate_to_chat_id;
    $this->migrate_from_chat_id = $migrate_from_chat_id;
  }

  /**
  * Creates a TA_Message from a json string
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	a json string representing a TA_Message
  *
  * @return a TA_Message object
  */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Message::createFromArray($api, json_decode($json, true));
  }

  /**
  * Creates a TA_Message from an associative array
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	an associative array representing a TA_Message
  *
  * @return a TA_Message object
  */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['message_id'],
          $arr['date'],
          TA_Chat::createFromArray($api, $arr['chat']),
          isset($arr['from'])                    ? TA_User::createFromArray($api, $arr['from'])                 : null,
          isset($arr['fordward_from'])           ? TA_User::createFromArray($api, $arr['fordward_from'])        : null,
          isset($arr['forward_date'])            ? $arr['forward_date']								                 	     	  : null,
          isset($arr['reply_to_message'])        ? TA_Message::createFromArray($api, $arr['reply_to_message'])  : null,
          isset($arr['text'])                    ? $arr['text']                                                 : null,
          isset($arr['audio'])                   ? TA_Audio::createFromArray($api, $arr['audio'])               : null,
          isset($arr['document'])                ? TA_Document::createFromArray($api, $arr['document'])         : null,
          isset($arr['photo'])                   ? TA_Photo::createFromArray($api, $arr['photo'])             : null,
          isset($arr['sticker'])                 ? TA_Sticker::createFromArray($api, $arr['sticker'])           : null,
          isset($arr['video'])                   ? TA_Video::createFromArray($api, $arr['video'])               : null,
          isset($arr['voice'])                   ? TA_Voice::createFromArray($api, $arr['voice'])               : null,
          isset($arr['caption'])                 ? $arr['caption']                                              : null,
          isset($arr['contact'])                 ? TA_Contact::createFromArray($api, $arr['contact'])          : null,
          isset($arr['location'])                ? TA_Location::createFromArray($api, $arr['location'])         : null,
          isset($arr['new_chat_participant'])    ? TA_User::createFromArray($api, $arr['new_chat_participant']) : null,
          isset($arr['left_chat_participant'])   ? TA_User::createFromArray($api, $arr['left_chat_participant']): null,
          isset($arr['new_chat_title'])          ? $arr['new_chat_title']                                       : null,
          isset($arr['new_chat_photo'])          ? $arr['new_chat_photo']                                       : null,
          isset($arr['delete_chat_photo'])       ? $arr['delete_chat_photo']                                    : null,
          isset($arr['group_chat_created'])      ? $arr['group_chat_created']                                   : null,
          isset($arr['channel_chat_created'])    ? $arr['channel_chat_created']                                 : null,
          isset($arr['migrate_to_chat_id'])      ? $arr['migrate_to_chat_id']                                   : null,
          isset($arr['migrate_from_chat_id'])    ? $arr['migrate_from_chat_id']                                 : null
        );
  }

  /**
   * Gets the message id
   *
   * Unique message identifier
   *
   * @return int message id
   */
  public function getMessageId() {
    return $this->message_id;
  }

  /**
   * Gets the message text
   *
   * For text messages, the actual UTF-8 text of the message, 0-4096 characters.
   *
   * @return string message text
   */
  public function getText() {
    return $this->text;
  }

  /**
   * Gets the message sender
   *
   * Sender, can be empty for messages sent to channels
   *
   * @return TA_User message sender
   */
  public function getFrom() {
    return $this->from;
  }

  /**
   * Checks if the message contains text
   *
   * @return boolean if the message contains text
   */
  public function hasText() {
    return ($this->text !== null);
  }

  /**
   * Checks if the message has been fordwarded
   *
   * @return boolean if the message has been fordwarded
   */
  public function isForwarded() {
    return ($this->fordward_from !== null);
  }

  /**
   * Gets the message date
   *
   * Date the message was sent in Unix time
   *
   * @return int message date
   */
  public function getDate() {
    return $this->date;
  }

  /**
   * Gets the message chat
   *
   * Conversation the message belongs to
   *
   * @return TA_Chat message chat
   */
  public function getChat() {
    return $this->chat;
  }

  /**
   * Gets the sender of the original message
   *
   * For forwarded messages, sender of the original message
   *
   * @return TA_User sender of the original message
   */
  public function getFordwardFrom() {
    return $this->fordward_from;
  }

  /**
   * Gets the date the original message was sent
   *
   * For forwarded messages, date the original message was sent in Unix time
   *
   * @return int date the original message was sent
   */
  public function getForwardDate() {
    return $this->forward_date;
  }

  /**
   * Checks if the message is a reply
   *
   * @return boolean if the message is a reply
   */
  public function isReply() {
    return ($this->reply_to_message !== null);
  }

  /**
   * Gets the message the current message is a reply to
   *
   * For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
   *
   * @return TA_Message [description]
   */
  public function getReplyToMessage() {
    return $this->reply_to_message;
  }

  /**
   * Checks if the current message contains audio
   *
   * @return boolean if the current message contains audio
   */
  public function isAudio() {
    return ($this->audio !== null);
  }

  /**
   * Gets the message audio
   *
   * Message is an audio file, information about the file
   *
   * @return unspecified message audio
   */
  public function getAudio() {
    return $this->audio;
  }

  /**
   * Checks if the current message contains a document
   *
   * @return boolean if the current message contains a document
   */
  public function isDocument() {
    return ($this->document !== null);
  }

  /**
   * Gets the message document
   *
   * Message is a general file, information about the file
   *
   * @return unspecified message document
   */
  public function getDocument() {
    return $this->document;
  }

  /**
   * Checks if the current message contains a photo
   *
   * @return boolean if the current message contains a photo
   */
  public function isPhoto() {
    return ($this->photo !== null);
  }

  /**
   * Gets the message photo
   *
   * Message is a photo, available sizes of the photo
   *
   * @return unspecified message photo
   */
  public function getPhoto() {
    return $this->photo;
  }

  /**
   * Checks if the current message contains a sticker
   *
   * @return boolean if the current message contains a sticker
   */
  public function isSticker() {
    return ($this->sticker !== null);
  }

  /**
   * Gets the message sticker
   *
   * Message is a sticker, information about the sticker
   *
   * @return unspecified message sticker
   */
  public function getSticker() {
    return $this->sticker;
  }

  /**
   * Checks if the current message contains a video
   *
   * @return boolean if the current message contains a video
   */
  public function isVideo() {
    return ($this->video !== null);
  }

  /**
   * Gets the message video
   *
   * Message is a video, information about the video
   *
   * @return unspecified message video
   */
  public function getVideo() {
    return $this->video;
  }

  /**
   * Checks if the current message contains a voice
   *
   * @return boolean if the current message contains a voice
   */
  public function isVoice() {
    return ($this->voice !== null);
  }

  /**
   * Gets the message voice
   *
   * Message is a voice message, information about the file
   *
   * @return unspecified message voice
   */
  public function getVoice() {
    return $this->voice;
  }

  /**
   * Checks if the current message contains a contact
   *
   * @return boolean if the current message contains a contact
   */
  public function isContact() {
    return ($this->contact !== null);
  }

  /**
   * Gets the message contact
   *
   * Message is a shared contact, information about the contact
   *
   * @return unspecified message contact
   */
  public function getContact() {
    return $this->contact;
  }

  /**
   * Checks if the current message contains a location
   *
   * @return boolean if the current message contains a location
   */
  public function isLocation() {
    return ($this->location !== null);
  }

  /**
   * Gets the message location
   *
   * Message is a shared location, information about the location
   *
   * @return unspecified message location
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * Gets the message media content
   *
   * @return unspecified message media content
   */
  public function getMedia() {
    if ($this->isAudio()) return $this->getAudio();
    if ($this->isDocument()) return $this->getDocument();
    if ($this->isPhoto()) return $this->getPhoto();
    if ($this->isSticker()) return $this->getSticker();
    if ($this->isVideo()) return $this->getVideo();
    if ($this->isVoice()) return $this->getVoice();
    if ($this->isContact()) return $this->getContact();
    if ($this->isLocation()) return $this->getLocation();
    return false;
  }

  /**
   * Gets the messgage media type
   *
   * @return string message media type
   */
  public function getMediaType() {
    if ($this->isAudio()) return 'audio';
    if ($this->isDocument()) return 'document';
    if ($this->isPhoto()) return 'photo';
    if ($this->isSticker()) return 'sticker';
    if ($this->isVideo()) return 'video';
    if ($this->isVoice()) return 'voice';
    if ($this->isContact()) return 'contact';
    if ($this->isLocation()) return 'location';
    return false;
  }

  /**
   * Checks if the message contains media
   *
   * @return boolean if the message contains media
   */
  public function hasMedia() {
    return ($this->getMediaType() !== false);
  }

  /**
   * Checks if the message contains a caption
   *
   * @return boolean if the message contains a caption
   */
  public function hasCaption() {
    return ($this->caption !== null);
  }

  /**
   * Gets the message caption
   *
   * @return string message caption
   */
  public function getCaption() {
    return $this->caption;
  }

  /**
   * Sends a reply to this message
   *
   * @param string $text
   *         text to send as a message
   * @param bool $link_previews
   *         (Optional) If link previews should be shown (Default: true)
   * @param mixed $reply_markup
   *         (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message the result of the api request
   */
  public function sendReply($text, $link_previews = true, $reply_markup = null) {
    return $this->_api->sendMessage($this->getChat()->getId(), $text, $link_previews, $this->getMessageId(), $reply_markup);
  }




  // TODO Add all getters
  /*
  private $new_chat_participant; // TA_User
  private $left_chat_participant; // TA_User
  private $new_chat_title;
  private $new_chat_photo;
  private $delete_chat_photo;
  private $group_chat_created;
  private $channel_chat_created;
  private $migrate_to_chat_id;
  private $migrate_from_chat_id;
  */



  public function __toString() {
    if ($this->hasText())
      return $this->getText();

    if ($this->hasMedia()) {
      $ret = $this->getMediaType();
      if ($this->hasCaption()) {
        $ret .= ': ' . $this->getCaption();
      }
      return $ret;
    }
    return 'Unknown message';
  }
}
