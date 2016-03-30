<?php
$bot = require_once('setup.php');

// Check login

echo '<a href="'.$config->getBaseUrl().'">Index</a>'."<br>\n";
echo '<a href="'.$config->getBaseUrl().'?setwebhook">Set webhook</a>'."<br>\n";

echo "<br><br><br>\n\n";

if (isset($_GET['setwebhook'])) {
  if ($bot->setWebhook() == 1) {
    echo "Webhook set!<br>\n";
  } else {
    echo "Webhook NOT set!<br>\n";
  }
  exit();
}
