<?php

require_once('../src/TelegramBot/TelegramApi/TelegramApi.php');

class TelegramApiTest extends PHPUnit_Framework_TestCase {
  private $api;
  private $config;
  public function setUp(){
    $this->assertFileExists('../src/config_file.php', 'No config file found! Run the installer first!');
    require('../src/config_file.php');
    $this->assertNotNull($_BOT_CONFIG, 'Invalid config file found! Run the installer first!');
    $this->config = $_BOT_CONFIG;
    $this->api = new TelegramApi($this->config['token']);
  }

  public function testGetMe() {
    $r = $this->api->getMe();
    $this->assertNotNull($r->getUsername());
    $this->assertNotNull($r->getId());
  }

  public function testSendMessage() {
    $message = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
    $r = $this->api->sendMessage($this->config['admins'][0], $message);
    $this->assertNotNull($r);
    $this->assertEquals($message, $r->getText());
    $this->assertEquals($this->config['admins'][0], $r->getChat()->getId());
  }

  public function testForwardMessage() {
    $message = 'Message to fordward';
    $r = $this->api->sendMessage($this->config['admins'][0], $message);
    $r2 = $this->api->forwardMessage($this->config['admins'][0], $this->config['admins'][0], $r->getMessageId());
    $this->assertNotNull($r2->getForwardDate());
    $this->assertNull($r->getForwardDate());
  }



}
