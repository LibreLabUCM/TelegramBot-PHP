<?php
require_once(__DIR__ . '/../PluginManager.php');


class IdPlugin extends TB_Plugin {
  public function IdPlugin($api, $bot) {
    parent::__construct($api, $bot);
  }

  /**
   * %condition date isNew
   * %condition text matches ^\/(?:id|start id)(?:@{#USERNME})?$
   */
  public function onTextMessageReceived($message) {
    if ($message->isReply()) {
      if ($message->getReplyToMessage()->isForwarded()) {
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\nForwarded from *".$message->getReplyToMessage()->getForwardFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getForwardFrom()->getId()
        ."`\n", null, null, 'Markdown');
        //$message->getReplyToMessage()->sendReply($message->getReplyToMessage()->getForwardFrom()->getId());
      } else if ($message->getReplyToMessage()->isNewChatParticipant()){
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\nNew member *".$message->getReplyToMessage()->getNewChatParticipant()->getFirstName()."*: `".$message->getReplyToMessage()->getNewChatParticipant()->getId()
        ."`\n", null, null, 'Markdown');
      } else if ($message->getReplyToMessage()->isLeftChatParticipant()){
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\nLeft member *".$message->getReplyToMessage()->getLeftChatParticipant()->getFirstName()."*: `".$message->getReplyToMessage()->getLeftChatParticipant()->getId()
        ."`\n", null, null, 'Markdown');
      } else {
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\n", null, null, 'Markdown');
        //$message->sendReply($message->getReplyToMessage()->getFrom()->getId());
      }
    } else if ($message->isForwarded()) {
      $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
      ."`\nForwarded from *".$message->getForwardFrom()->getFirstName()."*: `".$message->getForwardFrom()->getId()
      ."`\n", null, null, 'Markdown');
      //$message->sendReply($message->getForwardFrom()->getId());
    }else {
      $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
      ."`\n", null, null, 'Markdown');
      //$message->sendReply($message->getFrom()->getId());
    }

  }
}
return array(
  'class' => 'IdPlugin',
  'name' => 'Id',
  'id' => 'IdPlugin'
);
