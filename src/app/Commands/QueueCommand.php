<?php

namespace App\Commands;

use App\Message;
use App\Telegram;
use Database\Models\Queue;
use Helpers\Validation;

class QueueCommand extends Command
{

    public function execute() : void
    {
        /**
         * Validates queue command. If input valid add user to queue and return message.
         * Otherwise, function sends an error message to the user.
         *
         */

        $command = explode(" ", $this->params['message'], 3);
        $queue = new Queue();

        if (Validation::validateCommand($command)) {
            if (!$user = Validation::validateUser($this->params['user_id'])) {
                if (Validation::validatePlace($command[1])) {
                    $reserve = $queue->add([
                        'user_id' => $this->params['user_id'],
                        'username' => $command[2],
                        'tg_username' => $this->params['tg_username'],
                        'place' => $command[1]
                    ]);
                    $this->telegram->sendMessage($this->params['chat_id'], Message::make('queue', $reserve));
                } else {
                    $this->telegram->sendMessage($this->params['chat_id'], Message::make('reservedBySomeone'));
                }
            } else {
                $this->telegram->sendMessage($this->params['chat_id'], Message::make('reserved', $user));
            }
        } else {
            $failMessage = Validation::getFailMessage($command);
            $this->telegram->sendMessage($this->params['chat_id'], $failMessage);
        }
    }

}