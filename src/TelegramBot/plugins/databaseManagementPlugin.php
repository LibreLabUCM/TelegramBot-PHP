<?php
require_once(__DIR__ . '/../PluginManager.php');


class DatabaseManagementPlugin extends TB_Plugin {
  public function DatabaseManagementPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date any
   */
  public function onMessageReceived($message) {
    // ---TEMP!---
    $o = array(
      'typeMap' => array(
        'root' => 'array',
        'document' => 'array',
      ),
    );
    $currentUser = $this->db->selectCollection('users', $o)->findOne(['_id' => $message->getFrom()->getId()]);
    if ($currentUser === null) {
      $currentUser = array(
        "_id" => $message->getFrom()->getId(),
        "first_name" => $message->getFrom()->getFirstName(),
        "chatsSeen" => [$message->getChat()->getId()],
        "role" => "user",
        "ban" => array(
          "status" => false,
          "reason" => "none"
        ),
        "stats" => ['messages' => 0]
      );
      if ($message->getFrom()->getUsername() !== null) $currentUser['username'] = $message->getFrom()->getUsername();
      if ($message->getFrom()->getLastName() !== null) $currentUser['last_name'] = $message->getFrom()->getLastName();
      $this->db->selectCollection('users')->insertOne($currentUser);
    }
    //var_dump($currentUser);
    if (!in_array($message->getChat()->getId(), $currentUser['chatsSeen'])) {
      array_push($currentUser['chatsSeen'], $message->getChat()->getId());
      $this->db->selectCollection('users')->updateOne(['_id' => $message->getFrom()->getId()], ['$set' => ['chatsSeen' => $currentUser['chatsSeen']]]);
    }
    $this->db->selectCollection('users')->updateOne(['_id' => $message->getFrom()->getId()], ['$inc' => ['stats.messages' => 1]]);
    //$message->sendReply('->```'.print_r($currentUser, true).'```', null, null, 'Markdown');

    // -----------
  }
}
return array(
  'class' => 'DatabaseManagementPlugin',
  'name' => 'Database Management',
  'id' => 'DatabaseManagementPlugin'
);
