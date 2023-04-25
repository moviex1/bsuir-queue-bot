<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use App\Message;
use App\Schedule;
use App\States\ChoosingDateState;
use Database\Entity\User;

class ListCommand extends StudentCommand
{

    public function execute(): void
    {
        /**
         * Show all the reserves.
         *
         * The Emojis array is used to randomly generate emojis for queue members
         *
         * @var array $emojis
         */
        $user = App::entityManager()->getRepository(User::class)->findOneByTgId($this->params['user_id']);

        $response = $this->telegram->sendButtons(
            $this->params['chat_id'],
            Message::make('buttons.dateButtons'),
            $this->getButtons($user->getGroup())
        );
        $this->stateManager->setState(
            $user->getTgId(),
            $this->getButtonsId($response),
            new ChoosingDateState($this->telegram, $this->stateManager)
        );


        $this->stateManager->addDataToState($user->getTgId(), [
            'buttons' => $this->getButtons($user->getGroup()),
            'user' => $user,
            'command' => 'list'
        ]);
    }

    private function getButtons(int $group): array
    {
        return [
            ['text' => Schedule::getLessons($group)[0]['date'], 'callback_data' => '0'],
            ['text' => Schedule::getLessons($group)[1]['date'], 'callback_data' => '1']
        ];
    }

    private function getButtonsId(string $response): int
    {
        return json_decode($response, true)['result']['message_id'];
    }
}