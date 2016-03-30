<?php
require_once(__DIR__ . '/../PluginManager.php');

return 'MediaLinkerPlugin';
class MediaLinkerPlugin extends TB_Plugin {
  public function MediaLinkerPlugin($api) {
    parent::__construct($api);
  }

  public function onMessageReceived($message) {
    if ($message->hasMedia()) {
      $f = $message->getMedia();
      if ($f->hasFile()) {
        $this->api->sendMessage($message->getFrom(), "I haven't downloaded your ".$message->getMediaType()."....\nI have deactivated it ;)");

        /*
        // Download the media file and answer with the link to the file downloaded
        $this->api->sendChatAction($message->getFrom(), "typing");
        $finalPath = 'files/'.$message->getDate() .'-'. $message->getMediaType() .'.'. $f->getFileExtension();
        $downloadPath = $f->downloadFile();
        rename($downloadPath, $finalPath);
        $this->api->sendMessage($message->getFrom(), $message->getMediaType()."!\n" .  $this->config->getWebhookUrl().$finalPath);
        */
      } else {
        if ($message->isLocation()) {
          $this->api->sendMessage($message->getFrom(), "So... you are at\n" . $f->getLongitude() . "\n" . $f->getLatitude());
        } else if ($message->isContact()) {
          $this->api->sendMessage($message->getFrom(), "Name: ".$f->getFirstName()."\nPhone: ".$f->getPhoneNumber());
        } else {
          $this->api->sendMessage($message->getFrom(), "I can't understand that media message!");
        }
      }
    }
  }
}
