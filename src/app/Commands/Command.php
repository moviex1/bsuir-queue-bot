<?php

namespace App\Commands;

use App\Telegram;

abstract class Command
{
    public function __construct(protected Telegram $telegram,protected array $params)
    {
    }

    abstract public function execute() : void;
}