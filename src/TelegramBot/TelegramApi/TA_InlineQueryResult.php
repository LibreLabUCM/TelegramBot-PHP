<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api InlineQueryResult
 *
 * @api
 *
 */
abstract class TA_InlineQueryResult {
  protected $_api; // TelegramApi
  protected $type;
  protected $id;
  public static $incrementalId = 0;

  public abstract function toArr();
}



/**
 * Telegram Api ChosenInlineQueryResult
 *
 * @api
 *
 */
class TA_ChosenInlineResult {

}


/**
 * Telegram Api InlineQueryResultArticle
 *
 * @api
 *
 */
class TA_InlineQueryResultArticle extends TA_InlineQueryResult {
  private $title;
  private $message_text;
  private $parse_mode;
  private $disable_web_page_preview;
  private $url;
  private $hide_url;
  private $description;
  private $thumb_url;
  private $thumb_width;
  private $thumb_height;

  public function TA_InlineQueryResultArticle(TelegramApi $api, $id, $title, $message_text, $parse_mode = null, $disable_web_page_preview = null, $url = null, $hide_url = null, $description = null, $thumb_url = null, $thumb_width = null, $thumb_height = null) {
    $this->_api = $api;
    $this->type = "article";
    $this->id = (string)$id;
    $this->title = $title;
    $this->message_text = $message_text;
    $this->parse_mode = $parse_mode;
    $this->disable_web_page_preview = $disable_web_page_preview;
    $this->url = $url;
    $this->hide_url = $hide_url;
    $this->description = $description;
    $this->thumb_url = $thumb_url;
    $this->thumb_width = $thumb_width;
    $this->thumb_height = $thumb_height;
  }

  public static function createFromJson(TelegramApi $api, $json) {
    return TA_InlineQueryResultArticle::createFromArray($api, json_decode($json, true));
  }

  public static function createMinimum(TelegramApi $api, $id, $title, $message_text) {
    return TA_InlineQueryResultArticle::createFromArray($api, array(
          'id' => $id,
          'title' => $title,
          'message_text' => $message_text
        ));
  }

  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['id'],
          $arr['title'],
          $arr['message_text'],
          isset($arr['parse_mode'])                 ? $arr['parse_mode']               : null,
          isset($arr['disable_web_page_preview'])   ? $arr['disable_web_page_preview'] : null,
          isset($arr['url'])                        ? $arr['url']                      : null,
          isset($arr['hide_url'])                   ? $arr['hide_url']                 : null,
          isset($arr['description'])                ? $arr['description']              : null,
          isset($arr['thumb_url'])                  ? $arr['thumb_url']                : null,
          isset($arr['thumb_width'])                ? $arr['thumb_width']              : null,
          isset($arr['thumb_height'])               ? $arr['thumb_height']             : null
        );
  }

  public function setParseMode($parse_mode) {
    $this->parse_mode = $parse_mode;
    return $this;
  }

  public function setDisableWebPagePreview($disable_web_page_preview) {
    $this->disable_web_page_preview = $disable_web_page_preview;
    return $this;
  }

  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function setHideUrl($hide_url) {
    $this->hide_url = $hide_url;
    return $this;
  }

  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  public function setThumb($thumb_url, $thumb_width, $thumb_height) {
    $this->thumb_url = $thumb_url;
    $this->thumb_width = $thumb_width;
    $this->thumb_height = $thumb_height;
    return $this;
  }

  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['title']          = $this->title;
    $ret['message_text']   = $this->message_text;
    if (isset($this->parse_mode))               $ret['parse_mode']                = $this->parse_mode;
    if (isset($this->disable_web_page_preview)) $ret['disable_web_page_preview']  = $this->disable_web_page_preview;
    if (isset($this->url))                      $ret['url']                       = $this->url;
    if (isset($this->hide_url))                 $ret['hide_url']                  = $this->hide_url;
    if (isset($this->description))              $ret['description']               = $this->description;
    if (isset($this->thumb_url))                $ret['thumb_url']                 = $this->thumb_url;
    if (isset($this->thumb_width))              $ret['thumb_width']               = $this->thumb_width;
    if (isset($this->thumb_height))             $ret['thumb_height']              = $this->thumb_height;
    return $ret;
  }
}

/**
 * Telegram Api InlineQueryResultPhoto
 *
 * @api
 *
 */
class TA_InlineQueryResultPhoto extends TA_InlineQueryResult {
  private $photo_url;
  private $thumb_url;

  private $title;

  public function TA_InlineQueryResultPhoto($api, $id, $photo_url, $thumb_url = null, $title = null) {
    if ($thumb_url === null) $thumb_url = $photo_url;

    $this->_api = $api;
    $this->type = "photo";
    $this->id = (string)$id;
    $this->photo_url = $photo_url;
    $this->thumb_url = $thumb_url;
    $this->title = $title;
  }


  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['photo_url']      = $this->photo_url;
    $ret['thumb_url']      = $this->thumb_url;

    if (isset($this->title))             $ret['title']              = $this->title;
    return $ret;
  }
}

/**
 * Telegram Api InlineQueryResultGif
 *
 * @api
 *
 */
class TA_InlineQueryResultGif extends TA_InlineQueryResult {
  public function toArr(){}
}

/**
 * Telegram Api InlineQueryResultMpeg4Gif
 *
 * @api
 *
 */
class TA_InlineQueryResultMpeg4Gif extends TA_InlineQueryResult {
  public function toArr(){}
}

/**
 * Telegram Api InlineQueryResultVideo
 *
 * @api
 *
 */
class TA_InlineQueryResultVideo extends TA_InlineQueryResult {
  public function toArr(){}
}


// Unofficial
class TA_InlineQueryResultArray {
  private $_api; // TelegramApi
  private $results; // TA_InlineQueryResult[]

  public function TA_InlineQueryResult() {
    $this->results = [];
  }

  public function addResult(TA_InlineQueryResult $result) {
    $this->results[] = $result->toArr();
    return $this;
  }

  public function toJson() {
    return json_encode($this->results);
  }
}
