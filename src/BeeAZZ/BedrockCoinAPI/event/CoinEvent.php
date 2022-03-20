<?php

namespace BeeAZZ\BedrockCoinAPI\event;

use BeeAZZ\BedrockCoinAPI\Main;

use pocketmine\event\plugin\PluginEvent;

class CoinEvent extends PluginEvent{
  
  public $main;
  
  public function __construct(Main $main){
    $this->main = $main;
  }
}
