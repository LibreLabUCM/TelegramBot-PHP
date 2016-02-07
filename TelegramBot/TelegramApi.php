<?php

/**
 * Telegram api wrapper.
 * 
 * @api
 * 
 */
class TelegramApi {
	private $token;
	
	
	public function TelegramApi($token) {
		$this->token = $token;
	}
	
	/**
	 * Sends an api request to telegram
	 *
	 * @param string $method
	 *        	the api method
	 * @param array $params
	 *        	(Optional) the parameters of the method (Default: null)
	 * @param void $file
	 *        	(Optional) (Unused) (Default: null)
	 *        	
	 * @return array the result of the api request
	 */
	private function sendApiRequest($method, $params = null, $file = null) {
		if ($params === null) {
			$url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
		} else {
			if (is_array ( $params )) {
				$url = 'https://api.telegram.org/bot' . $this->token . '/' . $method . '?' . http_build_query ( $params );
			} else {
				// Exception! $params should be an array!
				return false;
			}
		}
		
		$curl = curl_init ();
		curl_setopt_array ( $curl, array (
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_SSL_VERIFYPEER => false 
		) );
		return json_decode ( curl_exec ( $curl ), true );
	}
	
	/**
	 * Sends a getMe request to get info about the bot
	 * 
	 * @return array the result of the api request
	 */
	public function getMe() {
		return $this->sendApiRequest ( 'getMe' );
	}
	
	/**
	 * Sends a meessage by sending a sendMessage api request
	 * 
	 * @param int $chat_id id of the user the message is going to be sent to
	 * @param string $text text to send as a message
	 * @param bool $link_previews (Optional) If link previews should be shown (Default: true)
	 * @param int $reply_id (Optional) Mark the message as a reply to other message in the same conversation (Default: null)
	 * @param mixed $reply_markup (Optional) Extra markup: keyboard, close keyboard, or force reply (Default: null)
	 * 
	 * @return mixed the result of the api request
	 */
	public function sendMessage($chat_id, $text, $link_previews = true, $reply_id = null, $reply_markup = null) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['text'] = $text;
		$options ['parse_mode'] = 'Markdown';
		
		if ($link_previews === true || $link_previews === null) {
			$options ['disable_web_page_preview'] = false;
		} else if ($link_previews === false) {
			$options ['disable_web_page_preview'] = true;
		}
		
		if ($reply_id !== null) {
			$options ['reply_id'] = $reply_id;
		}
		
		if ($reply_markup !== null) {
			$options ['reply_markup'] = json_encode ( $reply_markup );
		}
		
