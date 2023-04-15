<?php

namespace Database\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'recommendations')]
class Recommendation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int|null $id;

    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $teacher;

    #[ORM\JoinColumn(name:"student_id", referencedColumnName:"id")]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedRecommendations')]
    private User $student;

    #[ORM\Column(type: Types::TEXT)]
    private string|null $recommendation;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getTeacher(): User
    {
        return $this->teacher;
    }

    /**
     * @param User $teacher
     */
    public function setTeacher(User $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return User
     */
    public function getStudent(): User
    {
        return $this->student;
    }

    /**
     * @param User $student
     */
    public function setStudent(User $student): void
    {
        $this->student = $student;
    }

    /**
     * @return string|null
     */
    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    /**
     * @param string|null $recommendation
     */
    public function setRecommendation(?string $recommendation): void
    {
        $this->recommendation = $recommendation;
    }

}