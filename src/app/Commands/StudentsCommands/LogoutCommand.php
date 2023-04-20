<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\Command;
use App\Commands\StudentCommand;
use Database\Entity\User;

class LogoutCommand extends StudentCommand
{

    public function execute(): void
    {
        $entityManager = App::entityManager();
        $user = $entityManager->getRepository(User::class)->findOneByTgId($this->params['user_id']);
        if ($user) {
            foreach ($user->getQueues() as $queue) {
                App::entityManager()->remove($queue);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            $this->telegram->sendMessage($this->params['chat_id'], 'You were logged out');
        } else {
            $this->telegram->sendMessage($this->params['chat_id'], 'You need to register first');
        }
    }
}