<?php

namespace App\Commands;

use App\Telegram;

class CommandHandler
{
    public function __construct(private Telegram $telegram)
    {
    }

    public function handleCommand(string $message,array $params)
    {
        if (in_array(explode(" ", $message)[0], ['/queue', '/queue@BsuirQueueBot']) ) {
            $queueCommand = new QueueCommand($this->telegram, $message , $params);
            $queueCommand->execute();
        } elseif ($message == '/remove' || $message == '/remove@BsuirQueueBot') {
            $removeCommand = new RemoveCommand($this->telegram, $params['user_id'], $params['chat_id']);
            $removeCommand->execute();
        } elseif ($message == '/list' || $message == '/list@BsuirQueueBot') {
            $listCommand = new ListCommand($this->telegram,$params['chat_id']);
            $listCommand->execute();
        } elseif ($message == '/show' || $message == '/show@BsuirQueueBot') {
            $showCommand = new ShowCommand($this->telegram,$params['user_id'],$params['chat_id']);
            $showCommand->execute();
        }
    }
}