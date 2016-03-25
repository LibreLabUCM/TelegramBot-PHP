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
  private $_api;
  private $result_id;
  private $from;
  private $query;

  private function TA_ChosenInlineResult($result_id, $from, $query) {
    $this->result_id;
    $this->from;
    $this->query;
  }

  public static function createFromJson(TelegramApi $api, $json) {
    return TA_ChosenInlineResult::createFromArray($api, json_decode($json, true));
  }

  public static function createFromArray(TelegramApi $api, $arr) {
    return new Self(
          $api,
          $arr['result_id'],
          $arr['from'],
          $arr['query']
        );
  }

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
  private $photo_width;
  private $photo_height;
  private $description;
  private $caption;
  private $message_text;
  private $parse_mode;
  private $disable_web_page_preview;

  public function TA_InlineQueryResultPhoto($api, $id, $photo_url, $thumb_url = null, $title = null, $photo_width = null, $photo_height = null, $description = null, $caption = null, $message_text = null, $parse_mode = null, $disable_web_page_preview = null) {
    if ($thumb_url === null) $thumb_url = $photo_url;

    $this->_api = $api;
    $this->type = "photo";
    $this->id = (string)$id;
    $this->photo_url = $photo_url;
    $this->thumb_url = $thumb_url;
    $this->title = $title;
    $this->photo_width = $photo_width;
    $this->photo_height = $photo_height;
    $this->description = $description;
    $this->caption = $caption;
    $this->message_text = $message_text;
    $this->parse_mode = $parse_mode;
    $this->disable_web_page_preview = $disable_web_page_preview;
  }

  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['photo_url']      = $this->photo_url;
    $ret['thumb_url']      = $this->thumb_url;

    if (isset($this->title))             $ret['title']              = $this->title;
    if (isset($this->photo_width))       $ret['photo_width']        = $this->photo_width;
    if (isset($this->photo_height))      $ret['photo_height']       = $this->photo_height;
    if (isset($this->description))       $ret['description']        = $this->description;
    if (isset($this->caption))           $ret['caption']            = $this->caption;
    if (isset($this->message_text))      $ret['message_text']       = $this->message_text;
    if (isset($this->parse_mode))        $ret['parse_mode']         = $this->parse_mode;
    if (isset($this->disable_web_page_preview))   $ret['disable_web_page_preview']   = $this->disable_web_page_preview;
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
  private $gif_url;
  private $thumb_url;
  private $title;
  private $gif_width;
  private $gif_height;
  private $description;
  private $caption;
  private $message_text;
  private $parse_mode;
  private $disable_web_page_preview;

  public function TA_InlineQueryResultGif($api, $id, $gif_url, $thumb_url = null, $title = null, $gif_width = null, $gif_height = null, $description = null, $caption = null, $message_text = null, $parse_mode = null, $disable_web_page_preview = null) {
    if ($thumb_url === null) $thumb_url = $gif_url;

    $this->_api = $api;
    $this->type = "gif";
    $this->id = (string)$id;
    $this->gif_url = $gif_url;
    $this->thumb_url = $thumb_url;
    $this->title = $title;
    $this->gif_width = $gif_width;
    $this->gif_height = $gif_height;
    $this->description = $description;
    $this->caption = $caption;
    $this->message_text = $message_text;
    $this->parse_mode = $parse_mode;
    $this->disable_web_page_preview = $disable_web_page_preview;
  }

  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['gif_url']        = $this->gif_url;
    $ret['thumb_url']      = $this->thumb_url;

    if (isset($this->title))             $ret['title']              = $this->title;
    if (isset($this->gif_width))         $ret['gif_width']          = $this->gif_width;
    if (isset($this->gif_height))        $ret['gif_height']         = $this->gif_height;
    if (isset($this->description))       $ret['description']        = $this->description;
    if (isset($this->caption))           $ret['caption']            = $this->caption;
    if (isset($this->message_text))      $ret['message_text']       = $this->message_text;
    if (isset($this->parse_mode))        $ret['parse_mode']         = $this->parse_mode;
    if (isset($this->disable_web_page_preview))   $ret['disable_web_page_preview']   = $this->disable_web_page_preview;
    return $ret;
  }
}

/**
 * Telegram Api InlineQueryResultMpeg4Gif
 *
 * @api
 *
 */
