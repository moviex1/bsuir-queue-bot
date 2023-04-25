<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use App\Message;
use Database\Entity\Queue;
use Database\Entity\User;

class RemoveCommand extends StudentCommand
{

    public function execute(): void
    {
        /**
         * Remove user from queue
         */

        $entityManager = App::entityManager();
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneByTgId($this->params['user_id']);

        $queue = $entityManager
            ->getRepository(Queue::class)
            ->getUserQueue($user);

        if ($queue) {
            $entityManager->remove($queue);
            $entityManager->flush();
        }
        $this->telegram->sendMessage($user->getTgId(), Message::make('remove', compact('queue')));
    }
}