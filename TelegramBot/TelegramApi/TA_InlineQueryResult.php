<?php
require_once(__DIR__ . '/TelegramApi.php');


abstract class TA_InlineQueryResult {
  private $_api; // TelegramApi
  protected $type;
  protected $id;

  //public abstract function toJson();
}



class TA_ChosenInlineResult {

}


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

  private function TA_InlineQueryResultArticle($api, $id, $title, $message_text, $parse_mode, $disable_web_page_preview, $url, $hide_url, $description, $thumb_url, $thumb_width, $thumb_height) {
    $this->_api = $api;
    $this->type = "article";
    $this->id = $id;
    $this->title = $title;
    $this-> message_text = $message_text;
    $this-> parse_mode = $parse_mode;
    $this-> disable_web_page_preview = $disable_web_page_preview;
    $this-> url = $url;
    $this-> hide_url = $hide_url;
    $this-> description = $description;
    $this-> thumb_url = $thumb_url;
    $this-> thumb_width = $thumb_width;
    $this-> thumb_height = $thumb_height;
  }

  public static function createFromJson($api, $json) {
    return TA_InlineQueryResultArticle::createFromArray($api, json_decode($json));
  }

  public static function createMinimum($api, $id, $title, $message_text) {
    return TA_InlineQueryResultArticle::createFromArray($api, array(
          'id' => $id,
          'title' => $title,
          'message_text' => $message_text
        ));
  }

  public static function createFromArray($api, $arr) {
    return new Self(
          $api,
          $arr['id'],
          $arr['title'],
          $arr['message_text'],
          isset($arr['parse_mode'])                 ? $arr['parse_mode'] : "Markdown",
          isset($arr['disable_web_page_preview'])   ? $arr['disable_web_page_preview'] : null,
          isset($arr['url'])                        ? $arr['url'] : null,
          isset($arr['hide_url'])                   ? $arr['hide_url'] : null,
          isset($arr['description'])                ? $arr['description'] : null,
          isset($arr['thumb_url'])                  ? $arr['thumb_url'] : null,
          isset($arr['thumb_width'])                ? $arr['thumb_width'] : null,
          isset($arr['thumb_height'])               ? $arr['thumb_height'] : null
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
}

class TA_InlineQueryResultPhoto extends TA_InlineQueryResult {

}

class TA_InlineQueryResultGif extends TA_InlineQueryResult {

}

class TA_InlineQueryResultMpeg4Gif extends TA_InlineQueryResult {

}

class TA_InlineQueryResultVideo extends TA_InlineQueryResult {

}
