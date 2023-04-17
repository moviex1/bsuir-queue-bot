<?php

namespace Database\Entity;


use Database\Repository\QueueRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QueueRepository::class)]
#[ORM\Table(name: 'queue')]
class Queue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $place;

    #[ORM\Column(name: 'lesson_date')]
    private DateTime $lessonDate;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $emoji;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private User $user;

    public function __construct()
    {
        $this->emoji = rand(0, 112);
    }


    public function getEmoji(): int
    {
        return $this->emoji;
    }

    /**
     * @param int $emoji
     */
    public function setEmoji(int $emoji): void
    {
        $this->emoji = $emoji;
    }

    /**
     * @return DateTime
     */
    public function getLessonDate(): DateTime
    {
        return $this->lessonDate;
    }

    /**
     * @param DateTime $lessonDate
     */
    public function setLessonDate(DateTime $lessonDate): self
    {
        $this->lessonDate = $lessonDate;
        return $this;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace(int $place): self
    {
        $this->place = $place;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}