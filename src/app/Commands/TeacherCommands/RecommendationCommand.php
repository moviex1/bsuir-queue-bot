<?php

namespace App\Commands\TeacherCommands;

use App\App;
use App\Commands\TeacherCommand;
use App\States\ChoosingGroupRecommendationState;
use Database\Entity\User;

class RecommendationCommand extends TeacherCommand
{
    public function execute(): void
    {
        $user = App::entityManager()->getRepository(User::class)->findOneByTgId($this->params['user_id']);
        if (!$this->checkIfTeacher($user)) {
            return;
        }
        $this->stateManager->setState(
            $this->params['user_id'],
            $this->params['message_id'],
            new ChoosingGroupRecommendationState($this->telegram, $this->stateManager)
        );
        $this->telegram->sendMessage($this->params['chat_id'], 'Enter group');
    }


}