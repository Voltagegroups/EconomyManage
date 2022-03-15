<?php
namespace Voltage\Api\Economy\listener;

use Voltage\Api\Economy\event\money\PlayerCreateAccountMoneyEvent;
use Voltage\Api\Economy\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EconomyListener implements Listener{

    private static $pg;

    public function __construct(EconomyAPI $pg) {
        self::$pg = $pg;
        $pg->getServer()->getPluginManager()->registerEvents($this,$pg);
    }

    public function getPlugin() : EconomyAPI {
        return self::$pg;
    }

    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        if (!EconomyAPI::getProvider()->existMoney($player->getName())) {
            $ev = new PlayerCreateAccountMoneyEvent($this->getPlugin(), $player, EconomyAPI::getProvider()->getBaseMoney());
            $ev->call();
            if (!$ev->isCancelled()) {
                EconomyAPI::getProvider()->setMoney($player->getName(), $ev->getBaseMoney());
            }
        }
    }
}