<?php

namespace App\Commands;

use App\Telegram;

class CommandHandler
{
    private array $queueMessages = [];

    public function __construct(private Telegram $telegram)
    {
    }

    private function checkQueue($params)
    {
        return array_key_exists($params['user_id'],$this->queueMessages);
    }

    public function handleCommand(array $params)
    {
        $command = explode("@", $params['message'])[0];

        switch($command){
            case '/queue':
                $queueCommand = new QueueCommand($this->telegram, $params);
                $queueCommand->execute();
                break;
            case '/remove':
                $removeCommand = new RemoveCommand($this->telegram, $params);
                $removeCommand->execute();
                break;
            case '/list':
                $listCommand = new ListCommand($this->telegram, $params);
                $listCommand->execute();
                break;
            case '/show':
                $showCommand = new ShowCommand($this->telegram, $params);
                $showCommand->execute();
                break;
        }
    }
}