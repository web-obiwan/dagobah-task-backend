<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Model;

use App\Traits\Accessor;

class Milestone implements \JsonSerializable
{
    use Accessor\Id;
    use Accessor\Title;
    use Accessor\Description;

    private ?string $dueDate;
    private ?string $startDate;

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    public function setDueDate(?string $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function __construct()
    {
    }


    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'due_date' => $this->getDueDate(),
            'start_date' => $this->getStartDate(),
        ];
    }
}
