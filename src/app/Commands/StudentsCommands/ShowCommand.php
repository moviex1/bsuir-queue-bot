<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use App\Message;
use Database\Entity\Queue;
use Database\Entity\User;

class ShowCommand extends StudentCommand
{
    public function execute(): void
    {
        /**
         * Shows the current position of the user in the queue if he is in the queue,
         * otherwise sends him a message that he is not in the queue
         */

        $user = App::entityManager()
            ->getRepository(User::class)
            ->findOneByTgId($this->params['user_id']);

        $queue = App::entityManager()
            ->getRepository(Queue::class)
            ->getUserQueue($user);

        $this->telegram->sendMessage(
            $this->params['chat_id'],
            Message::make('show', [
                'queue' => $queue,
                'user' => $user
            ])
        );
    }

}