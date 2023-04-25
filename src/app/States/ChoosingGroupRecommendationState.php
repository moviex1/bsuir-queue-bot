<?php

namespace App\States;

use App\App;
use App\Message;
use App\Schedule;
use Database\Entity\User;

class ChoosingGroupRecommendationState extends State
{
    public function handleInput(array $params): void
    {
        if (Schedule::getLessons($params['message'])) {
            $students = App::entityManager()
                ->getRepository(User::class)
                ->findByGroup($params['message']);

            if (empty($students)) {
                $this->telegram->sendMessage(
                    $params['chat_id'],
                    Message::make('recommendations.group', compact('students'))
                );
                return;
            }

            $this->stateManager->changeState(
                $params['user_id'],
                new EnteringStudentState($this->telegram, $this->stateManager)
            );
            $this->stateManager->addDataToState($params['user_id'], [
                'group' => $params['message']
            ]);
            $this->telegram->sendMessage(
                $params['chat_id'],
                Message::make('recommendations.group', compact('students'))
            );
            $this->telegram->sendMessage(
                $params['chat_id'],
                '<b>Введите id студента, которому хотите оставить рекомендацию</b>'
            );
        } else {
            $this->telegram->sendMessage($params['chat_id'], '<b>Вы ввели неверную группу</b>');
        }
    }

}