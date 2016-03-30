<?php
require_once(__DIR__ . '/TelegramBot.php');





class PluginManager {
  private $pluginList; // TB_Plugin[]
  private $api; // TelegramApi

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
    $this->pluginList[$plugin] = new $plugin($this->api);
  }

  public function onMessageReceived($message) {
    foreach ($this->pluginList as $pluginName => $plugin) {
      $plugin->onMessageReceived($message);
    }
  }

}

abstract class TB_Plugin {
  protected $api; // TelegramBot
  public function TB_Plugin(TelegramApi $api) {
    $this->api = $api;
  }
  public function onMessageReceived($message) {}
}
