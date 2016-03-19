<?php


class BotConfig {
   private $token;
   private $webhookUrl;
   private $db;
   private $admins;
   private $hookKey;
   private $validConfig;

   public function __construct() {
      $this->validConfig = false;
   }

   /**
   * Static constructor / factory
   */
   public static function create() {
      $instance = new self();
      return $instance;
   }

   /**
    * Config validator - fluent style
    */
   public function validate() {
      if (
        empty($this->token) ||
        empty($this->webhookUrl) ||
        empty($this->db) ||
        empty($this->admins) ||
        empty($this->hookKey) ) {
        $this->validConfig = false;
      } else {
        $this->validConfig = true;
      }
      return $this;
   }

   // Setters

   /**
    * token setter - fluent style
    */
   public function setToken($token) {
      $this->token = $token;
      return $this;
   }

   /**
    * webhookUrl setter - fluent style
    */
   public function setWebhookUrl($webhookUrl) {
      $this->webhookUrl = $webhookUrl;
      return $this;
   }

   /**
    * db setter - fluent style
    */
   public function setDb($db) {
      $this->db = $db;
      return $this;
   }

   /**
    * admins setter - fluent style
    */
   public function setAdmins($admins) {
      $this->admins = $admins;
      return $this;
   }

   /**
    * hookKey setter - fluent style
    */
   public function setHookKey($hookKey) {
      $this->hookKey = $hookKey;
      return $this;
   }

   // Getters

   /**
    * token getter
    */
   public function getToken() {
      return $this->token;
   }

   /**
    * webhookUrl getter
    */
   public function getWebhookUrl() {
      return $this->webhookUrl;
   }

   /**
    * db getter
    */
   public function getDb() {
      return $this->db;
   }

   /**
    * admins getter
    */
   public function getAdmins() {
      return $this->admins;
   }

   /**
    * hookKey getter
    */
   public function getHookKey() {
      return $this->hookKey;
   }

   /**
    * vaidConfig getter
    */
   public function isValid() {
      return $this->validConfig;
   }

}
