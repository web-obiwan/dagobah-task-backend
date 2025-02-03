<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name:'repository')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            normalizationContext: ['groups' => ['repository:read']],
            denormalizationContext: ['groups' => ['repository:create']],
        ),
        new Put(
            normalizationContext: ['groups' => ['repository:read']],
            denormalizationContext: ['groups' => ['repository:update']]
        ),
    ],
    normalizationContext: ['groups' => [
        'repository:read',
        'repository:project',
    ]]
)]
class Repository
{
    use TimestampableEntity;

    #[Groups(['repository:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['repository:read', 'repository:create', 'repository:update'])]
    #[ORM\Column(length: 100, nullable: false)]
    protected string $name = '';

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(length: 2000, nullable: true)]
    protected ?string $description;

    #[Groups(['issue:project', 'issue:create'])]
    #[ORM\ManyToOne(targetEntity: Project::class, fetch: 'EAGER', inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Project $project;

    #[Groups(['repository:read'])]
    #[ORM\Column(nullable: true)]
    protected ?int $gitlabId = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getGitlabId(): ?int
    {
        return $this->gitlabId;
    }

    public function setGitlabId(?int $gitlabId): void
    {
        $this->gitlabId = $gitlabId;
    }

    public function __toString(): string
    {
        return sprintf('%s / %s', $this->getProject()->getName(), $this->name);
    }
}
