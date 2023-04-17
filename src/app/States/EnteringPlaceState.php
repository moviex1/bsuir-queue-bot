<?php

namespace App\States;

use App\App;
use App\Schedule;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;
use Helpers\Validation;

class EnteringPlaceState extends State
{
    public function handleInput(array $params): void
    {
        $user = $this->stateManager->getStateData($params['user_id'], 'user');
        if (Validation::validateNum($params['message'])) {
            $place = $params['message'];
            $lessonDate = $this->stateManager->getStateData($user->getTgId(), 'lessonDate');

            if ($this->isPlaceTaken($place, $lessonDate)) {
                $this->telegram->sendMessage($params['user_id'], 'this place is taken');
                return;
            }

            $this->handleQueue($user, $lessonDate, $place);
        } else {
            $this->telegram->sendMessage($user->getTgId(),'Enter number between 1 and 30');
        }
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
        $this->telegram->sendMessage($user->getTgId(), 'You take ' . $place . ' place');
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