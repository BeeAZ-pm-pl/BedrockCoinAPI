# General

<br>

BedrockCoinAPI A New Economy For Pocketmine

<br>

# For Developers

<br>

- Add Coin
```
$this->coin = $this->getServer()->getPluginManager()->getPlugin("BedrockCoinAPI");

$this->coin->addCoin($player, "1000"); //You can't use float in amount
```
- Reduce Coin
```
$this->coin = $this->getServer()->getPluginManager()->getPlugin("BedrockCoinAPI");
$this->coin->reduceCoin($player, "1000"); //Like Add Coin you can't use float in amount
```
- Get All Coin (Make Top Coin)
```
$this->coin = $this->getServer()->getPluginManager()->getPlugin("BedrockCoinAPI");
$this->coin->getAllCoin(); //You can make top coin with this
```
- My Coin
```
$this->coin = $this->getServer()->getPluginManager()->getPlugin("BedrockCoinAPI");
$this->coin->myCoin($player);
```
