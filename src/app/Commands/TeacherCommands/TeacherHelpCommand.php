<?php

namespace App\Commands\TeacherCommands;

use App\App;
use App\Commands\TeacherCommand;
use App\Message;
use Database\Entity\User;

class TeacherHelpCommand extends TeacherCommand
{
    public function execute(): void
    {
        $user = App::entityManager()->getRepository(User::class)->findOneByTgId($this->params['user_id']);
        if(!$this->checkIfTeacher($user)){
           return;
        }
        $this->telegram->sendMessage($this->params['user_id'], Message::make('teacherHelp'));
    }
}