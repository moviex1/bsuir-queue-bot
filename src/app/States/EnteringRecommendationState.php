<?php

namespace App\States;


class EnteringRecommendationState extends State
{

    private function getMessageId(string $message): ?int
    {
        return json_decode($message, true)['result']['message_id'] ?? null;
    }

    public function handleInput(array $params): void
    {
        if ($params['message'] != 'state') {
            $messageId = $this->stateManager->getCurrentState($params['user_id'])['message_id'];
            $this->telegram->deleteMessage($params['chat_id'], $messageId);
            $message = $this->telegram->sendMessage($params['chat_id'], "State message");
            $newMessageId = $this->getMessageId($message);
            $this->stateManager->setState($params['user_id'], $newMessageId, $this);
        } else {
            $this->stateManager->removeUserState($params['user_id']);
        }
    }
}