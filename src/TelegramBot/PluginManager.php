<?php
require_once(__DIR__ . '/TelegramBot.php');





class PluginManager {
  private $pluginList; // TB_Plugin[]
  private $api; // TelegramApi
  private $bot;
  private $considerOldAfter = 10;
  private $db;
  private $events = array(
    'textMessageReceived' => array(
      'onTextMessageReceived',
      'onMessageReceived'
    ),
    'mediaMessageReceived' => array(
      'onMediaMessageReceived',
      'onMessageReceived'
    ),
    'messageReceived' => array(
      'onMessageReceived'
    )
  );


  public function PluginManager(TelegramApi $api, TelegramBot $bot, $db) {
    $this->pluginList = [];
    $this->api = $api;
    $this->bot = $bot;
    $this->db = $db;
  }

  public function checkIfPluginIsActive($plugin) {
    if (isset($plugin['id'])) $plugin = $plugin['id'];
    if (true) {
      return true;
    } else {
      return false;
    }
  }

  public function registerAll() {
    foreach (glob(__DIR__ . '/plugins/*.php') as $file) {
      $this->registerPlugin(require_once($file));
    }
  }

  public function registerPlugin($plugin, $filterOutInactive = true) {
    if ( (!$filterOutInactive) || $this->checkIfPluginIsActive($plugin)) {
      $this->pluginList[$plugin['id']] = array(
        'plugin' => new $plugin['class']($this->api, $this->bot, $this->db),
        'class' => $plugin['class'],
        'name' => $plugin['name'],
        'reflector' => new ReflectionClass($plugin['class']),
        'version' => $plugin['version']
      );
    }
  }

  public function getPluginList() {
    return $this->pluginList;
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
   * Checks an array of conditions agains the current object
   *
   * @param  mixed $conditions array of conditions to be checked
   * @param  mixed $param      object to be tested against the conditions
   * @return bool              if $params passed successfully all condition checks
   *
   * Some examples of conditions:
   * %condition isMessage
   * %condition hasText
   * %condition text is hi
   * %condition text matches ^\/hi(?:@{#USERNME})?$
   */
  private function checkConditions($conditions, $param) {
    foreach($conditions as $condition) {
      $condition = explode(' ', $condition, 2);
      if (empty($condition[0])) throw new Exception('No condition!');
      switch($condition[0]) {
        case 'isMessage':
          if (!($param instanceof TA_Message)) return false;
          break;
        case 'hasText':
          if (!($param instanceof TA_Message)) return false;
          if (!$param->hasText()) return false;
          break;
        case 'text':
          if (!($param instanceof TA_Message)) return false;
          if (!$param->hasText()) return false;
          $condition['text'] = explode(' ', $condition[1], 2);
          if ($condition['text'][0] === 'is') {
            $condition['text'][1] = str_replace('{#USERNME}', $this->bot->getBotUsername(), $condition['text'][1]);
            if (!($param->getText() === $condition['text'][1])) return false;
          }
          if ($condition['text'][0] === 'matches') {
            $condition['text'][1] = str_replace('{#USERNME}', $this->bot->getBotUsername(), $condition['text'][1]);
            if (preg_match('/'.$condition['text'][1].'/', $param->getText(), $mat) == false) return false;
          }
          break;
        case 'date':
          if (!($param instanceof TA_Message)) return false;
          $date = $param->getDate();
          if ($condition[1] === 'isNew') {
            if($date < time() - $this->considerOldAfter) return false;
          } else if ($condition[1] === 'isOld') {
            if($date > time() - $this->considerOldAfter) return false;
          } else if ($condition[1] === 'any') {}
          break;
        default:
          throw new Exception('Unknown condition: '.$condition[0]);
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
  protected $bot; // TelegramBot
  protected $db;
  public function TB_Plugin(TelegramApi $api, TelegramBot $bot, $db) {
    $this->api = $api;
    $this->bot = $bot;
    $this->db = $db;
  }
  public function getChangeLog() { return ['0'=>['version'=>[0, 0, 0], 'changes' => []]]; }
  //public function onMessageReceived($message) {}
  //public function onTextMessageReceived($message) {}
  //public function onMediaMessageReceived($message) {}
  //public function onInlineQueryReceived($message) {}
}
