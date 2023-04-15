<?php

namespace App\Commands;

use App\Message;
use Database\Entity\Queue;

class RemoveCommand extends Command
{

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