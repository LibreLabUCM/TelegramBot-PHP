<?php
require_once(__DIR__ . '/../PluginManager.php');


class ExamplePlugin extends TB_Plugin {
  public function HelloWorldPlugin($api) {
    parent::__construct($api);
  }

  /**
   *    set a priority of 5.
   *    anything with priority from 0 to 4 will be called before this function,
   *    with priority 6 or greater will be called after this function.
   * %priority 5
   * 		same, but with 10 // 2 priorities => this function will be called 2 times!
   * %priority 10
   *    if it is a message
   * %condition isMessage
   *    if the message is a text message
   * %condition hasText
   *    if the text is "/example" or contains the bot @username
   * %condition text matches (@{#USERNME})|(^\/example$)
   *    this function will be called if conditions are met, even if the event was cancelled
   * %callEvenIfCancelled true
   */
  public function onMessageReceived($message, &$eventData, $method) {
    $p = $method['priority']; // Get the priority for this call of the function
    $t = $message->getText(); // Gets the message text. We are sure the message has text, it is specified as a %condition
    $c = $eventData['cancelled'] ? 'yes' : 'no'; // Gets if the event was cancelled.
    $timesProcessed = $eventData['timesProcessed']; // Gets the number of times this event was processed
    $message->sendReply("Priority: $p\n Text: $t\n Cancelled: $c\n Times processed: $timesProcessed\n"); // Replies to the message with specified text
    $eventData['cancelled'] = true; // Cancels the event. No other plugins will process the event (except those with %callEvenIfCancelled true)
  }

}
return array(
  'class' => 'ExamplePlugin',
  'name' => 'Example World',
  'id' => 'ExamplePlugin'
);
