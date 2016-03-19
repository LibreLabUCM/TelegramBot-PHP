<?php
require_once(__DIR__ . '/TelegramApi.php');
require_once(__DIR__ . '/TA_User.php');


/**
 * Telegram Api InlineQuery
 *
 * @api
 *
 */
class TA_InlineQuery {
  private $_api; // TelegramApi
  private $id;
  private $from; // TA_User
  private $query;
  private $offset;

  private function TA_InlineQuery(TelegramApi $api, $id, $from, $query, $offset) {
    $this->_api = $api;
    $this->id = $id;
    $this->from = $from;
    $this->query = $query;
    $this->offset = $offset;
  }

  /**
  * Creates a TA_InlineQuery from a json string
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	a json string representing a TA_InlineQuery
  *
  * @return a TA_InlineQuery object
  */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_InlineQuery::createFromArray($api, json_decode($json));
  }

  /**
  * Creates a TA_InlineQuery from an associative array
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	an associative array representing a TA_InlineQuery
  *
  * @return a TA_InlineQuery object
  */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          TA_User::createFromArray($api, $arr['from']),
          $arr['query'],
          $arr['offset']
        );
  }

  /**
   * Gets the inline query id
   *
   * @return string inline query id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets the user requesting the inline query
   *
   * @return TA_User user requesting the inline query
   */
  public function getFrom() {
    return $this->from;
  }

  /**
   * Gets the text of the query
   *
   * @return string text of the query
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * Gets the offset of the results to be returned
   *
   * @return string Offset of the results
   */
  public function getOffset() {
    return $this->offset;
  }
}
