<?php

namespace Database\Repository;

use App\App;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;

class QueueRepository extends EntityRepository
{
    public function getAllByDate(int $group, DateTime $date): array
    {
        return $this->findBy([
            'lessonDate' => $date,
            'group' => $group
        ], ['place' => 'ASC']);
    }

    public function getUserByDate(User $user, DateTime $date)
    {
        return $this->findOneBy([
            'lessonDate' => $date,
            'user' => $user
        ]);
    }

    public function getByPlace(string $date, int $place)
    {
        return $this->findBy([
            'lessonDate' => new DateTime($date),
            'place' => $place
        ]);

    }

    public function addToQueue($data)
    {
        $entityManager = App::entityManager();

        $queue = new Queue();
        $queue->setLessonDate($data['date']);
        $queue->setUser($data['user']);
        $queue->setPlace($data['place']);

        $entityManager->persist($queue);
        $entityManager->flush();

        return $queue;
    }

}