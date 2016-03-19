<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api File
 *
 * @api
 *
 */
class TA_File {
  private $_api; // TelegramApi
  private $file_id;
  private $file_size;
  private $file_path;

  private function TA_File(TelegramApi $api, $file_id, $file_size = null, $file_path = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->file_size = $file_size;
    $this->file_path = $file_path;
  }

  /**
  * Creates a TA_File from a json string
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	a json string representing a TA_File
  *
  * @return a TA_File object
  */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_File::createFromArray($api, json_decode($json));
  }

  /**
  * Creates a TA_File from an associative array
  *
  * @param string $api
  *        	an instance to the TelegramApi wrapper
  * @param array $json
  *        	an associative array representing a TA_File
  *
  * @return a TA_File object
  */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          isset($arr['file_size'])   ? $arr['file_size']  : null,
          isset($arr['file_path'])   ? $arr['file_path']  : null
        );
  }

  public function getFileId() {
    return $this->file_id;
  }

  public function getFileSize() {
    return $this->file_size;
  }

  public function getFilePath() {
    return $this->file_path;
  }
}
