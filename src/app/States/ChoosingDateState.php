<?php

namespace App\States;


class ChoosingDateState extends State
{

    private function getMessageId(string $message): ?int
    {
        return json_decode($message, true)['result']['message_id'] ?? null;
    }

    public function handleInput(array $params): void
    {
        $state = $this->stateManager->getCurrentState($params['user_id']);
        $buttons = $state['data']['buttons'];
        if ($params['callback_data'] === null) {
            $this->telegram->deleteMessage($params['chat_id'], $state['message_id']);
            $message = $this->telegram->sendButtons($params['chat_id'], $buttons);
            $messageId = $this->getMessageId($message);
            $this->stateManager->changeMessageId($params['user_id'], $messageId);
        } else {
            $this->telegram->deleteMessage($params['chat_id'], $state['message_id']);
            $this->handleCommand($this->stateManager, $state['data']['command']);
            $this->stateManager->removeUserState($params['user_id']);
            $this->telegram->sendMessage($params['chat_id'], 'you are successfully take a place!');
        }
    }

    private function handleCommand(StateManager $stateManager, string $command)
    {
        switch ($command) {
            case 'list':
                echo 'list';
                break;
            case 'show':
                echo 'show';
                break;
            case 'queue':
                echo 'queue';
                break;
            case 'remove':
                echo 'remove';
            default:
                break;
        }
    }

}

