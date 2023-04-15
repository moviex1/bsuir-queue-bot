<?php

namespace App\Commands;

use App\States\StateManager;

abstract class CommandFactory
{

    protected $params;

    /**
     * @param mixed $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }

    abstract public function createNewCommand(string $message) : ?Command;
}