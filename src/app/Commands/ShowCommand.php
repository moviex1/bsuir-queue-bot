<?php

namespace App\Commands;

use App\Message;
use App\Telegram;
use Database\Entities\Queue;

class ShowCommand extends Command
{
    public function execute(): void
    {
        /**
         * Shows the current position of the user in the queue if he is in the queue,
         * otherwise sends him a message that he is not in the queue
         */

        $queue = new Queue();
        $user = $queue->getById($this->params['user_id']);
        $this->telegram->sendMessage($this->params['chat_id'], Message::make('show', compact('user')));
    }

}