<?php

namespace Voltage\Api\Economy\event\money;

use Voltage\Api\Economy\event\MoneyEvent;
use Voltage\Api\Economy\EconomyAPI;
use pocketmine\player\Player;

class PlayerCreateAccountMoneyEvent extends MoneyEvent
{
    private int $basemoney;
    private Player $player;

    public function __construct(EconomyAPI $plugin, Player $player, int $basemoney)
    {
        $this->player = $player;
        $this->basemoney = $basemoney;
        parent::__construct($plugin);
    }
    
    public function getPlayer() : Player {
        return $this->player;
    }

    public function getBaseMoney() : int {
        return $this->basemoney;
    }

    public function setBaseMoney(int $value) : void {
        $this->basemoney = $value;
    }
}