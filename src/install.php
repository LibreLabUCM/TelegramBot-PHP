<?php
$configExists = file_exists('./config_file.php');
if ($configExists) {
  require_once('./config_file.php'); // $_BOT_CONFIG
  if (!isset($_BOT_CONFIG)) $configExists = false;
}

if ($configExists) {
  echo "<p style=\"color:red;\">Config file already exists! Delete it or clear its contents before trying to install!</p><br><br>\n";
  echo '<a href="index.php">Go to index</a>';
  exit();
}

$token = empty($_POST['token']) ? "" : $_POST['token'];
if (!empty($token)) {
  if (preg_match('/^\d*\:[0-9a-zA-Z]*$/', $token)) {
    $url = 'https://api.telegram.org/bot' . $token . '/getMe';
    $curl = curl_init ();
    curl_setopt_array ( $curl, array (
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url,
      CURLOPT_SSL_VERIFYPEER => false
    ));
    $r = json_decode ( curl_exec ( $curl ), true );
    if ($r['ok']) {
      $baseUrl = 'https://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/';
      $hookKey = random_str(16);
      $configText = "<?php
\$_BOT_CONFIG = array(
  'token' => '$token',
  'baseUrl' => '$baseUrl',
  'db' => 'db', // This will be an instance to a database. Right now it is not used. Any string will work
  'admins' => array(12345678, 87654321, 11111111), // Array of admin id's. Not used right now...
  'hookKey' => '$hookKey' // Something like: 6Obdqaab44b4fb2e . After changing this, you should set the webhook again!
);
";
      if (file_put_contents('./config_file.php', $configText)) {
        $url = 'https://api.telegram.org/bot' . $token . '/setWebhook?'.http_build_query (array('url' => $baseUrl.'bot.php?key='.$hookKey));
        $curl = curl_init ();
        curl_setopt_array ( $curl, array (
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $url,
          CURLOPT_SSL_VERIFYPEER => false
        ));
        $r = json_decode ( curl_exec ( $curl ), true );
        if ($r['ok']) {
          header('Location: index.php');
          echo 'Ok!';
          exit();
        } else {
          echo "<p style=\"color:red;\">Couldn't setup the webhook </p><br><br>\n";
        }
      } else {
        echo "<p style=\"color:red;\">Couldn't write the configuration! Please create an empty file 'config_file.php' in the root of the repository and make it writable.</p><br><br>chown www-data config_file.php<br><br><br>\n";
      }
    } else {
      echo "<p style=\"color:red;\">That token doesn't seem to be valid!</p><br><br>\n";
    }
  } else {
    echo "<p style=\"color:red;\">That token doesn't seem to be correct!</p><br><br>\n";
  }
}


if ($configExists) {
  echo '<p style="color:red;">Config file already exists! Installing again will overwrite it!</p>';
}

?>
<form style="text-align:center;" method="post">
  <input type="text" name="token" style="width:80%;" value="<?php echo htmlspecialchars($token); ?>" placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11"><br>
  <input type="submit" value="Install"><br>
</form>

<?php
exit();
















function random_str($length) { // Modified, from: http://stackoverflow.com/a/31107425
  $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = bin2hex(openssl_random_pseudo_bytes(2*$length));
  $max = mb_strlen($keyspace, '8bit') - 1;
  for ($i = 0; $i < $length; ++$i) {
    $str .= $keyspace[rand(0, $max)];
  }
  return substr(str_shuffle($str), $length);
}
