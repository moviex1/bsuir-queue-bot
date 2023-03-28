<?php

namespace App\Commands;

use App\Message;
use App\Telegram;
use Database\Models\Queue;

class RemoveCommand implements Command
{
    public function __construct(private Telegram $telegram,private int $user_id, private int $chat_id)
    {
    }

    public function execute(): void
    {
        /**
         * Remove user from queue
         */

        $queue = new Queue();
        $user = $queue->getById($this->user_id);
        $queue->remove($this->user_id);
        $this->telegram->sendMessage($this->chat_id, Message::make('remove', compact('user')));
    }
}