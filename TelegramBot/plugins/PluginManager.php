<?php
ini_set('display_errors','1'); error_reporting(E_ALL);
require_once('Plugin.php');

class PluginManager {
   private static $instance = null;
   private $plugins;
   
   private function PluginManager() {
      $this->plugins = array();
      foreach (scandir(dirname(__FILE__)) as $filename) {
         $path = dirname(__FILE__) . '/' . $filename;
         if (is_file($path)) {
            require_once($path);
         }
      }
      
      $this->addPlugin(new CancelPlugin());
      $this->addPlugin(new CommandsPlugin());
      $this->addPlugin(new ExamplePlugin());
      $this->addPlugin(new GetWarriorPlugin());
      $this->addPlugin(new HelpPlugin());
      $this->addPlugin(new ManageWarriorsPlugin());
      $this->addPlugin(new ParticipationPlugin());
      $this->addPlugin(new RegisterPlugin());
      $this->addPlugin(new ScorePlugin());
      $this->addPlugin(new SendWarriorPlugin());
      
   }
   
   public static function getInstance() { // Something is not right!
      if (self::$instance == null) {
         self::$instance = new PluginManager();
      }
      return self::$instance;
   }
   
   public function addPlugin($pluginInstance) {
      $this->plugins[] = $pluginInstance;
      //print_r($this->plugins);
   }
   
   public function onInit() {
      //print_r($this->plugins);
      foreach($this->plugins as $plugin) {
         $plugin->onInit();
      }
   }
   public function onMessageReceived(TA_Message $message) {
      foreach($this->plugins as $plugin) {
         $plugin->onMessageReceived($message);
      }
   }
}