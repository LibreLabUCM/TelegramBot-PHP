<?php


$_BOT_CONFIG = array(
  'token' => '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11',
  'webhookUrl' => 'https://example.com/TelegramBot/', //
  'db' => 'db', // This will be an instance to a database. Right now it is not used. Any string will work
  'admins' => array(12345678, 87654321, 11111111), // Array of admin id's. Not used right now...
  'hookKey' => 'ChangeThisToSomethingRandom' // Something like: 561gbrz566zsw . After changing this, you should set the webhook again!
);


/*
$testId = 12345678; // Your id!
// Simulate an update!
$update = '{
   "update_id":1,
   "message":{
      "message_id":1,
      "from":{
         "id":'.$testId.',
         "first_name":"FIRST_NAME",
         "username":"USERNAME"
      },
      "chat":{
         "id":'.$testId.',
         "first_name":"FIRST_NAME",
         "username":"USERNAME",
         "type":"private"
      },
      "date":1,
      "text":"test!"
   }
}';
*/
