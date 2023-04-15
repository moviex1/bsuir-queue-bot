<?php

namespace App\States;

use App\App;
use App\Enums\Role;
use App\Telegram;
use Database\Entity\User;
use Helpers\Validation;

class EnteringNameState extends State
{
    public function handleInput(array $params): void
    {
        if(Validation::validateUsername($params['message'])){
            App::entityManager()->getRepository(User::class)->create([
                'role' => Role::Student,
                'name' => $params['message'],
                'tg_id' => $params['user_id'],
                'tg_username' => $params['tg_username'],
                'group' => $this->stateManager->getCurrentState($params['user_id'])['data']['group']
            ]);
            $this->telegram->sendMessage($params['chat_id'], 'You successfully registered');
            $this->stateManager->removeUserState($params['user_id']);
        } else{
            $this->telegram->sendMessage($params['user_id'], 'name should be less than 50 characters');
        }
    }
}