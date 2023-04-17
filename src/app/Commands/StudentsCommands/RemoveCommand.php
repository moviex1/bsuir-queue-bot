<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\Command;
use App\Message;
use App\Schedule;
use Database\Entity\Queue;
use Database\Entity\User;

class RemoveCommand extends Command
{

    public function execute(): void
    {
        /**
         * Remove user from queue
         */

        $entityManager = App::entityManager();
        $user = $entityManager->getRepository(User::class)->findOneByTgId($this->params['user_id']);
        $queue = $entityManager->getRepository(Queue::class)->findOneBy([
            'lessonDate' => [
                Schedule::getLessons($user->getGroup())[0]['date'],
                Schedule::getLessons($user->getGroup())[1]['date']
            ],
            'user' => $user
        ]);
        if($queue){
            $entityManager->remove($queue);
            $entityManager->flush();
        }
        $this->telegram->sendMessage($user->getTgId(), Message::make('remove', compact('queue')));
    }
}