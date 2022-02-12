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
        $name = strtolower($player->getName());
        if (EconomyAPI::getProviderSysteme()->existMoney($name)) {
            $ev = new PlayerCreateAccountMoneyEvent($this->getPlugin(), $player, EconomyAPI::getProviderSysteme()->getBaseMoney());
            $ev->call();
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->setMoney($name, EconomyAPI::getProviderSysteme()->getMaxMoney());
            }
        }
    }
}