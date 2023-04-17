<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\Command;
use App\States\EnteringGroupState;
use Database\Entity\User;

class RegisterCommand extends Command
{

    public function execute(): void
    {
        $userRepository = App::entityManager()->getRepository(User::class);

        if (is_null($userRepository->getById($this->params['user_id']))) {

            $this->stateManager->setState(
                $this->params['user_id'],
                $this->params['message_id'],
                new EnteringGroupState($this->telegram, $this->stateManager)
            );

            $this->telegram->sendMessage($this->params['chat_id'], 'Enter group');
        } else {
            $this->telegram->sendMessage($this->params['chat_id'], 'You are already registered');
        }
    }

}