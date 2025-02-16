<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\GroupFilter;
use App\Repository\IssueRepository;
use App\ValueObject\IssueStatus;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
#[ORM\Table(name:'issue')]
#[ORM\Index(name: 'idx_issue_name', columns: ['name'])]
#[ORM\Index(name: 'idx_issue_status', columns: ['status'])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            normalizationContext: ['groups' => ['issue:read', 'issue:labels']],
            denormalizationContext: ['groups' => ['issue:create']],
        ),
        new Put(
            normalizationContext: ['groups' => ['issue:read', 'issue:labels']],
            denormalizationContext: ['groups' => ['issue:update']]
        ),
    ],
    normalizationContext: ['groups' => [
        'issue:read',
        'issue:project',
        'project:read',
        'issue:sprint',
        'sprint:read',
        'issue:reporter',
        'user:read',
        'issue:owner',
        'issue:reviewer',
        'issue:status',
        'issue:priority',
        'priority:read',
        'issue:labels',
        'label:read',
        'issue:repository',
        'repository:read']
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id' => 'exact',
        'project' => 'exact',
        'sprint' => 'exact',
        'reference' => 'exact',
        'reporter' => 'exact',
        'owner' => 'exact',
        'reviewer' => 'exact',
        'status' => 'exact',
        'priority' => 'exact',
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'createdAt' => 'ASC',
        'priority.sort' => 'ASC',
        'reference' => 'ASC',
    ]
)]
#[ApiFilter(
    GroupFilter::class,
    arguments: [
        'parameterName' => 'groups',
        'overrideDefaultGroups' => true,
        'whitelist' => [
            'issue:read',
            'issue:labels',
        ]
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

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(nullable: true)]
    protected ?int $storyPoint;

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $description;

    #[Groups(['issue:project', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: Project::class, fetch: 'EAGER', inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable:false, onDelete:'RESTRICT')]
    protected Project $project;

    /**
     * @var Collection<int, Repository>
     */
    #[Groups(['issue:repository', 'issue:create', 'issue:update'])]
    #[ORM\ManyToMany(targetEntity: Repository::class)]
    private Collection $repositories;

    #[Groups(['issue:project', 'issue:create', 'issue:update'])]
    #[ORM\ManyToOne(targetEntity: Sprint::class, fetch: 'EAGER', inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable:true, onDelete:'RESTRICT')]
    protected ?Sprint $sprint;

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

    #[Groups(['issue:status', 'issue:update'])]
    #[ORM\Column(length: 20, nullable: false)]
    protected string $status = IssueStatus::BACKLOG;

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

    #[Groups(['issue:read', 'issue:create', 'issue:update'])]
    #[ORM\Column(type:'date', nullable: true)]
    private ?DateTime $deadline = null;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->repositories = new ArrayCollection();
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

    public function getSprint(): ?Sprint
    {
        return $this->sprint;
    }

    public function setSprint(?Sprint $sprint): void
    {
        $this->sprint = $sprint;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $value = new IssueStatus($status);
        $this->status = $value->getValue();
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

    public function removeLabel(Label $label): void
    {
        $this->labels->removeElement($label);
    }

    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function getDeadline(): ?DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?DateTime $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
