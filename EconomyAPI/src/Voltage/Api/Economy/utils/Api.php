<?php

namespace Voltage\Api\Economy\utils;

use Voltage\Api\Economy\event\money\PlayerAddEconomyEvent;
use Voltage\Api\Economy\event\money\PlayerDelEconomyEvent;
use Voltage\Api\Economy\event\money\PlayerSetEconomyEvent;
use Voltage\Api\Economy\EconomyAPI;
use pocketmine\player\Player;
use Ramsey\Uuid\UuidInterface;

class Api
{
    private EconomyAPI $plugin;

    public function __construct(EconomyAPI $pg) {
        $this->plugin = $pg;
    }

    public function getMoney($player) : int {
        if ($player instanceof Player) {
            return EconomyAPI::getProviderSysteme()->getMoney($player->getName());
        } else if (is_string($player)) {
            return EconomyAPI::getProviderSysteme()->getMoney($player);
        } else if ($player instanceof UuidInterface) {
            $player = $this->plugin->getServer()->getPlayerByUUID($player);
            if ($player instanceof Player) {
                return EconomyAPI::getProviderSysteme()->getMoney($player->getName());
            }
        }
        return 0;
    }

    public function addMoney($player, int $amount) : void {
        if ($player instanceof Player) {
            $ev = new PlayerAddEconomyEvent($this->plugin, $player->getName(),$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->addMoney($ev->getName(),$ev->getAmount());
            }
        } else if (is_string($player)) {
            $ev = new PlayerAddEconomyEvent($this->plugin, $player,$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->addMoney($ev->getName(), $ev->getAmount());
            }
        } else if ($player instanceof UuidInterface) {
            $player = $this->plugin->getServer()->getPlayerByUUID($player);
            if ($player instanceof Player) {
                $ev = new PlayerAddEconomyEvent($this->plugin, $player->getName(), $amount);
                $ev->call();
                if ($ev->getAmount() <= 0) {
                    $ev->cancel();
                }
                if (!$ev->isCancelled()) {
                    EconomyAPI::getProviderSysteme()->addMoney($ev->getName(), $ev->getAmount());
                }
            }
        }
    }

    public function setMoney($player, int $amount) : void {
        if ($player instanceof Player) {
            $ev = new PlayerSetEconomyEvent($this->plugin, $player->getName(),$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->setMoney($ev->getName(),$ev->getAmount());
            }
        } else if (is_string($player)) {
            $ev = new PlayerSetEconomyEvent($this->plugin, $player,$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->setMoney($ev->getName(), $ev->getAmount());
            }
        } else if ($player instanceof UuidInterface) {
            $player = $this->plugin->getServer()->getPlayerByUUID($player);
            if ($player instanceof Player) {
                $ev = new PlayerSetEconomyEvent($this->plugin, $player->getName(), $amount);
                $ev->call();
                if ($ev->getAmount() <= 0) {
                    $ev->cancel();
                }
                if (!$ev->isCancelled()) {
                    EconomyAPI::getProviderSysteme()->setMoney($ev->getName(), $ev->getAmount());
                }
            }
        }
    }

    public function delMoney($player, int $amount) : void {
        if ($player instanceof Player) {
            $ev = new PlayerDelEconomyEvent($this->plugin, $player->getName(),$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->delMoney($ev->getName(),$ev->getAmount());
            }
        } else if (is_string($player)) {
            $ev = new PlayerDelEconomyEvent($this->plugin, $player,$amount);
            $ev->call();
            if ($ev->getAmount() <= 0) {
                $ev->cancel();
            }
            if (!$ev->isCancelled()) {
                EconomyAPI::getProviderSysteme()->delMoney($ev->getName(), $ev->getAmount());
            }
        } else if ($player instanceof UuidInterface) {
            $player = $this->plugin->getServer()->getPlayerByUUID($player);
            if ($player instanceof Player) {
                $ev = new PlayerDelEconomyEvent($this->plugin, $player->getName(), $amount);
                $ev->call();
                if ($ev->getAmount() <= 0) {
                    $ev->cancel();
                }
                if (!$ev->isCancelled()) {
                    EconomyAPI::getProviderSysteme()->delMoney($ev->getName(), $ev->getAmount());
                }
            }
        }
    }
}