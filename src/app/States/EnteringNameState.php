<?php

namespace App\States;

use App\App;
use App\Enums\Role;
use App\Message;
use App\Telegram;
use Database\Entity\User;
use Helpers\Validation;

class EnteringNameState implements State
{
    public function __construct(protected Telegram $telegram, protected StateManager $stateManager)
    {
    }
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
            $this->telegram->sendMessage($params['chat_id'], Message::make('register.success'));
            $this->stateManager->removeUserState($params['user_id']);
        } else{
            $this->telegram->sendMessage($params['user_id'], '<b>Имя должно быть менее 50 символов!</b>');
        }
    }
}