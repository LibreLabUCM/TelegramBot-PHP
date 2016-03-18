<?php
require_once(__DIR__ . '/TelegramApi.php');


/**
 * Telegram Api ReplyMarkup
 *
 * @api
 *
 */
abstract class TA_ReplyMarkup {}

class TA_ReplyKeyboardMarkup extends TA_ReplyMarkup{
   // TODO
}

class TA_ReplyKeyboardHide extends TA_ReplyMarkup{
   // TODO
}

class TA_ForceReply extends TA_ReplyMarkup{
   // TODO
}
