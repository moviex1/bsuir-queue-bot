<?php

namespace App\Commands;

use App\Message;
use App\Telegram;
use Database\Models\Queue;

class RemoveCommand implements Command
{
    public function __construct(private Telegram $telegram,private array $params)
    {
    }

    public function execute(): void
    {
        /**
         * Remove user from queue
         */

        $queue = new Queue();
        $user = $queue->getById($this->params['user_id']);
        $queue->remove($this->params['user_id']);
        $this->telegram->sendMessage($this->params['chat_id'], Message::make('remove', compact('user')));
    }
}