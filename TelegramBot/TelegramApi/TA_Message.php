<?php
require_once(__DIR__ . '/TA_User.php');
require_once(__DIR__ . '/TA_Chat.php');


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
  private $audio;
  private $document;
  private $photo;
  private $sticker;
  private $video;
  private $voice;
  private $caption;
  private $contact;
  private $location;
  private $new_chat_participant; // TA_User
  private $left_chat_participant; // TA_User
  private $new_chat_title;
  private $new_chat_photo;
  private $delete_chat_photo;
  private $group_chat_created;
  private $channel_chat_created;
  private $migrate_to_chat_id;
  private $migrate_from_chat_id;

  private function TA_Message($api, $message_id, $date, TA_Chat $chat, TA_User $from = null, TA_User $fordward_from = null, $forward_date = null, TA_Message $reply_to_message = null,
      $text = null, $audio = null, $document = null, $photo = null, $sticker = null, $video = null, $voice = null, $caption = null, $contact = null, $location = null,
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
    $this->audio = $audio;
    $this->document = $document;
    $this->photo = $photo;
    $this->sticker = $sticker;
    $this->video = $video;
    $this->voice = $voice;
    $this->caption = $caption;
    $this->contact = $contact;
    $this->location = $location;
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

  public static function createFromJson($api, $json) {
    return TA_Message::createFromArray($api, json_decode($json));
  }

  public static function createFromArray($api, $arr) {
    return new Self(
          $api,
          $arr['message_id'],
          $arr['date'],
          TA_Chat::createFromArray($arr['chat']),
          isset($arr['from'])                    ? TA_User::createFromArray($arr['from'])                 : null,
          isset($arr['fordward_from'])           ? TA_User::createFromArray($arr['fordward_from'])        : null,
          isset($arr['forward_date'])            ? $arr['forward_date']								                 		: null,
          isset($arr['reply_to_message'])        ? TA_Message::createFromArray($arr['reply_to_message'])  : null,
          isset($arr['text'])                    ? $arr['text']                                           : null,
          isset($arr['audio'])                   ? $arr['audio']                                          : null,
          isset($arr['document'])                ? $arr['document']                                       : null,
          isset($arr['photo'])                   ? $arr['photo']                                          : null,
          isset($arr['sticker'])                 ? $arr['sticker']                                        : null,
          isset($arr['video'])                   ? $arr['video']                                          : null,
          isset($arr['voice'])                   ? $arr['voice']                                          : null,
          isset($arr['caption'])                 ? $arr['caption']                                        : null,
          isset($arr['contact'])                 ? $arr['contact']                                        : null,
          isset($arr['location'])                ? $arr['location']                                       : null,
          isset($arr['new_chat_participant'])    ? TA_User::createFromArray($arr['new_chat_participant']) : null,
          isset($arr['left_chat_participant'])   ? TA_User::createFromArray($arr['left_chat_participant']): null,
          isset($arr['new_chat_title'])          ? $arr['new_chat_title']                                 : null,
          isset($arr['new_chat_photo'])          ? $arr['new_chat_photo']                                 : null,
          isset($arr['delete_chat_photo'])       ? $arr['delete_chat_photo']                              : null,
          isset($arr['group_chat_created'])      ? $arr['group_chat_created']                             : null,
          isset($arr['channel_chat_created'])    ? $arr['channel_chat_created']                           : null,
          isset($arr['migrate_to_chat_id'])      ? $arr['migrate_to_chat_id']                             : null,
          isset($arr['migrate_from_chat_id'])    ? $arr['migrate_from_chat_id']                           : null
        );
  }

  public function getMessageId() {
    return $this->message_id;
  }

  public function getText() {
    return $this->text;
  }

  public function getFrom() {
    return $this->from;
  }

  public function hasText() {
    return ($this->text !== null);
  }

  public function isForwarded() {
    return ($this->fordward_from !== null);
  }

  public function getDate() {
    return $this->date;
  }

  public function getChat() {
    return $this->chat;
  }

  public function getFordwardFrom() {
    return $this->fordward_from;
  }

  public function getForwardDate() {
    return $this->forward_date;
  }

  public function isReply() {
    return ($this->reply_to_message !== null);
  }

  public function getReplyToMessage() {
    return $this->reply_to_message;
  }

  public function hasMedia() {
    return
      ($this->audio     !== null) ||
      ($this->document  !== null) ||
      ($this->photo     !== null) ||
      ($this->sticker   !== null) ||
      ($this->video     !== null) ||
      ($this->voice     !== null) ||
      ($this->contact   !== null) ||
      ($this->location  !== null);
  }

  public function isAudio() {
    return ($this->audio !== null);
  }

  public function getAudio() {
    return $this->audio;
  }

  public function isDocument() {
    return ($this->document !== null);
  }

  public function getDocument() {
    return $this->document;
  }

  public function isPhoto() {
    return ($this->photo !== null);
  }

  public function getPhoto() {
    return $this->photo;
  }

  public function isSticker() {
    return ($this->sticker !== null);
  }

  public function getSticker() {
    return $this->sticker;
  }

  public function isVideo() {
    return ($this->video !== null);
  }

  public function getVideo() {
    return $this->video;
  }

  public function isVoice() {
    return ($this->voice !== null);
  }

  public function getVoice() {
    return $this->voice;
  }

  public function isContact() {
    return ($this->contact !== null);
  }

  public function getContact() {
    return $this->contact;
  }

  public function isLocation() {
    return ($this->location !== null);
  }

  public function getLocation() {
    return $this->location;
  }

  public function hasCaption() {
    return ($this->caption !== null);
  }

  public function getVaption() {
    return $this->caption;
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
}
