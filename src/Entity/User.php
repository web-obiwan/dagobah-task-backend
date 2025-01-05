<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name:'user')]
#[DoctrineAssert\UniqueEntity(["email"])]
#[DoctrineAssert\UniqueEntity(["username"])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['user:read']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[Groups(['user:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;


    #[Groups(['user:read'])]
    #[ORM\Column(length: 100, unique: true, nullable: false)]
    protected string $username = '';

    #[Groups(['user:read'])]
    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private string $email = '';

    #[ORM\Column(nullable: false, options: ['default' => true])]
    protected bool $enabled = true;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'array', nullable: false)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     */
    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create'])]
    protected ?string $plainPassword = null;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'owner')]
    private Collection $ownerIssues;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'reviewer')]
    private Collection $reviewerIssues;

    public function __construct()
    {
        $this->ownerIssues = new ArrayCollection();
        $this->reviewerIssues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * @param string[] $roles
     * @return void
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;
    }

    public function __toString()
    {
        return sprintf('%s [%d]', $this->getUsername(), $this->getId());
    }

    public function addRole(string $role): void
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
