<?php

namespace App\States;

use App\App;
use App\Telegram;
use Database\Entity\User;

class EnteringGitState implements State
{
    public function __construct(protected Telegram $telegram, protected StateManager $stateManager)
    {
    }
    public function handleInput(array $params): void
    {
        $userRepository = App::entityManager()
            ->getRepository(User::class);
        $user = $userRepository
            ->findOneByTgId($params['user_id']);
        $userWithGit = $userRepository
            ->findOneByGit($params['message']);

        if ($userWithGit) {
            $this->telegram->sendMessage($user->getTgId(), '<b>Этот Github уже занят!</b>');
            return;
        }

        $userRepository->addGit($user, $params['message']);

        $this->stateManager->removeUserState($user->getTgId());
        $this->telegram->sendMessage($user->getTgId(), '<b>Вы успешно ввели Github!😽</b>');
    }
}