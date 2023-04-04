<?php

namespace App\Commands;

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