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

  public function getId() {
    return $this->id;
  }

  public function getType() {
    return $this->type;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getUsername() {
    return $this->username;
  }

  public function getFirstName() {
    return $this->first_name;
  }

  public function getLastName() {
    return $this->last_name;
  }

  public function sendMessage($text, $link_previews = true, $reply_markup = null) {
    $this->$_api->sendMessage($this, $text, $link_previews, null, $reply_markup);
    return false;
  }
}
