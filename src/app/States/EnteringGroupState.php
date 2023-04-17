<?php

namespace App\States;

use App\Schedule;
use App\Telegram;

class EnteringGroupState extends State
{

    public function handleInput(array $params): void
    {
        if(!is_null(Schedule::getLessons($params['message']))){
            $this->stateManager->addDataToState($params['user_id'],[
                'group' => $params['message']
            ]);
            $this->stateManager->changeState($params['user_id'], new EnteringNameState($this->telegram,$this->stateManager));
            $this->telegram->sendMessage($params['chat_id'], 'Enter name');
        } else {
            $this->telegram->sendMessage($params['chat_id'], 'Enter valid group');
        }
    }

}