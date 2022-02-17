<?php
namespace Voltage\Api\Economy\listener;

use Voltage\Api\Economy\event\money\PlayerCreateAccountMoneyEvent;
use Voltage\Api\Economy\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EconomyListener implements Listener{

    private static $pg;

    public function __construct(EconomyAPI $pg) {
        var_dump("good");
        self::$pg = $pg;
        $pg->getServer()->getPluginManager()->registerEvents($this,$pg);
    }

    public function getPlugin() : EconomyAPI {
        return self::$pg;
    }

    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        if (!EconomyAPI::getProviderSysteme()->existMoney($player->getName())) {
            $ev = new PlayerCreateAccountMoneyEvent($this->getPlugin(), $player, EconomyAPI::getProviderSysteme()->getBaseMoney());
            $ev->call();
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->setMoney($player->getName(), EconomyAPI::getProviderSysteme()->getMaxMoney());
            }
        }
    }
}