		return $this->sendApiRequest ( 'sendMessage', $options );
	}
	
	/**
	 * Forwards a message from origin to destination chat
	 * 
	 * @param int $chat_id destination chat
	 * @param int $from_chat_id origin chat
	 * @param int $message_id id of the message in origin chat to fordward
	 * 
	 * @return mixed the result of the api request
	 */
	public function forwardMessage($chat_id, $from_chat_id, $message_id) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['from_chat_id'] = $from_chat_id;
		$options ['message_id'] = $message_id;
		return $this->sendApiRequest ( 'forwardMessage', $options );
	}
	
	// Untested
	public function sendPhoto($chat_id, $photo, $caption = "", $reply_id = null, $reply_markup = null) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['photo'] = $photo;
		
		if ($caption !== null && $caption !== "") {
			$options ['caption'] = $caption;
		}
		
		if ($reply_id !== null) {
			$options ['reply_id'] = $reply_id;
		}
		
		if ($reply_markup !== null) {
			$options ['reply_markup'] = json_encode ( $reply_markup );
		}
		
		return $this->sendApiRequest ( 'sendMessage', $options );
	}
	
	// Untested
	public function sendAudio($chat_id, $audio, $duration = "", $performer = "", $title = "", $reply_id = null, $reply_markup = null) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['audio'] = $audio;
	
		if ($duration !== null && $duration !== "") {
			$options ['duration'] = $duration;
		}
		
		if ($performer !== null && $performer !== "") {
			$options ['performer'] = $performer;
		}
		
		if ($title !== null && $title !== "") {
			$options ['title'] = $title;
		}
	
		if ($reply_id !== null) {
			$options ['reply_id'] = $reply_id;
		}
	
		if ($reply_markup !== null) {
			$options ['reply_markup'] = json_encode ( $reply_markup );
		}
	
		return $this->sendApiRequest ( 'sendMessage', $options );
	}
	
	// Untested
	public function sendDocument($chat_id, $document, $reply_id = null, $reply_markup = null) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['document'] = $document;
		
		if ($reply_id !== null) {
			$options ['reply_id'] = $reply_id;
		}
		
		if ($reply_markup !== null) {
			$options ['reply_markup'] = json_encode ( $reply_markup );
		}
		
		return $this->sendApiRequest ( 'sendMessage', $options );
	}
	
	// Untested
	public function sendSticker($chat_id, $sticker, $reply_id = null, $reply_markup = null) {
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['sticker'] = $sticker;
	
		if ($reply_id !== null) {
			$options ['reply_id'] = $reply_id;
		}
	
		if ($reply_markup !== null) {
			$options ['reply_markup'] = json_encode ( $reply_markup );
		}
	
		return $this->sendApiRequest ( 'sendMessage', $options );
	}
	
	// Untested
	public function sendVideo($chat_id, $video, $duration, $caption = "", $reply_id = null, $reply_markup = null) {
		// TODO
	}
	
	// Untested
	public function sendVoice($chat_id, $vioce, $duration, $reply_id = null, $reply_markup = null) {
		// TODO
	}
	
	/**
	 * Sends an api request to show a chat action for the client
	 * @param int $chat_id target chat
	 * @param string $action string representing the action. 
	 * 
	 * @return mixed the result of the api request 
	 */
	public function sendChatAction($chat_id, $action) {
		$availableActions = array (
				'typing',
				'upload_photo',
				'record_video',
				'upload_video',
				'record_audio',
				'upload_audio',
				'upload_document',
				'find_location' 
		);
		
		if (! in_array ( $action, $availableActions )) {
			// Exception! Unknown action!
			return false;
		}
		
		$options = array ();
		$options ['chat_id'] = $chat_id;
		$options ['action'] = $action;
		return $this->sendApiRequest ( 'sendChatAction', $options );
	}
	
	public function getUserProfilePhotos($user_id, $offset = 0, $limit = 100) {
		$options = array ();
		$options ['user_id'] = $user_id;
		$options ['offset'] = $offset;
		$options ['limit'] = $limit;
		
		return $this->sendApiRequest ( 'getUserProfilePhotos', $options );
	}
	
	// Untested
	public function getFile($file_id) {
		$options = array ();
		$options ['file_id'] = $file_id;
		return $this->sendApiRequest ( 'getFile', $options );
	}
	
	
	// Untested
	/**
	 * Answer an inline query request
	 * 
	 * @param string $inline_query_id id of the query to answer
	 * @param mixed $results results to show to the user
	 * @param int $cache_time (Optional) time the server can save the answer in cache (Default: 0)
	 * @param bool $is_personal (Optional) if the answer should not be the same for other users (Default: true)
	 * @param string $next_offset  (Optional) (Default: "")
	 * 
	 * @return mixed the result of the api request
	 */
	public function answerInlineQuery($inline_query_id, $results, $cache_time = 0, $is_personal = true, $next_offset = "") {
		$options = array ();
		$options ['inline_query_id'] = $inline_query_id;
		$options ['results'] = $results;
		$options ['cache_time'] = $cache_time;
		$options ['is_personal'] = $is_personal;
		$options ['next_offset'] = $next_offset;
		return $this->sendApiRequest ( 'answerInlineQuery', $options );
	}
	
}




class TA_User {
	private $id;
	private $first_name;
	private $last_name;
	private $username;
	
	public function TA_User($id, $first_name, $last_name = null, $username = null) {
		$this->id = $id;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->username = $username;
	}
	
	public static function createFromJson($json) {
		return TA_User::createFromArray(json_decode($json));
	}
	
	public static function createFromArray($arr) {
		return new Self(
				$arr['id'],
				$arr['first_name'],
				isset($arr['last_name'])	? $arr['last_name']	: null,
				isset($arr['username'])		? $arr['username']	: null
			);
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getFirstName() {
		return $this->first_name;
	}
	
	public function getLastName() {
		return $this->last_name;
	}
	
	public function getUsername() {
		return $this->username;
	}
}


class TA_Chat {
	private $id;
	private $type;
	private $title;
	private $username;
	private $first_name;
	private $last_name;
	
	public function TA_Chat($id, $type, $title = null, $username = null, $first_name = null, $last_name = null) {
		$this->id = $id;
		$this->type = $type;
		$this->title = $title;
		$this->username = $username;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
	}
	
	public static function createFromJson($json) {
		return TA_Chat::createFromArray(json_decode($json));
	}
	
	public static function createFromArray($arr) {
		return new Self(
				$arr['id'],
				$arr['type'],
				isset($arr['title'])		? $arr['title']			: null,
				isset($arr['username'])		? $arr['username']		: null,
				isset($arr['first_name'])	? $arr['first_name']	: null,
				isset($arr['last_name'])	? $arr['last_name']		: null
			);
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getFirstName() {
		return $this->first_name;
	}
	
	public function getLastName() {
		return $this->last_name;
	}
	
	
}






class TA_Message {
	private $message_id;
	private $from; // TA_User
	private $date;
	private $chat; // TA_Chat
	private $fordward_from; // TA_User
	private $forward_date;
	private $reply_to_message; // TA_Message
	private $text;
	private $audio;
	private $document;
	private $photo;
	private $sticker;
	private $video;
	private $voice;
	private $caption;
	private $contact;
	private $location;
	private $new_chat_participant; // TA_User
	private $left_chat_participant; // TA_User
	private $new_chat_title;
	private $new_chat_photo;
	private $delete_chat_photo;
	private $group_chat_created;
	private $channel_chat_created;
	private $migrate_to_chat_id;
	private $migrate_from_chat_id;


