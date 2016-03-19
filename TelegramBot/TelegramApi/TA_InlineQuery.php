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

  public function getId() {
    return $this->id;
  }

  public function getFrom() {
    return $this->from;
  }

  public function getQuery() {
    return $this->query;
  }

  public function getOffset() {
    return $this->offset;
  }
}
