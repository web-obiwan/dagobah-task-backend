<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name:'label')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['label:read']]
)]
class Label
{
    #[Groups(['label:read'])]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected ?int $id = null;

    #[Groups(['label:read'])]
    #[ORM\Column(length: 100, nullable: false)]
    protected string $name = '';

    #[Groups(['label:read'])]
    #[ORM\Column(length: 7, nullable: false)]
    private string $color = '#000000';

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

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
