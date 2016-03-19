#TelegramBot-PHP

  * Clone
  * Copy "config_file.example.php" to "config_file.php"
  * Edit the config file:
    * token: get it from [BotFather](https://telegram.me/BotFather)
    * webhookUrl: url to index.php. If you try to access that url you should get something like: "Error on creating bot: Invalid key!"
    * db: not used. Anything works. You can leave it as 'db'.
    * admins: not used. Array of admin id's.
    * hookKey: some random string like 561gbrz566zsw. Type it once, randomly, then forget about it.


If you want to test the bot, uncomment the second part of the config file and set $testId to your id.
Then access the url where the bot is located. You should receive a message to the account specified in $testId.
To access the bot by url, use this url: **webhookUrl**?key=**hookKey**

Something like: https://example.com/TelegramBot/?key=561gbrz566zsw
If the key is not specified, you should get and error: "Invalid key!"

Once you are sure it is working, you can set up the webhook by adding: &setwebhook
to the end of the url:
https://example.com/TelegramBot/?key=561gbrz566zsw&setwebhook

Once the webhook is set, be sure to comment out the second part of the config file (if not, every update will be the one specified there!).


If you see "Nothing to see here..." in the browser, then the bot is correctly setup, and if the webhook points to that url the bot will recieve it and process it.
