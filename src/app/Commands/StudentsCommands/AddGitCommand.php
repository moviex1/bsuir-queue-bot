<?php

namespace App\Commands\StudentsCommands;

use App\Commands\StudentCommand;
use App\Message;
use App\States\EnteringGitState;

class AddGitCommand extends StudentCommand
{

    public function execute(): void
    {
        $this->telegram->sendMessage($this->params['chat_id'], Message::make('addGit.enterGit'));
        $this->stateManager->setState(
            $this->params['user_id'],
            $this->params['message_id'],
            new EnteringGitState($this->telegram, $this->stateManager)
        );
    }

}