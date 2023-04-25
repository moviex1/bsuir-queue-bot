<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\StudentCommand;
use App\Message;
use App\Schedule;
use App\States\ChoosingDateState;
use Database\Entity\Queue;
use Database\Entity\User;

class QueueCommand extends StudentCommand
{

    public function execute(): void
    {
        $user = App::entityManager()
            ->getRepository(User::class)
            ->getById($this->params['user_id']);

        $queue = App::entityManager()
            ->getRepository(Queue::class)
            ->getUserQueue($user);

        if ($queue) {
            $this->telegram->sendMessage($user->getTgId(), Message::make('queue.reserved', compact('queue')));
            return;
        }

        $buttons = $this->getButtons($user->getGroup());
        $response = $this->telegram->sendButtons($this->params['chat_id'], Message::make('buttons.dateButtons'),$buttons);

        $this->stateManager->setState(
            $user->getTgId(),
            $this->getButtonsId($response),
            new ChoosingDateState($this->telegram, $this->stateManager)
        );

        $this->stateManager->addDataToState(
            $this->params['user_id'],
            [
                'buttons' => $buttons,
                'command' => 'queue',
                'user' => $user
            ]
        );
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