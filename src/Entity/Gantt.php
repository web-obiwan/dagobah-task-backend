<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name:'gantt')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            normalizationContext: ['groups' => ['gantt:read']],
            denormalizationContext: ['groups' => ['issue:create']],
        ),
        new Put(
            normalizationContext: ['groups' => ['gantt:read']],
            denormalizationContext: ['groups' => ['gantt:update']]
        ),
    ],
    normalizationContext: ['groups' => [
        'gantt:read',
        ]
    ]
)]
class Gantt
{
    use TimestampableEntity;

    #[Groups(['gantt:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['gantt:read', 'gantt:create', 'gantt:update'])]
    #[ORM\Column(nullable: false)]
    private string $name;

    #[Groups(['gantt:project', 'gantt:create', 'gantt:update'])]
    #[ORM\ManyToOne(targetEntity: Project::class, fetch: 'EAGER', inversedBy: 'gantts')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Project $project;

    /**
     * @var Collection<int, Repository>
     */
    #[Groups(['gantt:repository', 'gantt:create', 'gantt:update'])]
    #[ORM\ManyToMany(targetEntity: Repository::class)]
    private Collection $repositories;

    #[Groups(['gantt:read', 'gantt:create', 'gantt:update'])]
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $begunAt;

    #[Groups(['gantt:read', 'gantt:create', 'gantt:update'])]
    #[ORM\Column(nullable: false)]
    private int $progress = 0;

    #[Groups(['gantt:read', 'gantt:create', 'gantt:update'])]
    #[ORM\Column(nullable: false)]
    private int $duration = 0;

    #[Groups(['gantt:project', 'gantt:create', 'gantt:update'])]
    #[ORM\ManyToOne(targetEntity: Gantt::class, fetch: 'EAGER', inversedBy: 'childs')]
    #[ORM\JoinColumn(nullable:true, onDelete:'RESTRICT')]
    protected ?Gantt $parent;

    /**
     * @var Collection<int, Gantt>
     */
    #[Groups(['gantt:read'])]
    #[ORM\OneToMany(targetEntity: Gantt::class, mappedBy: 'parent')]
    private Collection $childs;

    public function __construct()
    {
        $this->repositories = new ArrayCollection();
        $this->childs = new ArrayCollection();
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

    /**
     * @return Collection<int, Gantt>
     */
    public function getChilds(): Collection
    {
        return $this->childs;
    }

    public function getParent(): ?Gantt
    {
        return $this->parent;
    }

    public function setParent(?Gantt $parent): void
    {
        $this->parent = $parent;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): void
    {
        $this->progress = $progress;
    }

    public function getBegunAt(): DateTime
    {
        return $this->begunAt;
    }

    public function setBegunAt(DateTime $begunAt): void
    {
        $this->begunAt = $begunAt;
    }

    public function getRepositories(): Collection
    {
        return $this->repositories;
    }

    public function addRepository(Repository $repository): void
    {
        $this->repositories[] = $repository;
    }

    public function removeRepository(Repository $repository): void
    {
        $this->repositories->removeElement($repository);
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }


    public function __toString(): string
    {
        return $this->name;
    }
}
