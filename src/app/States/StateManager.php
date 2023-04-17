<?php

namespace App\States;

class StateManager
{
    private array $states;

    public function getCurrentState(int $id): ?array
    {
        return $this->states[$id] ?? null;
    }


    public function setState(int $id, int $message_id, State $state): void
    {
        $this->states[$id] = ['state' => $state, 'message_id' => $message_id, 'data' => []];
    }

    public function addDataToState(int $id, array $data): void
    {
        foreach ($data as $key => $value) {
            $this->states[$id]['data'][$key] = $value;
        }
    }

    public function changeState(int $id, State $state): void
    {
        $this->states[$id]['state'] = $state;
    }

    public function changeMessageId(int $id, int $newMessageId): void
    {
        $this->states[$id]['message_id'] = $newMessageId;
    }

    public function getMessageId(int $id): int
    {
        return $this->states[$id]['message_id'];
    }

    public function removeUserState(int $id): void
    {
        unset($this->states[$id]);
    }

    public function getStateData(int $id, string $key): mixed
    {
        return $this->states[$id]['data'][$key] ?? throw new \LogicException("No such key in data of user");
    }


}