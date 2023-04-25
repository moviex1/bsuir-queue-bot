<?php

namespace App\Commands\StudentsCommands;

use App\Commands\StudentCommand;

class CancelCommand extends StudentCommand
{

    public function execute(): void
    {
        if ($this->stateManager->getCurrentState($this->params['user_id'])) {
            $messageId = $this->stateManager->getMessageId($this->params['user_id']);
            $this->stateManager->removeUserState($this->params['user_id']);
            $this->telegram->deleteMessage($this->params['chat_id'], $messageId);
            $this->telegram->sendMessage($this->params['chat_id'], '<b>Вы отменили ввод</b>');
        } else {
            $this->telegram->sendMessage($this->params['chat_id'], '<b>У вас нету активного ввода</b>');
        }
    }

}