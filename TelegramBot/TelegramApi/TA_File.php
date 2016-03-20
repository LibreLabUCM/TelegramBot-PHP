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
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return true;
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

  public function getFileExtension() {
    return 'mp3';
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
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return true;
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

  public function getFileExtension() {
    return pathinfo($this->getFileName(), PATHINFO_EXTENSION);
  }

}


class TA_Sticker {
  private $_api; // TelegramApi
  private $file_id;
  private $width;
  private $height;
  private $thumb;
  private $file_size;
  private $_file;

  private function TA_Sticker(TelegramApi $api, $file_id, $width, $height, $thumb = null, $file_size = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->width = $width;
    $this->height = $height;
    $this->thumb = $thumb;
    $this->file_size = $file_size;
  }

  /**
   * Creates a TA_Sticker from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Sticker
   *
   * @return a TA_Sticker object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Sticker::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Sticker from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Sticker
   *
   * @return a TA_Sticker object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          $arr['width'],
          $arr['height'],
          isset($arr['thumb'])       ? TA_File::createFromArray($api, $arr['thumb']) : null,
          isset($arr['file_size'])   ? $arr['file_size']  : null
        );
  }

  /**
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return true;
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
   * Gets the sticker width
   *
   * @return string sticker width
   */
  public function getWidth() {
    return $this->width;
  }

  /**
   * Gets the sticker height
   *
   * @return string sticker height
   */
  public function getHeight() {
    return $this->height;
  }

  private function updateFile() {
    $this->_file = TA_File::createFromArray($this->_api, $this->_api->getFile($this->getFileId()));
  }

  public function downloadFile() {
    if ($this->_file == null)
      $this->updateFile();
    return $this->_file->downloadFile();
  }

  public function getFileExtension() {
    return 'webp';
  }

}


class TA_Video {
  private $_api; // TelegramApi
  private $file_id;
  private $width;
  private $height;
  private $duration;
  private $thumb;
  private $mime_type;
  private $file_size;
  private $_file;

  private function TA_Video(TelegramApi $api, $file_id, $width, $height, $duration, $thumb = null, $mime_type = null, $file_size = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->width = $width;
    $this->height = $height;
    $this->duration = $duration;
    $this->thumb = $thumb;
    $this->mime_type = $mime_type;
    $this->file_size = $file_size;
  }

  /**
   * Creates a TA_Video from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Video
   *
   * @return a TA_Video object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Video::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Video from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Video
   *
   * @return a TA_Video object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          $arr['width'],
          $arr['height'],
          $arr['duration'],
          isset($arr['thumb'])       ? TA_File::createFromArray($api, $arr['thumb']) : null,
          isset($arr['mime_type'])   ? $arr['mime_type']  : null,
          isset($arr['file_size'])   ? $arr['file_size']  : null
        );
  }

  /**
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return true;
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
   * Gets the video width
   *
   * @return string video width
   */
  public function getWidth() {
    return $this->width;
  }

  /**
   * Gets the video height
   *
   * @return string video height
   */
  public function getHeight() {
    return $this->height;
  }

  /**
   * Gets the video duration
   *
   * @return string video duration
   */
  public function getVideoDuration() {
    return $this->duration;
  }

  private function updateFile() {
    $this->_file = TA_File::createFromArray($this->_api, $this->_api->getFile($this->getFileId()));
  }

  public function downloadFile() {
    if ($this->_file == null)
      $this->updateFile();
    return $this->_file->downloadFile();
  }

  public function getFileExtension() {
    return 'mp4';
  }

}


class TA_Voice {
  private $_api; // TelegramApi
  private $file_id;
  private $duration;
  private $mime_type;
  private $file_size;
  private $_file;

  private function TA_Voice(TelegramApi $api, $file_id, $duration, $mime_type = null, $file_size = null) {
    $this->_api = $api;
    $this->file_id = $file_id;
    $this->duration = $duration;
    $this->mime_type = $mime_type;
    $this->file_size = $file_size;
  }

  /**
   * Creates a TA_Voice from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Voice
   *
   * @return a TA_Voice object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Voice::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Voice from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Voice
   *
   * @return a TA_Voice object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['file_id'],
          $arr['duration'],
          isset($arr['mime_type'])   ? $arr['mime_type']  : null,
          isset($arr['file_size'])   ? $arr['file_size']  : null
        );
  }

  /**
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return true;
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
   * Gets the voice duration
   *
   * @return string voice duration
   */
  public function getVoiceDuration() {
    return $this->duration;
  }

  private function updateFile() {
    $this->_file = TA_File::createFromArray($this->_api, $this->_api->getFile($this->getFileId()));
  }

  public function downloadFile() {
    if ($this->_file == null)
      $this->updateFile();
    return $this->_file->downloadFile();
  }

  public function getFileExtension() {
    return 'ogg';
  }

}


class TA_Location {
  private $_api; // TelegramApi
  private $longitude;
  private $latitude;

  private function TA_Location(TelegramApi $api, $longitude, $latitude) {
    $this->_api = $api;
    $this->longitude = $longitude;
    $this->latitude = $latitude;
  }

  /**
   * Creates a TA_Location from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Location
   *
   * @return a TA_Location object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Location::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Location from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Location
   *
   * @return a TA_Location object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['longitude'],
          $arr['latitude']
        );
  }

  /**
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return false;
  }

  /**
   * Gets the location longitude
   *
   * @return float location longitude
   */
  public function getLongitude() {
    return $this->longitude;
  }

  /**
   * Gets the location latitude
   *
   * @return float location latitude
   */
  public function getLatitude() {
    return $this->latitude;
  }

}


class TA_Contact {
  private $_api; // TelegramApi
  private $phone_number;
  private $first_name;
  private $last_name;
  private $user_id;

  private function TA_Contact(TelegramApi $api, $phone_number, $first_name, $last_name = null, $user_id = null) {
    $this->_api = $api;
    $this->phone_number = $phone_number;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->user_id = $user_id;
  }

  /**
   * Creates a TA_Contact from a json string
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	a json string representing a TA_Contact
   *
   * @return a TA_Contact object
   */
  public static function createFromJson(TelegramApi $api, $json) {
    return TA_Contact::createFromArray($api, json_decode($json));
  }

  /**
   * Creates a TA_Contact from an associative array
   *
   * @param string $api
   *        	an instance to the TelegramApi wrapper
   * @param array $json
   *        	an associative array representing a TA_Contact
   *
   * @return a TA_Contact object
   */
  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['phone_number'],
          $arr['first_name'],
          isset($arr['last_name'])   ? $arr['last_name']  : null,
          isset($arr['user_id'])     ? $arr['user_id']    : null
        );
  }

  /**
   * Checks if this media type contains a file
   *
   * @return boolean if this media type contains a file
   */
  public function hasFile() {
    return false;
  }

  /**
   * Gets the contact phone number
   *
   * @return string contact phone number
   */
  public function getPhoneNumber() {
    return $this->phone_number;
  }

  /**
   * Gets the contact first name
   *
   * @return string contact first name
   */
  public function getFirstName() {
    return $this->first_name;
  }

}
