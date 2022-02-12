<?php

namespace Voltage\Api\Economy\provider;

use Voltage\Api\Economy\EconomyAPI;

abstract class ProviderBase
{
    private EconomyAPI $plugin;
    private int $basemoney = 1000;
    private int $maxmoney = 9223372036854775807;
    
    public function __construct(EconomyAPI $pg) {
        $this->plugin = $pg;
        if (EconomyAPI::getData()->exists('default')) {
            $this->basemoney = EconomyAPI::getData()->get('default');
        }
        if (EconomyAPI::getData()->exists('max')) {
            $this->maxmoney = EconomyAPI::getData()->get('max');
        }
    }

    protected function getPlugin() : EconomyAPI {
        return $this->plugin;
    }

    abstract public function load() : void;

    abstract public function getMoney(string $name) : int;

    abstract public function addMoney(string $name, int $amount) : void;

    abstract public function delMoney(string $name, int $amount) : void;

    abstract public function setMoney(string $name, int $amount) : void;

    abstract public function existMoney(string $name) : bool;

    abstract public function getTopMoneyAsort() : array;

    abstract public function getTopMoneyArsort() : array;

    public function getBaseMoney() : int {
        return $this->basemoney;
    }

    public function getMaxMoney() : int {
        return $this->maxmoney;
    }

}