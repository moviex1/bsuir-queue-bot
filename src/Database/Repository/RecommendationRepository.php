<?php

namespace Database\Repository;

use App\App;
use Database\Entity\Recommendation;
use Doctrine\ORM\EntityRepository;

class RecommendationRepository extends EntityRepository
{
    public function addRecommendation(array $data)
    {
        $recommendation = new Recommendation();
        $recommendation->setRecommendation($data['recommendation'])
            ->setStudent($data['student'])
            ->setTeacher($data['teacher']);
        $entityManager = App::entityManager();
        $entityManager->persist($recommendation);
        $entityManager->flush();
    }

}