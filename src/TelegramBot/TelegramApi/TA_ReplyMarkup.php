<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api ReplyMarkup
 *
 * @api
 *
 */
abstract class TA_ReplyMarkup {
  public abstract function toJson();

  public function __toString() {
    return $this->toJson();
  }
}

class TA_ReplyKeyboardMarkup extends TA_ReplyMarkup {
  private $keyboard;
  private $resize_keyboard;
  private $one_time_keyboard;
  private $selective;

  public function TA_ReplyKeyboardMarkup($keyboard = null, $resize_keyboard = false, $one_time_keyboard = false, $selective = false) {
    if ($keyboard === null) $keyboard = [[]];
    else if (!is_array($keyboard)) $keyboard = json_decode($keyboard, true);

    $this->keyboard = $keyboard;
    $this->resize_keyboard = $resize_keyboard;
    $this->one_time_keyboard = $one_time_keyboard;
    $this->selective = $selective;
  }

  /**
   * Adds a new empty row - fluent style
   */
  public function addRow() {
    if (count($this->keyboard[count($this->keyboard) - 1]) !== 0 || true) {
      array_push($this->keyboard, []);
    }
    return $this;
  }

  /**
   * Adds a new option - fluent style
   * @param string $option the new option text
   * @param [type] $row    (Optional) The option will be added in the specified row (Default: the last row)
   */
  public function addOption($option, $row = null) {
    if ($row === null) $row = count($this->keyboard) - 1;
    array_push($this->keyboard[$row], $option);
    return $this;
  }

  /**
   * Gets a json representation of this object
   * @return string json representation
   */
  public function toJson() {
    foreach($this->keyboard as $row => $buttons) {
      foreach($buttons as $col => $button) {
        if ($button instanceof TA_KeyboardButton) {
          $this->keyboard[$row][$col] = $button->toArray();
        }
      }
    }
    $obj = array(
      'keyboard' => $this->keyboard
    );
    if ($this->resize_keyboard !== null) $obj['resize_keyboard'] = $this->resize_keyboard;
    if ($this->one_time_keyboard !== null) $obj['one_time_keyboard'] = $this->one_time_keyboard;
    if ($this->selective !== null) $obj['selective'] = $this->selective;
    return json_encode($obj);
  }

}

class TA_ReplyKeyboardHide extends TA_ReplyMarkup{
  private $hide_keyboard;
  private $selective;

  public function TA_ReplyKeyboardHide($selective = false) {
    $this->hide_keyboard = true;
    $this->selective = $selective;
  }

  /**
   * Gets a json representation of this object
   * @return string json representation
   */
  public function toJson() {
    $obj = array(
      'hide_keyboard' => $this->hide_keyboard
    );
    if ($this->selective !== null) $obj['selective'] = $this->selective;
    return json_encode($obj);
  }
}

class TA_ForceReply extends TA_ReplyMarkup {
  private $force_reply;
  private $selective;

  public function TA_ForceReply($selective = false) {
    $this->force_reply = true;
    $this->selective = $selective;
  }

  /**
   * Gets a json representation of this object
   * @return string json representation
   */
  public function toJson(){
    $obj = array(
      'force_reply' => $this->force_reply
    );
    if ($this->selective !== null) $obj['selective'] = $this->selective;
    return json_encode($obj);
  }
}

class TA_KeyboardButton {
  private $text;
  private $request_contact;
  private $request_location;

  public function TA_KeyboardButton($text, $request_contact = null, $request_location = null) {
    $this->text = $text;
    $this->request_contact = $request_contact;
    $this->request_location = $request_location;
  }

  public function setText($text) {
    $this->text = $text;
  }

  public function setRequestContact($request_contact) {
    $this->request_contact = $request_contact;
  }

  public function setRequestLocation($request_location) {
    $this->request_location = $request_location;
  }

  public function getText() {
    return $this->text;
  }

  public function getRequestContact() {
    return $this->request_contact;
  }

  public function getRequestLocation() {
    return $this->request_location;
  }

  public function toArray() {
    $obj = ['text' => $this->text];
    if ($this->request_contact !== null) $obj['request_contact'] = $this->request_contact;
    if ($this->request_location !== null) $obj['request_location'] = $this->request_location;
    return $obj;
  }
}



class TA_InlineKeyboardMarkup extends TA_ReplyMarkup {
  private $keyboard;

  public function TA_InlineKeyboardMarkup($keyboard = null) {
    if ($keyboard === null) $keyboard = [[]];
    else if (!is_array($keyboard)) $keyboard = json_decode($keyboard, true);

    $this->keyboard = $keyboard;
  }

  /**
   * Adds a new empty row - fluent style
   */
  public function addRow() {
    if (count($this->keyboard[count($this->keyboard) - 1]) !== 0 || true) {
      array_push($this->keyboard, []);
    }
    return $this;
  }

  /**
   * Adds a new option - fluent style
   * @param string $option the new option text
   * @param [type] $row    (Optional) The option will be added in the specified row (Default: the last row)
   */
  public function addOption($option, $row = null) {
    if ($row === null) $row = count($this->keyboard) - 1;
    array_push($this->keyboard[$row], $option);
    return $this;
  }

  /**
   * Gets a json representation of this object
   * @return string json representation
   */
  public function toJson() {
    foreach($this->keyboard as $row => $buttons) {
      foreach($buttons as $col => $button) {
        if ($button instanceof TA_InlineKeyboardButton) {
          $this->keyboard[$row][$col] = $button->toArray();
        }
      }
    }
    $obj = array(
      'inline_keyboard' => $this->keyboard
    );
    return json_encode($obj);
  }

}


class TA_InlineKeyboardButton {
  private $text;
  private $url;
  private $callback_data;
  private $switch_inline_query;

  public function TA_InlineKeyboardButton($text, $url = null, $callback_data = null, $switch_inline_query = null) {
    $this->text = $text;
    $this->setUrl($url);
    $this->setCallback_data($callback_data);
    $this->setSwitch_inline_query($switch_inline_query);
  }

  public function setText($text) {
    $this->text = $text;
  }

  public function setUrl($url) {
    $this->url = $url;
    if ($url !== null) $this->callback_data = null;
    if ($url !== null) $this->switch_inline_query = null;
  }

  public function setCallback_data($callback_data) {
    if ($callback_data !== null) $this->url = null;
    $this->callback_data = $callback_data;
    if ($callback_data !== null) $this->switch_inline_query = null;
  }

  public function setSwitch_inline_query($switch_inline_query) {
    if ($switch_inline_query !== null) $this->url = null;
    if ($switch_inline_query !== null) $this->callback_data = null;
    $this->switch_inline_query = $switch_inline_query;
  }

  public function getText() {
    return $this->text;
  }

  public function getUrl() {
    return $this->url = $url;
  }

  public function getCallback_data() {
    return $this->callback_data;
  }

  public function getSwitch_inline_query() {
    return $this->switch_inline_query;
  }

  public function toArray() {
    $obj = ['text' => $this->text];
    if ($this->url !== null) $obj['url'] = $this->url;
    else if ($this->callback_data !== null) $obj['callback_data'] = $this->callback_data;
    else if ($this->switch_inline_query !== null) $obj['switch_inline_query'] = $this->switch_inline_query;
    else throw new Exception('No optional field set!');
    return $obj;
  }
}
