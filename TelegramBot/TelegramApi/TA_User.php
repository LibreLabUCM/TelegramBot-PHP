<?php
require_once(__DIR__ . '/TelegramApi.php');


class TA_User {
  private $_api; // TelegramApi
  private $id;
  private $first_name;
  private $last_name;
  private $username;

  private function TA_User($api, $id, $first_name, $last_name = null, $username = null) {
    $this->_api = $api;
    $this->id = $id;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->username = $username;
  }

  public static function createFromJson($api, $json) {
    return TA_User::createFromArray($api, json_decode($json));
  }

  public static function createFromArray($api, $arr) {
    return new Self(
      $api,
      $arr['id'],
      $arr['first_name'],
      isset($arr['last_name'])   ? $arr['last_name']  : null,
      isset($arr['username'])    ? $arr['username']   : null
    );
  }

  public function getId() {
    return $this->id;
  }

  public function getFirstName() {
    return $this->first_name;
  }

  public function getLastName() {
    return $this->last_name;
  }

  public function getUsername() {
    return $this->username;
  }

  public function sendMessage($text, $link_previews = true, $reply_markup = null) {
    return $this->$_api->sendMessage($this, $text, $link_previews, null, $reply_markup);
  }

  public function getUserProfilePhotos($offset = 0, $limit = 100) {
    return $this->$_api->getUserProfilePhotos($this, $offset, $limit);
  }
}
