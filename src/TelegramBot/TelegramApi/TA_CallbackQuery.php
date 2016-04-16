<?php
require_once(__DIR__ . '/TelegramApi.php');



/**
 * Telegram Api ChosenInlineQueryResult
 *
 * @api
 *
 */
class TA_CallbackQuery {
  private $_api;
  private $id;
  private $from;
  private $message;
  private $data;
  private $inline_message_id;

  private function TA_CallbackQuery($api, $id, $from, $data, $message = null, $inline_message_id = null) {
    $this->_api = $api;
    $this->id = $id;
    $this->from = $from;
    $this->data = $data;
    $this->message = $message;
    $this->inline_message_id = $inline_message_id;
  }

  public static function createFromJson(TelegramApi $api, $json) {
    return TA_CallbackQuery::createFromArray($api, json_decode($json, true));
  }

  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['id'],
          TA_User::createFromArray($api, $arr['from']),
          $arr['data'],
          isset($arr['message'])                 ? TA_Message::createFromArray($api, $arr['message'])               : null,
          isset($arr['inline_message_id'])       ? $arr['inline_message_id']     : null
        );
  }

  public function getId() {
    return $this->id;
  }

  public function getFrom() {
    return $this->from;
  }

  public function getData() {
    return $this->data;
  }

  public function getMessage() {
    return $this->message;
  }

}
