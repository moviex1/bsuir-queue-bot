<?php

namespace App\States;

use App\Message;
use App\Schedule;

class EnteringGroupState extends State
{

    public function handleInput(array $params): void
    {
        if(Schedule::getLessons($params['message'])){
            $this->stateManager->addDataToState($params['user_id'],[
                'group' => $params['message']
            ]);
            $this->stateManager->changeState($params['user_id'], new EnteringNameState($this->telegram,$this->stateManager));
            $this->telegram->sendMessage($params['chat_id'], Message::make('register.enterName'));
        } else {
            $this->telegram->sendMessage($params['chat_id'], Message::make('register.invalidGroup'));
        }
    }

}