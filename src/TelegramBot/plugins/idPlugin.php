<?php
require_once(__DIR__ . '/../PluginManager.php');


class IdPlugin extends TB_Plugin {
  public function IdPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   * %condition text matches ^\/(?:id|start id)(?:@{#USERNME})?$
   */
  public function onTextMessageReceived($message) {
    if ($message->isReply()) {
      if ($message->getReplyToMessage()->isForwarded()) {
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\n  msg id: `".$message->getMessageId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\n  msg id: `".$message->getReplyToMessage()->getMessageId()
        ."`\n  Forwarded from *".$message->getReplyToMessage()->getForwardFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getForwardFrom()->getId()
        ."`\n", null, null, 'Markdown');
        //$message->getReplyToMessage()->sendReply($message->getReplyToMessage()->getForwardFrom()->getId());
      } else if ($message->getReplyToMessage()->isNewChatParticipant()){
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\n  msg id: `".$message->getMessageId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\n  msg id: `".$message->getReplyToMessage()->getMessageId()
        ."`\nNew member *".$message->getReplyToMessage()->getNewChatParticipant()->getFirstName()."*: `".$message->getReplyToMessage()->getNewChatParticipant()->getId()
        ."`\n", null, null, 'Markdown');
      } else if ($message->getReplyToMessage()->isLeftChatParticipant()){
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\n  msg id: `".$message->getMessageId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\n  msg id: `".$message->getReplyToMessage()->getMessageId()
        ."`\nLeft member *".$message->getReplyToMessage()->getLeftChatParticipant()->getFirstName()."*: `".$message->getReplyToMessage()->getLeftChatParticipant()->getId()
        ."`\n", null, null, 'Markdown');
      } else {
        $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
        ."`\n  msg id: `".$message->getMessageId()
        ."`\nReplied to *".$message->getReplyToMessage()->getFrom()->getFirstName()."*: `".$message->getReplyToMessage()->getFrom()->getId()
        ."`\n  msg id: `".$message->getReplyToMessage()->getMessageId()
        ."`\n", null, null, 'Markdown');
        //$message->sendReply($message->getReplyToMessage()->getFrom()->getId());
      }
    } else if ($message->isForwarded()) {
      $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
      ."`\n  msg id: `".$message->getMessageId()
      ."`\nForwarded from *".$message->getForwardFrom()->getFirstName()."*: `".$message->getForwardFrom()->getId()
      ."`\n", null, null, 'Markdown');
      //$message->sendReply($message->getForwardFrom()->getId());
    }else {
      $message->sendReply("*".$message->getFrom()->getFirstName()."*: `".$message->getFrom()->getId()
      ."`\n  msg id: `".$message->getMessageId()
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