	public function TA_Chat($message_id, $date, TA_Chat $chat, TA_User $from = null, TA_User $fordward_from = null, $forward_date = null, TA_Message $reply_to_message = null,
			$text = null, $audio = null, $document = null, $photo = null, $sticker = null, $video = null, $voice = null, $caption = null, $contact = null, $location = null,
			TA_User $new_chat_participant = null, TA_User $left_chat_participant = null, $new_chat_title = null, $new_chat_photo = null, $delete_chat_photo = null,
			$group_chat_created = null, $channel_chat_created = null, $migrate_to_chat_id = null, $migrate_from_chat_id = null) {

				$this->message_id = $message_id;
				$this->date = $date;
				$this->chat = $chat;
				$this->from = $from;
				$this->fordward_from = $fordward_from; // TA_User
				$this->forward_date = $forward_date;
				$this->reply_to_message = $reply_to_message; // TA_Message
				$this->text = $text;
				$this->audio = $audio;
				$this->document = $document;
				$this->photo = $photo;
				$this->sticker = $sticker;
				$this->video = $video;
				$this->voice = $voice;
				$this->caption = $caption;
				$this->contact = $contact;
				$this->location = $location;
				$this->new_chat_participant = $new_chat_participant; // TA_User
				$this->left_chat_participant = $left_chat_participant; // TA_User
				$this->new_chat_title = $new_chat_title;
				$this->new_chat_photo = $new_chat_photo;
				$this->delete_chat_photo = $delete_chat_photo;
				$this->group_chat_created = $group_chat_created;
				$this->channel_chat_created = $channel_chat_created;
				$this->migrate_to_chat_id = $migrate_to_chat_id;
				$this->migrate_from_chat_id = $migrate_from_chat_id;
	}
	
	public static function createFromJson($json) {
		return TA_Message::createFromArray(json_decode($json));
	}
	
	public static function createFromArray($arr) {
		return new Self(
				$arr['message_id'],
				$arr['date'],
				$arr['chat'],
				isset($arr['from'])						? $arr['from']							: null,
				isset($arr['fordward_from'])			? $arr['fordward_from']					: null,
				isset($arr['forward_date'])				? $arr['forward_date']					: null,
				isset($arr['reply_to_message'])			? $arr['reply_to_message']				: null,
				isset($arr['text'])						? $arr['text']							: null,
				isset($arr['audio'])					? $arr['audio']							: null,
				isset($arr['document'])					? $arr['document']						: null,
				isset($arr['photo'])					? $arr['photo']							: null,
				isset($arr['sticker'])					? $arr['sticker']						: null,
				isset($arr['video'])					? $arr['video']							: null,
				isset($arr['voice'])					? $arr['voice']							: null,
				isset($arr['caption'])					? $arr['caption']						: null,
				isset($arr['contact'])					? $arr['contact']						: null,
				isset($arr['location'])					? $arr['location']						: null,
				isset($arr['new_chat_participant'])		? $arr['new_chat_participant']			: null,
				isset($arr['left_chat_participant'])	? $arr['left_chat_participant']			: null,
				isset($arr['new_chat_title'])			? $arr['new_chat_title']				: null,
				isset($arr['new_chat_photo'])			? $arr['new_chat_photo']				: null,
				isset($arr['delete_chat_photo'])		? $arr['delete_chat_photo']				: null,
				isset($arr['group_chat_created'])		? $arr['group_chat_created']			: null,
				isset($arr['channel_chat_created'])		? $arr['channel_chat_created']			: null,
				isset($arr['migrate_to_chat_id'])		? $arr['migrate_to_chat_id']			: null,
				isset($arr['migrate_from_chat_id'])		? $arr['migrate_from_chat_id']			: null
			);
	}
	
	public function getMessageId() {
		return $this->message_id;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function hasText() {
		return ($this->text !== null);
	}
	
	public function hasMedia() {
		return
			($this->audio !== null)		||
			($this->document !== null)	||
			($this->photo !== null)		||
			($this->sticker !== null)	||
			($this->video !== null)		||
			($this->voice !== null)		||
			($this->contact !== null)	||
			($this->location !== null);
	}
	
	public function isForwarded() {
		return ($this->fordward_from !== null);
	}
	
	// TODO Add all getters

}
