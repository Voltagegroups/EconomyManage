<?php

namespace Voltage\Api\Economy\event\money;

use Voltage\Api\Economy\event\MoneyEvent;
use Voltage\Api\Economy\EconomyAPI;

class PlayerSetEconomyEvent extends MoneyEvent
{
    private string $name;
    private int $amount;

    public function __construct(EconomyAPI $plugin, string $name, int $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        parent::__construct($plugin);
    }

    public function getName() : string {
        return $this->name;
    }

    public function getAmount() : int {
        return $this->amount;
    }

    public function setAmount(int $amount) : void {
        $this->amount = $amount;
    }
}