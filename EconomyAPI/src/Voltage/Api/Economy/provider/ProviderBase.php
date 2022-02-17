<?php

namespace Voltage\Api\Economy\provider;

use Voltage\Api\Economy\EconomyAPI;

abstract class ProviderBase
{
    private EconomyAPI $plugin;
    private int $basemoney = 1000;
    private int $maxmoney = 9223372036854775807;
    private string $name = "default";
    
    public function __construct(EconomyAPI $pg, ?string $name = null, ?int $default = null, ?int $maxmoney = null) {
        $this->plugin = $pg;
        if (!is_null($name)) {
            $this->name = $name;
        }
        if (!is_null($maxmoney)) {
            $this->maxmoney = $maxmoney;
        }
        if (!is_null($maxmoney)) {
            $this->basemoney = $default;
        }
    }

    protected function getPlugin() : EconomyAPI {
        return $this->plugin;
    }

    abstract public function load() : void;

    abstract public function getName() : string;

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

    public function getEconomyName() : string {
        return $this->name;
    }

}