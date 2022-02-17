<?php

namespace Voltage\Api\Economy\provider\lists;

use JsonException;
use Voltage\Api\Economy\provider\ProviderBase;
use pocketmine\utils\Config;

class Yaml extends ProviderBase
{

    private Config $player;

    public function load() : void {
        $this->player = new Config($this->getPlugin()->getDataFolder(). $this->getEconomyName() . ".yml",Config::YAML);
    }

    public function getName() : string {
        return "Yaml";
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

    /**
     * @throws JsonException
     */
    public function addMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($this->player->exists($name)) {
                if ($this->player->get($name) + $amount >= $this->getMaxMoney()) {
                    $this->player->set($name, $this->getMaxMoney());
                    $this->player->save();
                } else {
                    $this->player->set($name,$this->player->get($name) + $amount);
                    $this->player->save();
                }
            } else {
                $this->player->set($name, $amount);
                $this->player->save();
            }
        }
    }

    /**
     * @throws JsonException
     */
    public function setMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($amount >= $this->getMaxMoney()) {
                $this->player->set($name, $this->getMaxMoney());
                $this->player->save();
            } else {
                $this->player->set($name, $amount);
                $this->player->save();
            }
        } else {
            $this->player->set($name, 0);
            $this->player->save();
        }
    }

    /**
     * @throws JsonException
     */
    public function delMoney(string $name, int $amount) : void {
        $name = strtolower($name);
        if ($amount > 0) {
            if ($this->player->exists($name)) {
                if ($this->player->get($name) - $amount <= 0) {
                    $this->player->set($name, 0);
                    $this->player->save();
                } else {
                    $this->player->set($name,$this->player->get($name) - $amount);
                    $this->player->save();
                }
            } else {
                $this->player->set($name, 0);
                $this->player->save();
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