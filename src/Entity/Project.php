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
use Symfony\Component\Serializer\Annotation\Groups;
use VideoGamesRecords\CoreBundle\Entity\Group;

#[ORM\Entity]
#[ORM\Table(name:'project')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['project:read']]
)]
class Project
{
    use TimestampableEntity;

    #[Groups(['project:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['project:read'])]
    #[ORM\Column(length: 100, unique: true, nullable: false)]
    protected string $name = '';

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(length: 100, nullable: false)]
    protected string $prefix = '';

    #[Groups(['project:read'])]
    #[ORM\Column(length: 2000, nullable: true)]
    protected ?string $description;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'project')]
    private Collection $issues;


    public function __construct()
    {
        $this->issues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
