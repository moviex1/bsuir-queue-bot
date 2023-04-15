<?php

namespace App\States;

class StateManager
{
    private array $states;

    public function getCurrentState($id): ?array
    {
        return $this->states[$id] ?? null;
    }


    public function setState($id, $message_id,  State $state): void
    {
        $this->states[$id] = ['state' => $state, 'message_id' => $message_id, 'data' => []];
    }

    public function addDataToState($id, mixed $data, string $key) : void
    {
        $this->states[$id]['data'][$key] = $data;
    }

    public function changeState(int $id ,State $state) : void
    {
        $this->states[$id]['state'] = $state;
    }

    public function changeMessageId(int $id, int $newMessageId) : void
    {
        $this->states[$id]['message_id'] = $newMessageId;
    }

    public function removeUserState($id): void
    {
        unset($this->states[$id]);
    }


}