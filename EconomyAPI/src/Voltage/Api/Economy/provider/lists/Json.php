<?php

namespace Voltage\Api\Economy\provider\lists;

use Voltage\Api\Economy\provider\ProviderBase;
use pocketmine\utils\Config;

class Json extends ProviderBase
{

    private Config $player;

    public function load() : void {
        $this->player = new Config($this->getPlugin()->getDataFolder()."players.json",Config::JSON);
    }

    public function getName() : string {
        return "Json";
    }

    public function existMoney(string $name) : bool {
        $name = strtolower($name);
        return $this->player->exists($name);
    }

    public function getMoney(string $name) : int {
        $name = strtolower($name);
        if ($this->player->exists($name)) {
            return $this->player->get($name);
        }
        return 0;
    }

    public function addMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($this->player->exists($name)) {
                if ($this->player->get($name) + $amount >= $this->getMaxMoney()) {
                    $this->player->set($name, $this->getMaxMoney());
                } else {
                    $this->player->set($name,$this->player->get($name) + $amount);
                }
            } else {
                $this->player->set($name, $amount);
            }
        }
    }

    public function setMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($amount >= $this->getMaxMoney()) {
                $this->player->set($name, $this->getMaxMoney());
            } else {
                $this->player->set($name, $amount);
            }
        } else {
            $this->player->set($name, 0);
        }
    }

    public function delMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($this->player->exists($name)) {
                if ($this->player->get($name) - $amount <= 0) {
                    $this->player->set($name, 0);
                } else {
                    $this->player->set($name,$this->player->get($name) - $amount);
                }
            } else {
                $this->player->set($name, 0);
            }
        }
    }

    public function getTopMoneyArsort() : array
    {
        $list = $this->player->getAll();
        arsort($list);
        return $list;
    }

    public function getTopMoneyAsort() : array
    {
        $list = $this->player->getAll();
        asort($list);
        return $list;
    }

}