<?php

declare(strict_types=1);

namespace App\Traits\Accessor;

trait Title
{
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}