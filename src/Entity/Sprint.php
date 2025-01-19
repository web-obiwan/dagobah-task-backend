<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
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
#[ORM\Table(name:'sprint')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            denormalizationContext: ['groups' => ['sprint:create']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['sprint:update']]
        ),
    ],
    normalizationContext: ['groups' => ['sprint:read']]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'createdAt' => 'ASC',
        'beginAt' => 'ASC',
    ]
)]
class Sprint
{
    use TimestampableEntity;

    #[Groups(['sprint:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    #[ORM\Column(length: 100, unique: true, nullable: false)]
    protected string $name = '';

    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $begunAt;

    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $endedAt;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'sprint')]
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

    public function getBegunAt(): DateTime
    {
        return $this->begunAt;
    }

    public function setBegunAt(DateTime $begunAt): void
    {
        $this->begunAt = $begunAt;
    }

    public function getEndedAt(): DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(DateTime $endedAt): void
    {
        $this->endedAt = $endedAt;
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
