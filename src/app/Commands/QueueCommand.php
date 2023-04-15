<?php

namespace App\Commands;

use App\App;
use App\Schedule;
use App\States\ChoosingDateState;
use Database\Entity\User;

class QueueCommand extends Command
{

    public function execute(): void
    {
        /**
         * Validates queue command. If input valid add user to queue and return message.
         * Otherwise, function sends an error message to the user.
         *
         */
        $user = App::entityManager()->getRepository(User::class)->getById($this->params['user_id']);

        $this->checkIfUserExist($user);

        $buttons = $this->getButtons($user->getGroup());

        $this->stateManager->setState(
            $this->params['user_id'],
            $this->getButtonsId($buttons),
            new ChoosingDateState($this->telegram, $this->stateManager)
        );

        $this->stateManager->addDataToState($this->params['user_id'], $buttons, 'buttons');
    }

    private function getButtons(int $group): array
    {
        return [
            ['text' => Schedule::getLessons($group)[0]['date'], 'callback_data' => '0'],
            ['text' => Schedule::getLessons($group)[1]['date'], 'callback_data' => '1']
        ];
    }

    private function checkIfUserExist(?User $user)
    {
        if (!$user) {
            $this->telegram->sendMessage(
                $this->params['chat_id'],
                'firstly you need to register, use command /register'
            );
        }
    }

    private function getButtonsId(array $buttons): int
    {
        $response = $this->telegram->sendButtons($this->params['chat_id'], $buttons);
        return json_decode($response, true)['result']['message_id'];
    }
}