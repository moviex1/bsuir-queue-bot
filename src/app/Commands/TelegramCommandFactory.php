<?php

namespace App\Commands;

use App\States\StateManager;
use App\Telegram;

class TelegramCommandFactory extends CommandFactory
{
    private Telegram $telegram;

    public function __construct(Telegram $telegram, protected StateManager $stateManager)
    {
        $this->telegram = $telegram;
    }


    public function createNewCommand(string $message) : ?Command
    {
        $command = explode('@', $message)[0];
        $classname = __NAMESPACE__ . '\\' . str_replace('/', '',$command, $replaces) . 'Command';
        if(class_exists($classname) && $replaces == 1 && $command[0] == '/'){
            return new $classname($this->telegram, $this->params, $this->stateManager);
        }
        return null;
    }



}