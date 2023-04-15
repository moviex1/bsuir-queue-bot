<?php

namespace App\States;

use App\Telegram;

abstract class State
{
    public function __construct(protected Telegram $telegram, protected StateManager $stateManager)
    {
    }

    abstract public function handleInput(array $params) : void;

}