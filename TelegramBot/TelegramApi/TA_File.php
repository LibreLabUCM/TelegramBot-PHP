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

  /**
   * Gets the file id
   *
   * @return string file id
   */
  public function getFileId() {
    return $this->file_id;
  }

  /**
   * Gets the file size
   *
   * @return int file size
   */
  public function getFileSize() {
    return $this->file_size;
  }

  /**
   * Gets the file path
   * To download the file: https://api.telegram.org/file/bot<token>/<file_path>
   *
   * @return string file path
   */
  public function getFilePath() {
    return $this->file_path;
  }

  /**
   * Downloads a file to tmp
   *
   * @return string path to the downloaded file
   */
  public function downloadFile() {
    // https://api.telegram.org/file/bot<token>/<file_path>
    return $this->_api->downloadFile($this->getFilePath());
  }
}

class TA_Audio {
  private $_api; // TelegramApi
  private $file_id;
  private $duration;
  private $performer;
  private $title;
  private $mime_type;
  private $file_size;
  private $_file;

  private function TA_Audio(TelegramApi $api, $file_id, $duration, $performer = null, $title = null, $mime_type = null, $file_size = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->duration = $duration;
    $this->performer = $performer;
    $this->title = $title;
    $this->mime_type = $mime_type;
    $this->file_size = $file_size;
  }

  /**
   * Creates a TA_Audio from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Audio
   *
   * @return a TA_Audio object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Audio::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Audio from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Audio
   *
   * @return a TA_Audio object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          $arr['duration'],
          isset($arr['performer'])   ? $arr['performer']  : null,
          isset($arr['title'])       ? $arr['title']      : null,
          isset($arr['mime_type'])   ? $arr['mime_type']  : null,
          isset($arr['file_size'])   ? $arr['file_size']  : null
        );
  }

  /**
   * Gets the file id
   *
   * @return string file id
   */
  public function getFileId() {
    return $this->file_id;
  }

  /**
   * Gets the file size
   *
   * @return int file size
   */
  public function getFileSize() {
    return $this->file_size;
  }

  /**
   * Gets the audio performer
   *
   * @return string audio performer
   */
  public function getAudioPerformer() {
    return $this->performer;
  }

  /**
   * Gets the audio title
   *
   * @return string audio title
   */
  public function getAudioTitle() {
    return $this->title;
  }

  private function updateFile() {
    $this->_file = TA_File::createFromArray($this->_api, $this->_api->getFile($this->getFileId()));
  }

  public function downloadFile() {
    if ($this->_file == null)
      $this->updateFile();
    return $this->_file->downloadFile();
  }

}


class TA_Document {
  private $_api; // TelegramApi
  private $file_id;
  private $thumb;
  private $file_name;
  private $mime_type;
  private $file_size;
  private $_file;

  private function TA_Document(TelegramApi $api, $file_id, $thumb = null, $file_name = null, $mime_type = null, $file_size = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->thumb = $thumb;
    $this->file_name = $file_name;
    $this->mime_type = $mime_type;
    $this->file_size = $file_size;
  }

  /**
   * Creates a TA_Document from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Document
   *
   * @return a TA_Document object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Document::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Document from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Document
   *
   * @return a TA_Document object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          isset($arr['thumb'])       ? TA_File::createFromArray($api, $arr['thumb']) : null,
          isset($arr['file_name'])   ? $arr['file_name']  : null,
          isset($arr['mime_type'])   ? $arr['mime_type']  : null,
          isset($arr['file_size'])   ? $arr['file_size']  : null
        );
  }

  /**
   * Gets the file id
   *
   * @return string file id
   */
  public function getFileId() {
    return $this->file_id;
  }

  /**
   * Gets the file size
   *
   * @return int file size
   */
  public function getFileSize() {
    return $this->file_size;
  }

  /**
   * Gets the file name
   *
   * @return string file name
   */
  public function getFileName() {
    return $this->file_name;
  }

  private function updateFile() {
    $this->_file = TA_File::createFromArray($this->_api, $this->_api->getFile($this->getFileId()));
  }

  public function downloadFile() {
    if ($this->_file == null)
      $this->updateFile();
    return $this->_file->downloadFile();
  }

}
