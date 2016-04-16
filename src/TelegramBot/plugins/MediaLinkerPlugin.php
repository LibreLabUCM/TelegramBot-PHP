<?php
require_once(__DIR__ . '/../PluginManager.php');


class MediaLinkerPlugin extends TB_Plugin {
  public function MediaLinkerPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   */
  public function onMediaMessageReceived($message) {
    $f = $message->getMedia();
    if ($f->hasFile()) {
      $message->sendReply("I haven't downloaded your ".$message->getMediaType()."....\nI have deactivated it ;)");

      /*
      // Download the media file and answer with the link to the file downloaded
      $this->api->sendChatAction($message->getFrom(), "typing");
      $finalPath = 'files/'.$message->getDate() .'-'. $message->getMediaType() .'.'. $f->getFileExtension();
      $downloadPath = $f->downloadFile();
      rename($downloadPath, $finalPath);
      $this->api->sendMessage($message->getFrom(), $message->getMediaType()."!\n" .  $this->config->getWebhookUrl().$finalPath);
      */
    } else {
      if ($message->isVenue()) {
        $this->api->sendMessage($message->getFrom(), "So... you are at\n" . $f->getTitle() . "\n" . $f->getAddress());
      } else if ($message->isLocation()) {
        $this->api->sendMessage($message->getFrom(), "So... you are at\n" . $f->getLongitude() . "\n" . $f->getLatitude());
      } else if ($message->isContact()) {
        $this->api->sendMessage($message->getFrom(), "Name: ".$f->getFirstName()."\nPhone: ".$f->getPhoneNumber());
      } else {
        $this->api->sendMessage($message->getFrom(), "I can't understand that media message!");
      }
    }
  }

}
return array(
  'class' => 'MediaLinkerPlugin',
  'name' => 'Media Linker',
  'id' => 'MediaLinkerPlugin'
);
