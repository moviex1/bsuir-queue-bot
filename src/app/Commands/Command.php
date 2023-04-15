<?php

namespace App\Commands;

use App\States\StateManager;
use App\Telegram;

abstract class Command
{
    public function __construct(protected Telegram $telegram,protected array $params, protected StateManager $stateManager)
    {
    }

    abstract public function execute() : void;
}