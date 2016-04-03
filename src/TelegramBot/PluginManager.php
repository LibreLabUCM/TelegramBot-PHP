<?php
require_once(__DIR__ . '/TelegramBot.php');





class PluginManager {
  private $pluginList; // TB_Plugin[]
  private $api; // TelegramApi
  private $events = array(
    'textMessageReceived' => array(
      'onTextMessageReceived',
      'onMessageReceived'
    ),
    'mediaMessageReceived' => array(
      'onMediaMessageReceived',
      'onMessageReceived'
    )
  );

  public function PluginManager(TelegramApi $api) {
    $this->pluginList = [];
    $this->api = $api;
  }

  public function registerAll() {
    foreach (glob(__DIR__ . '/plugins/*.php') as $file) {
      $this->registerPlugin(require_once($file));
    }
  }

  public function registerPlugin($plugin) {
    $this->pluginList[$plugin['id']] = array(
      'plugin' => new $plugin['class']($this->api),
      'class' => $plugin['class'],
      'name' => $plugin['name'],
      'reflector' => new ReflectionClass($plugin['class'])
    );
  }

  private function getFunctionMetadata($pluginId, $function) {
    $metadata = array(
      'priority' => [],
      'condition' => [],
      'callEvenIfCancelled' => []
    );

    $c = $this->pluginList[$pluginId]['reflector']->getMethod($function)->getDocComment();
    preg_match_all('/\%(.*?)\s(.*)/', $c, $matches);

    foreach ($matches[0] as $index => $match) {
      if (array_key_exists($matches[1][$index], $metadata)) {
        array_push($metadata[$matches[1][$index]], trim($matches[2][$index]));
      }
    }

    if (count($metadata['callEvenIfCancelled']) === 0) array_push($metadata['callEvenIfCancelled'], false);
    $metadata['callEvenIfCancelled'] = strtolower($metadata['callEvenIfCancelled'][0]) == 'true' ? true : false;
    if (count($metadata['priority']) === 0) array_push($metadata['priority'], 0);

    return $metadata;
  }

  /**
   * [checkConditions description]
   * @param  [type] $conditions [description]
   * @param  [type] $param      [description]
   * @return [type]             [description]
   * %condition isMessage
   * %condition hasText
   * %condition text is hi
   * %condition text matches ^\/hi(?:@{#USERNME})?$
   */
  private function checkConditions($conditions, $param) {
    foreach($conditions as $condition) {
      $condition = explode(' ', $condition, 2);
      $cmd = $condition[0];
      if ($cmd === 'isMessage') {
        if (!($param instanceof TA_Message)) return false;
      }
      if ($cmd === 'hasText') {
        if (!($param instanceof TA_Message)) return false;
        if (!$param->hasText()) return false;
      }
      if ($cmd === 'text') {
        if (!($param instanceof TA_Message)) return false;
        if (!$param->hasText()) return false;
        list($cmd1, $args1) = explode(' ', $condition[1], 2);
        if ($cmd1 === 'is') {
          $args1 = str_replace('{#USERNME}', 'DevPGSVbot', $args1);
          if (!($param->getText() === $args1)) return false;
        }
        if ($cmd1 === 'matches') {
          $args1 = str_replace('{#USERNME}', 'DevPGSVbot', $args1);
          if (preg_match('/'.$args1.'/', $param->getText(), $mat) == false) return false;
        }
      }
    }
    return true;
  }

  public function onEvent($eventName, $param) {
    $eventFunctions = $this->events[$eventName];
    $methodsByPriority = [];
    foreach ($this->pluginList as $pluginId => $plugin) {
      foreach ($eventFunctions as $function) {
        if ($this->pluginList[$pluginId]['reflector']->hasMethod($function)) {
          $metadata = $this->getFunctionMetadata($pluginId, $function);
          foreach ($metadata['priority'] as $p) {
            if (!isset($methodsByPriority[$p])) $methodsByPriority[$p] = [];
            if ($this->checkConditions($metadata['condition'], $param)) { // check conditions
              array_push($methodsByPriority[$p], array(
                'method' => $this->pluginList[$pluginId]['reflector']->getMethod($function),
                'pluginId' => $pluginId,
                'functionName' => $function,
                'metadata' => $metadata,
                'plugin' => $this->pluginList[$pluginId],
                'priority' => $p
              ));
            }
          }
        }
      }
    }

    $eventData = array(
      'cancelled' => false,
      'name' => $eventName,
      'timesProcessed' => 0,
      'processedBy' => []
    );
    $log = fopen(__DIR__ . '/../files/log.txt', 'a');
    foreach ($methodsByPriority as $priority) {
      foreach($priority as $method) {
        fwrite($log, $method['pluginId'].'->'.$method['functionName']."\n");
        //echo $eventData['cancelled'] ? 'yes' : 'no';
        //echo "\n\n";
        if (!$eventData['cancelled'] || $method['metadata']['callEvenIfCancelled']) {
          //echo $method['pluginId'].'->'.$method['functionName']."<br>\n";
          $method['method']->invokeArgs($method['plugin']['plugin'], array($param, &$eventData, $method)); // $method['priority']
          //echo " >Ok<br>\n";
          $eventData['timesProcessed']++;
          array_push($eventData['processedBy'], $method);
        }
      }
    }
    //fwrite($log, print_r($methodsByPriority, true));
    fclose($log);
  }

}

abstract class TB_Plugin {
  protected $api; // TelegramBot
  public function TB_Plugin(TelegramApi $api) {
    $this->api = $api;
  }
  //public function onMessageReceived($message) {}
  //public function onTextMessageReceived($message) {}
  //public function onMediaMessageReceived($message) {}
  //public function onInlineQueryReceived($message) {}
}
