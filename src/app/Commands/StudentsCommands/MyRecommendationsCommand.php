<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use App\Message;
use Database\Entity\Recommendation;
use Database\Entity\User;

class MyRecommendationsCommand extends StudentCommand
{

    public function execute(): void
    {
        $user = App::entityManager()
            ->getRepository(User::class)
            ->findOneByTgId($this->params['user_id']);
        $recommendations = App::entityManager()
            ->getRepository(Recommendation::class)
            ->getUserRecommendations($user);

        $this->telegram->sendMessage(
            $this->params['chat_id'],
            Message::make('recommendations.myRecommendations', compact('recommendations'))
        );
    }

}