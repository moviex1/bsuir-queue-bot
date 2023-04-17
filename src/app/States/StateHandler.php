<?php

namespace App\States;

use App\Telegram;

class StateHandler
{
    public function __construct(private StateManager $stateManager)
    {
    }

    public function handleInput(array $params): void
    {
        $stateObject = $this->stateManager->getCurrentState($params['user_id'])['state'] ?? null;

        $stateObject?->handleInput($params);
    }

    public function hasState(array $params): bool
    {
        return empty($this->stateManager->getCurrentState($params['user_id']));
    }

}