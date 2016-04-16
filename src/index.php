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


echo "<h1>Plugins</h1>\n";
$plugins =  $bot->getPluginManager()->getPluginList();

echo "<ul>\n";
foreach ($plugins as $pluginId => $plugin) {
  echo "<li><h3>".$plugin['name']." ($pluginId)</h3>\n";
  $changelog = $plugin['plugin']->getChangeLog();
  echo '<table border="1">';
  foreach ($changelog as $timestamp => $update) {
    $version = $update['version'][0].'.'.$update['version'][1].'.'.$update['version'][2];
    if (!empty($update['version'][3])) $version .= '-'.$update['version'][3];
    echo '<tr>';
    echo '<td>'.date('d/m/y H:i', $timestamp).'</td>';
    echo '<td>'.$version.'</td>';
    echo '<td>';
    echo "<ul>\n";
    foreach ($update['changes'] as $change) {
      echo '<li>'.$change.'</li>';
    }
    echo "</ul>\n";
    echo '</td>';
    echo '</tr>';
  }
  echo '<table>';
  echo "</li>\n";
}
echo "</ul>\n";
