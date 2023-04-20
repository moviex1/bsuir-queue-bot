<?php

namespace Database\Entity;

use App\Enums\Role;
use Database\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
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

    #[ORM\Column(type: Types::BIGINT, name: 'tg_id')]
    private int $tgId;

    #[ORM\Column(name: '`group`', type: Types::INTEGER)]
    private int $group;

    #[ORM\Column(name: 'git', type: Types::STRING)]
    private string|null $git = null;

    #[ORM\Column(enumType: Role::class)]
    private Role $role = Role::Student;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Queue::class, cascade: ['persist', 'remove'])]
    private Collection $queues;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Recommendation::class, cascade: ['persist', 'remove'])]
    private Collection $receivedRecommendations;


    public function __construct()
    {
        $this->receivedRecommendations = new ArrayCollection();
    }

    public function addRecommendation(Recommendation $recommendation) : self
    {
        $this->receivedRecommendations[] = $recommendation;
        return $this;
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

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
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
    public function setTgId(int $tgId): self
    {
        $this->tgId = $tgId;
        return $this;
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
    public function setTgUsername(?string $tgUsername): self
    {
        $this->tgUsername = $tgUsername;
        return $this;
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
    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
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
    public function setGroup(int $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getQueues(): Collection
    {
        return $this->queues;
    }

    /**
     * @param Collection $queues
     */
    public function setQueues(Collection $queues): self
    {
        $this->queues = $queues;
        return $this;
    }

    /**
     * @return string
     */
    public function getGit(): string
    {
        return $this->git;
    }

    /**
     * @param string $git
     */
    public function setGit(string $git): self
    {
        $this->git = $git;

        return $this;
    }

}