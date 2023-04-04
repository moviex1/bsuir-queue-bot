<?php

namespace App\Commands;

class CommandFactory
{
    private $telegram;
    private $params;

    public function __construct($telegram, $params)
    {
        $this->telegram = $telegram;
        $this->params = $params;
    }

    public function createNewCommand($command) : ?Command
    {
        $classname = __NAMESPACE__ . '\\' . ucfirst(str_replace('/', '',$command)) . 'Command';
        if(class_exists($classname)){
            return new $classname($this->telegram, $this->params);
        }
        return null;
    }

}