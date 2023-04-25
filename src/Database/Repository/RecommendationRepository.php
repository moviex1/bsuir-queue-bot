<?php

namespace Database\Repository;

use App\App;
use Database\Entity\Recommendation;
use Database\Entity\User;
use Doctrine\ORM\EntityRepository;

class RecommendationRepository extends EntityRepository
{
    public function addRecommendation(array $data) : void
    {
        $recommendation = new Recommendation();
        $recommendation->setRecommendation($data['recommendation'])
            ->setStudent($data['student'])
            ->setTeacher($data['teacher']);
        $entityManager = App::entityManager();
        $entityManager->persist($recommendation);
        $entityManager->flush();
    }

    public function getUserRecommendations(User $user) : array
    {
        return $this->findBy([
            'student' => $user
        ], ['created_at' => 'DESC']);
    }

}