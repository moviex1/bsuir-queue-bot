<?php

namespace App\Commands\StudentsCommands;

use App\Commands\StudentCommand;
use App\Message;

class StartCommand extends StudentCommand
{

    public function execute(): void
    {
        $this->telegram->sendMessage($this->params['chat_id'], Message::make('start'));
    }

}