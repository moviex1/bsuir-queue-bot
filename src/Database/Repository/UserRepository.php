<?php

namespace Database\Repository;


use App\App;
use Database\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function create(array $data)
    {
        $user = new User();
        $user->setGroup($data['group']);
        $user->setName($data['name']);
        $user->setTgUsername($data['tg_username']);
        $user->setTgId($data['tg_id']);
        $user->setRole($data['role']);

        $entityManager = App::entityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }

    public function getById($id)
    {
        return $this->findOneBy(['tgId' => $id]);
    }

}