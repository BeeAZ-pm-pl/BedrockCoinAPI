<?php

namespace BeeAZZ\BedrockCoinAPI\event;

use BeeAZZ\BedrockCoinAPI\Main;

use pocketmine\player\Player;

use BeeAZZ\BedrockCoinAPI\event\CoinEvent;

class CoinChangeEvent extends CoinEvent{
  
  public $main;
  
  public $player;
  
  public function __construct(Main $main, $player){
    $this->main = $main;
    $this->player = $player;
  }
  
  public function getPlayer(){
    return $this->player;
  }
  
  public function getCoin(){
    return $this->main->myCoin($this->player);
  }
}
