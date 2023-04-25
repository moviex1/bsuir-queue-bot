<?php

namespace App\States;


use App\App;
use App\Message;
use Database\Entity\Recommendation;
use Database\Entity\User;

class EnteringRecommendationState extends State
{

    public function handleInput(array $params): void
    {
        $student = $this->stateManager->getStateData($params['user_id'], 'student');
        $teacher = App::entityManager()->getRepository(User::class)->findOneByTgId($params['user_id']);
        $this->stateManager->removeUserState($params['user_id']);
        App::entityManager()->getRepository(Recommendation::class)->addRecommendation([
            'recommendation' => $params['message'],
            'student' => $student,
            'teacher' => $teacher
        ]);
        $this->telegram->sendMessage(
            $params['chat_id'],
            Message::make('recommendations.enteredRecommendation', [
                'student' => $student,
                'recommendation' => $params['message']
            ])
        );
        $this->telegram->sendMessage(
            $student->getTgId(),
            '<b>У вас новая рекомендация от преподавателя!😙(вы можете посмотреть ее при помощи команды /myrecommendations )</b>'
        );
    }
}