<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name:'sprint')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['sprint:read']]
)]
class Sprint
{
    use TimestampableEntity;

    #[Groups(['sprint:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['sprint:read'])]
    #[ORM\Column(length: 100, unique: true, nullable: false)]
    protected string $name = '';

    #[Groups(['sprint:read'])]
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $begunAt;

    #[Groups(['sprint:read'])]
    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $endedAt;


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

    public function __toString(): string
    {
        return $this->name;
    }
}
