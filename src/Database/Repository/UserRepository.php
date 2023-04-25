<?php

namespace Database\Repository;


use App\App;
use Database\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function create(array $data): User
    {
        $user = new User();
        $user->setGroup($data['group'])
            ->setName($data['name'])
            ->setTgUsername($data['tg_username'])
            ->setTgId($data['tg_id'])
            ->setRole($data['role']);

        $entityManager = App::entityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }

    public function addGit(User $user, string $git)
    {
        $user->setGit($git);
        App::entityManager()->persist($user);
        App::entityManager()->flush();
    }

    public function getById($id): ?User
    {
        return $this->findOneBy(['tgId' => $id]);
    }

    public function deleteUser(User $user)
    {
        $entityManager = App::entityManager();
        foreach ($user->getQueues() as $queue) {
            $entityManager->remove($queue);
        }
        $entityManager->flush();

        foreach ($user->getReceivedRecommendations() as $recommendation) {
            $entityManager->remove($recommendation);
        }
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
    }

}