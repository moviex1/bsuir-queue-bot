<?php

namespace Database\Entities;

use App\Enums\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', name: 'tg_username')]
    private string|null $tgUsername;

    #[ORM\Column(type: Types::BIGINT, name:'tg_id')]
    private int $tgId;

    #[ORM\Column(enumType: Role::class)]
    private Role $role = Role::Student;

    #[ORM\OneToMany(targetEntity: Recommendation::class, mappedBy: 'student', cascade: ['persist'])]
    private Collection $receivedRecommendations;

    #[ORM\OneToMany(targetEntity: Queue::class, mappedBy: 'user')]
    private Collection $queues;

    public function __construct()
    {
        $this->receivedRecommendations = new ArrayCollection();
    }

    public function addRecommendation(Recommendation $recommendation)
    {
        $this->receivedRecommendations[] = $recommendation;
    }

    public function getReceivedRecommendations()
    {
        return $this->receivedRecommendations;
    }


    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getTgId(): int
    {
        return $this->tgId;
    }

    /**
     * @param int $tgId
     */
    public function setTgId(int $tgId): void
    {
        $this->tgId = $tgId;
    }

    /**
     * @return string|null
     */
    public function getTgUsername(): ?string
    {
        return $this->tgUsername;
    }

    /**
     * @param string|null $tgUsername
     */
    public function setTgUsername(?string $tgUsername): void
    {
        $this->tgUsername = $tgUsername;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

}