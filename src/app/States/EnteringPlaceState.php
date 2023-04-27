<?php

namespace App\States;

use App\App;
use App\Message;
use App\Schedule;
use App\Telegram;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;
use Helpers\Validation;

class EnteringPlaceState implements State
{
    public function __construct(protected Telegram $telegram, protected StateManager $stateManager)
    {
    }
    public function handleInput(array $params): void
    {
        $user = $this->stateManager->getStateData($params['user_id'], 'user');
        $place = $params['message'];

        if (!Validation::validateNum($place)) {
            $this->telegram->sendMessage($params['chat_id'], '<b>Место должно быть от 1 до 30🐹</b>');
            return;
        }

        $lessonDate = $this->stateManager->getStateData($user->getTgId(), 'lessonDate');

        if ($this->isPlaceTaken($place, $lessonDate)) {
            $this->telegram->sendMessage($params['chat_id'], Message::make('queue.reservedBySomeone'));
            return;
        }

        $this->handleQueue($user, $lessonDate, $place);
    }

    private function handleQueue(User $user, DateTime $lessonDate, int $place): void
    {
        $queueRepository = App::entityManager()->getRepository(Queue::class);
        $queueRepository->addToQueue([
            'user' => $user,
            'lessonDate' => $lessonDate,
            'place' => $place
        ]);

        $this->stateManager->removeUserState($user->getTgId());
        $this->telegram->sendMessage(
            $user->getTgId(),
            Message::make('queue.queue', [
                'place' => $place,
                'lessonDate' => $lessonDate->format('Y-m-d h:i:s')
            ])
        );
    }

    private function isPlaceTaken(int $place, DateTime $lessonDate)
    {
        $queueRepository = App::entityManager()->getRepository(Queue::class);
        return !is_null(
            $queueRepository->findOneBy([
                'place' => $place,
                'lessonDate' => $lessonDate
            ])
        );
    }

}