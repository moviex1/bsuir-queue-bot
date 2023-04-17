<?php

namespace App\Commands\StudentsCommands;

use App\App;
use App\Commands\Command;
use App\Schedule;
use App\States\ChoosingDateState;
use Database\Entity\Queue;
use Database\Entity\User;

class QueueCommand extends Command
{

    public function execute(): void
    {
        $user = App::entityManager()->getRepository(User::class)->getById($this->params['user_id']);

        $this->checkIfUserExist($user);


        if (!is_null($this->getUserRecentQueues($user))) {
            $this->telegram->sendMessage($user->getTgId(), 'You are already in queue');
            return;
        }

        $buttons = $this->getButtons($user->getGroup());
        $response = $this->telegram->sendButtons($this->params['chat_id'], $buttons);

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

    private function checkIfUserExist(?User $user)
    {

    }

    private function getButtonsId(string $response): int
    {
        return json_decode($response, true)['result']['message_id'];
    }

    private function getUserRecentQueues(User $user)
    {
        $queueRepository = App::entityManager()->getRepository(Queue::class);
        return $queueRepository->findOneBy([
            'user' => $user,
            'lessonDate' => [
                Schedule::getLessons($user->getGroup())[0]['date'],
                Schedule::getLessons($user->getGroup())[1]['date']
            ]
        ]);
    }
}