<?php

namespace App\Commands;

use App\App;
use Database\Entity\User;

class CancelCommand extends Command
{

    public function execute(): void
    {
        if ($this->stateManager->getCurrentState($this->params['user_id'])) {
            $messageId = $this->stateManager->getMessageId($this->params['user_id']);
            $this->stateManager->removeUserState($this->params['user_id']);
            $this->telegram->deleteMessage($this->params['chat_id'], $messageId);
            $this->telegram->sendMessage($this->params['chat_id'], 'You canceled entering');
        } else {
            $this->telegram->sendMessage($this->params['chat_id'], 'You dont have anything to cancel');
        }
    }

}