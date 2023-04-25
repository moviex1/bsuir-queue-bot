<?php

namespace App\States;


use App\App;
use Database\Entity\User;

class EnteringStudentState extends State
{
    public function handleInput(array $params): void
    {
        $group = $this->stateManager->getStateData($params['user_id'], 'group');
        $student = App::entityManager()->getRepository(User::class)->findOneBy([
            'group' => $group,
            'id' => $params['message']
        ]);
        if ($student) {
            $this->stateManager->changeState(
                $params['user_id'],
                new EnteringRecommendationState($this->telegram, $this->stateManager)
            );
            $this->stateManager->addDataToState($params['user_id'], [
                'student' => $student
            ]);
            $this->telegram->sendMessage(
                $params['chat_id'],
                '<b>Введите рекомендацию для пользователя ' . $student->getName() . '</b>'
            );
        } else {
            $this->telegram->sendMessage($params['chat_id'], '<b>Пользователя с таким id нету в данной группе</b>');
        }
    }

}