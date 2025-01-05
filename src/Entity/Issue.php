<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\IssueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
#[ORM\Table(name:'issue')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            denormalizationContext: ['groups' => ['issue:create']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['issue:update']]
        ),
    ],
    normalizationContext: ['groups' => [
        'issue:read',
        'issue:project',
        'issue:reporter',
        'issue:owner',
        'issue:reviewer',
        'issue:status',
        'issue:priority',
        'issue:labels', 'label:read']
    ]
)]
class Issue
{
    use TimestampableEntity;

    #[Groups(['issue:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(length: 100, nullable: false)]
    protected string $name = '';

    #[Groups(['issue:read'])]
    #[ORM\Column(length: 50, unique: true, nullable: false)]
    protected string $reference = '';

    #[Groups(['issue:read'])]
    #[ORM\Column(nullable: true)]
    protected ?int $storyPoint;

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(length: 2000, nullable: true)]
    protected ?string $description;

    #[Groups(['issue:project', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: Project::class, fetch: 'EAGER', inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Project $project;

    #[Groups(['issue:reporter', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable:true, onDelete:'RESTRICT')]
    protected ?User $reporter = null;

    #[Groups(['issue:owner', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER', inversedBy: 'ownerIssues')]
    #[ORM\JoinColumn(nullable:true, onDelete:'RESTRICT')]
    protected ?User $owner = null;

    #[Groups(['issue:reviewer', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER', inversedBy: 'reviewerIssues')]
    #[ORM\JoinColumn(nullable:true, onDelete:'RESTRICT')]
    protected ?User $reviewer = null;

    #[Groups(['issue:status', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: Status::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Status $status;

    #[Groups(['issue:priority', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: Priority::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Priority $priority;

    /**
     * @var Collection<int, Label>
     */
    #[Groups(['issue:labels', 'issue:create', 'issue:update'])]
    #[ORM\ManyToMany(targetEntity: Label::class)]
    private Collection $labels;

    #[Groups(['issue:read', 'issue:update'])]
    #[ORM\Column(nullable:false, options: ['default' => false])]
    protected bool $isArchived = false;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
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

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getStoryPoint(): ?int
    {
        return $this->storyPoint;
    }

    public function setStoryPoint(?int $storyPoint): void
    {
        $this->storyPoint = $storyPoint;
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

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): void
    {
        $this->reporter = $reporter;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getReviewer(): ?User
    {
        return $this->reviewer;
    }

    public function setReviewer(?User $reviewer): void
    {
        $this->reviewer = $reviewer;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function setPriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    public function addLabel(Label $label): void
    {
        $this->labels[] = $label;
    }

    public function removePlatform(Label $label): void
    {
        $this->labels->removeElement($label);
    }

    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function getIsArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): void
    {
        $this->isArchived = $isArchived;
    }
}
