<?php

namespace App\Commands;

use App\States\StateManager;
use App\Telegram;

interface Command
{

    public function execute() : void;
}