<?php

namespace App\States;

use App\Message;
use App\Telegram;
use Helpers\Validation;

class EnteringPlaceState extends State
{
    public function handleInput(array $params) : void
    {
        if(Validation::validatePlace()){
            $this->stateManager->setState($params['id'], $params['message_id'], new ChoosingDateState($this->telegram, $this->stateManager));
            $this->telegram->sendMessage($params['chat_id'], Message::make());
        }
    }

}