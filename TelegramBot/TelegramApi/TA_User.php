<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api User
 *
 * @api
 *
 */
class TA_User {
  private $_api; // TelegramApi
  private $id;
  private $first_name;
  private $last_name;
  private $username;

  private function TA_User(TelegramApi $api, $id, $first_name, $last_name = null, $username = null) {
    $this->_api = $api;
    $this->id = $id;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->username = $username;
  }

  /**
  * Creates a TA_User from a json string
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	a json string representing a TA_User
  *
  * @return a TA_User object
  */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_User::createFromArray($api, json_decode($json, true));
  }

  /**
  * Creates a TA_User from an associative array
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	an associative array representing a TA_User
  *
  * @return a TA_User object
  */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
      $api,
      $arr['id'],
      $arr['first_name'],
      isset($arr['last_name'])   ? $arr['last_name']  : null,
      isset($arr['username'])    ? $arr['username']   : null
    );
  }

  /**
   * Gets the user id
   *
   * @return int user id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets the user first name
   *
   * @return string user first name
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * Gets the user last name
   *
   * @return string user last name
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * Gets the username
   *
   * @return string username
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Send a message to the user
   *
   * @param  string $text          text to send as a message
   * @param  bool   $link_previews (Optional) If link previews should be shown (Default: true)
   * @param  mixed  $reply_markup  (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
   *
   * @return TA_Message            the result of the api request
   */
  public function sendMessage($text, $link_previews = true, $reply_markup = null) {
    return $this->$_api->sendMessage($this, $text, $link_previews, null, $reply_markup);
  }

  /**
   * Gets the user profile photos
   *
   * @param  int     $offset  (Optional) Sequential number of the first photo to be returned. By default, all photos are returned. (Default: 0)
   * @param  int     $limit   (Optional) Limits the number of photos to be retrieved. Values between 1—100 are accepted. (Defaults: 100)
   * @return TA_UserProfilePhotos      the user profile photos
   */
  public function getUserProfilePhotos($offset = 0, $limit = 100) {
    return $this->$_api->getUserProfilePhotos($this, $offset, $limit);
  }
}
