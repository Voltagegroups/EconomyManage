<?php

namespace Voltage\Api\Economy\event;

use Voltage\Api\Economy\EconomyAPI;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\plugin\PluginEvent;

class MoneyEvent extends PluginEvent implements Cancellable
{
    use CancellableTrait;

    public function __construct(EconomyAPI $plugin)
    {
        parent::__construct($plugin);
    }
}