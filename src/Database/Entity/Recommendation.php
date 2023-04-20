<?php

namespace Database\Entity;

use Database\Repository\RecommendationRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecommendationRepository::class)]
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

    #[ORM\Column]
    private DateTime $created_at;

    public function __construct()
    {
        $this->created_at = new DateTime();
    }

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
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
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
    public function setTeacher(User $teacher): self
    {
        $this->teacher = $teacher;
        return $this;
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
    public function setStudent(User $student): self
    {
        $this->student = $student;
        return $this;
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
    public function setRecommendation(?string $recommendation): self
    {
        $this->recommendation = $recommendation;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

}