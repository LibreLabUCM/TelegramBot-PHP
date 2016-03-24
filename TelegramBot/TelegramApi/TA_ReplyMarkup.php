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
