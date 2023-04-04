<?php

namespace App\Commands;

class TelegramCommandFactory extends CommandFactory
{
    private $telegram;

    public function __construct($telegram)
    {
        $this->telegram = $telegram;
    }


    public function createNewCommand(string $message) : ?Command
    {
        $command = explode('@', $message)[0];
        $classname = __NAMESPACE__ . '\\' . ucfirst(str_replace('/', '',$command)) . 'Command';
        if(class_exists($classname)){
            return new $classname($this->telegram, $this->params);
        }
        return null;
    }



}