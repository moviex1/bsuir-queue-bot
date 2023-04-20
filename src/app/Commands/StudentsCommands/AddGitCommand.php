<?php

namespace App\Commands\StudentsCommands;

use App\Commands\StudentCommand;
use App\States\EnteringGitState;

class AddGitCommand extends StudentCommand
{

    public function execute(): void
    {
        $this->telegram->sendMessage($this->params['chat_id'], 'Введите ваш ник на Github');
        $this->stateManager->setState(
            $this->params['user_id'],
            $this->params['message_id'],
            new EnteringGitState($this->telegram, $this->stateManager)
        );
    }

}