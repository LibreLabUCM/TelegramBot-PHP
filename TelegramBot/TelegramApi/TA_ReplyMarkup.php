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
}

class TA_ReplyKeyboardMarkup extends TA_ReplyMarkup{ // reply_markup={"keyboard":[["option1"],["option2","3"]]}
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

  public function addRow() {
    //echo (count($this->keyboard) - 1)."<br>\n";
    //echo (count($this->keyboard[count($this->keyboard) - 1]))."<br>\n";
    if (count($this->keyboard[count($this->keyboard) - 1]) !== 0 || true) {
      array_push($this->keyboard, []);
    }
    return $this;
  }

  public function addOption($option, $row = null) {
    if ($row === null) $row = count($this->keyboard) - 1;
    array_push($this->keyboard[$row], $option);
    return $this;
  }

  public function toJson() {
    $obj = array(
      'keyboard' => $this->keyboard
    );
    if ($this->resize_keyboard !== null) $obj['resize_keyboard'] = $this->resize_keyboard;
    if ($this->one_time_keyboard !== null) $obj['one_time_keyboard'] = $this->one_time_keyboard;
    if ($this->selective !== null) $obj['selective'] = $this->selective;
    return json_encode($obj);
  }

  public function __toString() {
    return $this->toJson();
  }
}

class TA_ReplyKeyboardHide extends TA_ReplyMarkup{
   // TODO
  public function toJson(){}
}

class TA_ForceReply extends TA_ReplyMarkup{
   // TODO
  public function toJson(){}
}
