<?php

namespace App\States;

use App\Schedule;

class ChoosingGroupRecommendationState extends State
{
    public function handleInput(array $params): void
    {
        if (Schedule::getLessons($params['message'])) {
            $this->stateManager->changeState(
                $params['user_id'],
                new EnteringStudentState($this->telegram, $this->stateManager)
            );
            $this->stateManager->addDataToState($params['user_id'], [
                'group' => $params['message']
            ]);
            $this->telegram->sendMessage($params['chat_id'], 'Enter student id');
        } else {
            $this->telegram->sendMessage($params['chat_id'], 'You entered invalid group');
        }
    }

}