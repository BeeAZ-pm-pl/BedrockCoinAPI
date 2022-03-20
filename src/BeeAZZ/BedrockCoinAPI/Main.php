<?php


namespace BeeAZZ\BedrockCoinAPI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\utils\Config;

use pocketmine\event\player\PlayerJoinEvent;

use BeeAZZ\BedrockCoinAPI\event\CoinChangeEvent;
use BeeAZZ\BedrockCoinAPI\event\CoinEvent;

class Main extends PluginBase implements Listener {
  
  public $coin;
  
  public function onEnable(): void{
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->coin = new Config($this->getDataFolder()."coin.yml", Config::YAML);
    $this->saveDefaultConfig();
  }
  
  public function onDisable(): void{
    $this->coin->save();
  }
  
  public function onJoin(PlayerJoinEvent $event){
    $player = $event->getPlayer();
    $name = $player->getName();
  if(!$this->coin->exists($name)){
   $this->coin->set($name, 0);
   $this->coin->save();
   $ev = new CoinChangeEvent($this, $player);
   $ev->call();
    }
  }
  
  public function reduceCoin($player, $coin){
   $name = $player->getName();
   if($player instanceof Player){
   if(is_numeric($coin)){
   $this->coin->set($name, ($this->coin->get($name) - $coin));
   $this->coin->save();
   $ev = new CoinChangeEvent($this, $player);
   $ev->call();
      }
    }
  }
  
  public function addCoin($player, $coin){
  $name = $player->getName();
  if($player instanceof Player){
  if(is_numeric($coin)){
  $this->coin->set($name, ($this->coin->get($name) + $coin));
  $this->coin->save();
  $ev = new CoinChangeEvent($this, $player);
  $ev->call();
      }
    }
  }
  
  public function myCoin($player){
    if($player instanceof Player){
     return $this->coin->get($player->getName());
    }
  }
  
  public function getAllCoin(){
    return $this->coin->getAll();
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
      case "addcoin":
      if($sender instanceof Player){
      if($sender->hasPermission("bedrockcoinapi.command.addcoin")){
      if(isset($args[0])){
      if(isset($args[1])){
      if($this->getServer()->getPlayerByPrefix($args[0]) !== null){
      if(is_numeric($args[1])){
      $player = $this->getServer()->getPlayerByPrefix($args[0]);
      $this->addCoin($player, $args[1]);
      $ev = new CoinChangeEvent($this, $player);
      $ev->call();
      $sender->sendMessage(str_replace(["{player}", "{coin}"], [$args[0], $args[1]], $this->getConfig()->get("player-addcoin")));
      $player->sendMessage(str_replace("{coin}", $args[1], $this->getConfig()->get("player-receive-addcoin")));
      }else{
       $sender->sendMessage("Please enter the amount in digits !");
      }
      }else{
       $sender->sendMessage($this->getConfig()->get("no-player"));
      }
      }else{
     $sender->sendMessage("Usage: /addcoin <player> <amount>");
                }
       }else{
     $sender->sendMessage("Usage: /addcoin <player> <amount>");
       }
      }else{
      $sender->sendMessage("Please Use Command In Game");
      }
      break;
      }
      case "seecoin":
      if($sender instanceof Player){
      if($sender->hasPermission("bedrockcoinapi.command.seecoin")){
      if(isset($args[0])){
      if($this->getServer()->getPlayerByPrefix($args[0]) !== null){
      $player = $this->getServer()->getPlayerByPrefix($args[0]);
      $sender->sendMessage(str_replace(["{player}", "{coin}"], [$args[0], $this->coin->get($player->getName())], $this->getConfig()->get("seecoin")));
    }else{
     $sender->sendMessage($this->getConfig()->get("no-player"));
    }
      }else{
     $sender->sendMessage("Usage: /addcoin <player> <amount>");
      }
     }else{
   $sender->sendMessage("Please Use Command In Game");
      }
    break;
      }
   case "mycoin":
  if($sender instanceof Player){
  if($sender->hasPermission("bedrockcoinapi.command.mycoin")){
  $coin = $this->myCoin($sender);
  $sender->sendMessage(str_replace("{coin}", $this->myCoin($sender), $this->getConfig()->get("mycoin")));
    }else{
  $sender->sendMessage("Please Use Command In Game");
        }
        break;
  }
   case "setcoin":
      if($sender instanceof Player){
      if($sender->hasPermission("bedrockcoinapi.command.setcoin")){
      if(isset($args[0])){
      if(isset($args[1])){
      if($this->getServer()->getPlayerByPrefix($args[0]) !== null){
      if(is_numeric($args[1])){
     $player = $this->getServer()->getPlayerByPrefix($args[0]);
     $this->coin->set($player->getName(), $args[1]);
     $this->coin->save();
     $sender->sendMessage(str_replace(["{player}", "{coin}"], [$args[0], $args[1]], $this->getConfig()->get("player-setcoin")));
     $player->sendMessage(str_replace("{coin}", $args[1], $this->getConfig()->get("player-receive-setcoin")));
     $ev = new CoinChangeEvent($this, $player);
     $ev->call();
      }else{
       $sender->sendMessage("Please enter the amount in digits !");
      }
      }else{
       $sender->sendMessage($this->getConfig()->get("no-player"));
      }
     }else{
     $sender->sendMessage("Usage: /setcoin <player> <amount>");
              }
       }else{
     $sender->sendMessage("Usage: /setcoin <player> <amount>");
              }
            }
      }else{
    $sender->sendMessage("Please Use Command In Game");
          }
          break;
    case "topcoin":
     $coinall = $this->getAllCoin();
      arsort($coinall);
      $coinall = array_slice($coinall, 0, 10);
            $top = 1;
    foreach($coinall as $name => $count){
    $sender->sendMessage("§a§l✿ Top " . $top . " . " . $name . " §e->§c " . $count . " §bCoin\n");
              $top++;
            }
        break;
     case "paycoin":
     if($sender instanceof Player){
     if($sender->hasPermission("bedrockcoinapi.command.paycoin")){
     if(isset($args[0])){
     if(isset($args[1])){
     if(is_numeric($args[1])){
     if($this->getServer()->getPlayerByPrefix($args[0]) !== null){
     $player2 = $this->getServer()->getPlayerByPrefix($args[0]);
     $coin = $this->myCoin($sender);
     if($args[0] !== $sender->getName()){
     if($coin >= $args[1]){
      $this->reduceCoin($sender, $args[1]);
      $this->addCoin($player2, $args[1]);
     $sender->sendMessage(str_replace(["{player}", "{coin}"], [$args[0], $args[1]], $this->getConfig()->get("player-paycoin")));
     $player2->sendMessage(str_replace(["{player}", "{coin}"], [$sender->getName(), $args[1]], $this->getConfig()->get("player-receive-paycoin")));
             }else{
      $sender->sendMessage($this->getConfig()->get("not-enough-money"));
           }
     }else{
      $sender->sendMessage($this->getConfig()->get("paycoin-yourself"));
     }
     }else{
      $sender->sendMessage($this->getConfig()->get("no-player"));
     }
     }else{
      $sender->sendMessage("Please enter the number");
     }
        }else{
     $sender->sendMessage("Usage: /paycoin <player> <amount>");
                  }
       }else{
     $sender->sendMessage("Usage: /paycoin <player> <amount>");
                }
       }else{
   $sender->sendMessage("Please Use Command In Game");
              }
       break;
    }
    return true;
  }
return true;
}
}
