<?php

namespace App\Commands\StudentsCommands;

use App\Commands\StudentCommand;
use App\Message;

class HelpCommand extends StudentCommand
{
    public function execute(): void
    {
        $this->telegram->sendMessage($this->params['user_id'], Message::make('help'));
    }
}