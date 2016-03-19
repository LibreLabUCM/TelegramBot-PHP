<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api Chat
 *
 * @api
 *
 */
class TA_Chat {
  private $_api; // TelegramApi
  private $id;
  private $type;
  private $title;
  private $username;
  private $first_name;
  private $last_name;

  private function TA_Chat(TelegramApi $api, $id, $type, $title = null, $username = null, $first_name = null, $last_name = null) {
    $this->_api = $api;
    $this->id = $id;
    $this->type = $type;
    $this->title = $title;
    $this->username = $username;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
  }

  /**
   * Creates a TA_Chat from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Chat
   *
   * @return a TA_Chat object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Chat::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Chat from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Chat
   *
   * @return a TA_Chat object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['id'],
          $arr['type'],
          isset($arr['title'])       ? $arr['title']      : null,
          isset($arr['username'])    ? $arr['username']   : null,
          isset($arr['first_name'])  ? $arr['first_name'] : null,
          isset($arr['last_name'])   ? $arr['last_name']  : null
        );
  }

  /**
   * Gets the chat id
   *
   * Unique identifier for this chat, not exceeding 1e13 by absolute value
   *
   * @return int chat id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets the chat type
   *
   * Type of chat, can be either “private”, “group”, “supergroup” or “channel”
   *
   * @return string chat type
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Gets the chat title
   *
   * Title, for channels and group chats
   *
   * @return string chat title
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Gets the chat username
   *
   * Username, for private chats and channels if available
   *
   * @return string chat username
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Gets the chat first name
   *
   * First name of the other party in a private chat
   *
   * @return string chat first name
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * Gets the chat last name
   *
   * Last name of the other party in a private chat
   *
   * @return string chat last name
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * Sends a meessage to this chat
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
  public function sendMessage($text, $link_previews = true, $reply_markup = null) {
    return $this->_api->sendMessage($this, $text, $link_previews, null, $reply_markup);
  }
}