class TA_InlineQueryResultMpeg4Gif extends TA_InlineQueryResult {
  private $mpeg4_url;
  private $thumb_url;
  private $title;
  private $mpeg4_width;
  private $mpeg4_height;
  private $description;
  private $caption;
  private $message_text;
  private $parse_mode;
  private $disable_web_page_preview;

  public function TA_InlineQueryResultMpeg4Gif($api, $id, $mpeg4_url, $thumb_url = null, $title = null, $mpeg4_width = null, $mpeg4_height = null, $description = null, $caption = null, $message_text = null, $parse_mode = null, $disable_web_page_preview = null) {
    if ($thumb_url === null) $thumb_url = $mpeg4_url;

    $this->_api = $api;
    $this->type = "mpeg4_gif";
    $this->id = (string)$id;
    $this->mpeg4_url = $mpeg4_url;
    $this->thumb_url = $thumb_url;
    $this->title = $title;
    $this->mpeg4_width = $mpeg4_width;
    $this->mpeg4_height = $mpeg4_height;
    $this->description = $description;
    $this->caption = $caption;
    $this->message_text = $message_text;
    $this->parse_mode = $parse_mode;
    $this->disable_web_page_preview = $disable_web_page_preview;
  }

  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['mpeg4_url']        = $this->mpeg4_url;
    $ret['thumb_url']      = $this->thumb_url;

    if (isset($this->title))             $ret['title']              = $this->title;
    if (isset($this->mpeg4_width))       $ret['mpeg4_width']        = $this->mpeg4_width;
    if (isset($this->mpeg4_height))      $ret['mpeg4_height']       = $this->mpeg4_height;
    if (isset($this->description))       $ret['description']        = $this->description;
    if (isset($this->caption))           $ret['caption']            = $this->caption;
    if (isset($this->message_text))      $ret['message_text']       = $this->message_text;
    if (isset($this->parse_mode))        $ret['parse_mode']         = $this->parse_mode;
    if (isset($this->disable_web_page_preview))   $ret['disable_web_page_preview']   = $this->disable_web_page_preview;
    return $ret;
  }
}

/**
 * Telegram Api InlineQueryResultVideo
 *
 * @api
 *
 */
class TA_InlineQueryResultVideo extends TA_InlineQueryResult {
  private $mpeg4_url;
  private $thumb_url;
  private $title;
  private $video_width;
  private $video_height;
  private $description;
  private $duration;
  private $message_text;
  private $parse_mode;
  private $disable_web_page_preview;
  private $mime_type;

  public function TA_InlineQueryResultVideo($api, $id, $video_url, $thumb_url, $title, $message_text, $mime_type, $parse_mode, $video_width = null, $duration = null, $video_height = null, $description = null, $disable_web_page_preview = null) {
    $this->_api = $api;
    $this->type = "video";
    $this->id = (string)$id;
    $this->video_url = $video_url;
    $this->thumb_url = $thumb_url;
    $this->title = $title;
    $this->message_text = $message_text;
    $this->mime_type = $mime_type;
    $this->parse_mode = $parse_mode;
    $this->video_width = $video_width;
    $this->video_height = $video_height;
    $this->description = $description;
    $this->duration = $duration;


    $this->disable_web_page_preview = $disable_web_page_preview;
  }

  public function toArr(){
    $ret = array();
    $ret['type']           = $this->type;
    $ret['id']             = $this->id;
    $ret['video_url']        = $this->video_url;
    $ret['thumb_url']      = $this->thumb_url;

    if (isset($this->title))             $ret['title']              = $this->title;
    if (isset($this->video_width))       $ret['video_width']        = $this->video_width;
    if (isset($this->video_height))      $ret['video_height']       = $this->video_height;
    if (isset($this->description))       $ret['description']        = $this->description;
    if (isset($this->mime_type))         $ret['mime_type']          = $this->mime_type;
    if (isset($this->duration))          $ret['duration']           = $this->duration;
    if (isset($this->message_text))      $ret['message_text']       = $this->message_text;
    if (isset($this->parse_mode))        $ret['parse_mode']         = $this->parse_mode;
    if (isset($this->disable_web_page_preview))   $ret['disable_web_page_preview']   = $this->disable_web_page_preview;
    return $ret;
  }
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
