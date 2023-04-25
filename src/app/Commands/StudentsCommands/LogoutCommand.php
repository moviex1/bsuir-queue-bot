<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use Database\Entity\User;

class LogoutCommand extends StudentCommand
{

    public function execute(): void
    {
        $entityManager = App::entityManager();
        $user = $entityManager->getRepository(User::class)->findOneByTgId($this->params['user_id']);
        if ($user) {
            $entityManager->getRepository(User::class)->deleteUser($user);
            $this->telegram->sendMessage($this->params['chat_id'], '<b>Вы вышли из своего аккаунта</b>');
        } else {
            $this->telegram->sendMessage($this->params['chat_id'], '<b>Сперва вам нужно зарегистрироваться</b>');
        }
    }
}