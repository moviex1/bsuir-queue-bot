<?php

namespace Database\Entities;


use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'queue')]
class Queue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $place;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "queues")]
    private User $user;

    #[ORM\Column(type: Types::INTEGER)]
    private int $group;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $lesson_date;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $emoji;


    public function __construct()
    {
        $this->emoji = rand(0,112);
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
        return $this->lesson_date;
    }

    /**
     * @param DateTime $lesson_date
     */
    public function setLessonDate(DateTime $lesson_date): void
    {
        $this->lesson_date = $lesson_date;
    }

    /**
     * @return int
     */
    public function getGroup(): int
    {
        return $this->group;
    }

    /**
     * @param int $group
     */
    public function setGroup(int $group): void
    {
        $this->group = $group;
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
    public function setUser(User $user): void
    {
        $this->user = $user;
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
    public function setPlace(int $place): void
    {
        $this->place = $place;
    }
}