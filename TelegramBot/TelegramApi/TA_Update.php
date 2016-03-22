<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api Update
 *
 * @api
 *
 */
class TA_Update {
  private $_api; // TelegramApi
  private $update_id;
  private $message; // TA_Message
  private $inline_query;
  private $chosen_inline_result;

  private function TA_Update(TelegramApi $api, $update_id, $message = null, $inline_query = null, $chosen_inline_result = null) {
    $this->_api = $api;
    $this->update_id = $update_id;
    $this->message = $message;
    $this->inline_query = $inline_query;
    $this->chosen_inline_result = $chosen_inline_result;
  }

  /**
   * Creates a TA_Update from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Update
   *
   * @return a TA_Update object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Update::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Update from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Update
   *
   * @return a TA_Update object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['update_id'],
          isset($arr['message'])               ? TA_Message::createFromArray($api, $update['message'])         : null,
          isset($arr['inline_query'])          ? $arr['inline_query']                                          : null,
          isset($arr['chosen_inline_result'])  ? $arr['chosen_inline_result']                                  : null,
        );
  }

  /**
   * Gets the update id
   *
   * The update‘s unique identifier. Update identifiers start from a certain positive number and increase sequentially. This ID becomes especially handy if you’re using Webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order.
   *
   * @return int update id
   */
  public function getId() {
    return $this->update_id;
  }

  /**
   * Gets the update type
   *
   * @return string update type
   */
  public function getType() {
    if ($this->message !== null) return "message";
    if ($this->inline_query !== null) return "inline_query";
    if ($this->chosen_inline_result !== null) return "chosen_inline_result";
    return false;
  }

  /**
   * Gets the update message
   *
   * New incoming message of any kind — text, photo, sticker, etc.
   *
   * @return TA_Message update message
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Gets the update inline_query
   *
   * New incoming inline query
   *
   * @return TA_InlineQuery update inline_query
   */
  public function getInlineQuery() {
    return $this->inline_query;
  }

  /**
   * Gets the update chosen_inline_result
   *
   * The result of an inline query that was chosen by a user and sent to their chat partner.
   *
   * @return TA_ChosenInlineResult update chosen_inline_result
   */
  public function getChosenInlineResult() {
    return $this->chosen_inline_result;
  }

}
