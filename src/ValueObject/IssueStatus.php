<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class IssueStatus
{
    public const string BACKLOG = 'BACKLOG';
    public const string PLANNED = 'PLANNED';
    public const string IN_PROGRESS = 'IN_PROGRESS';
    public const string COMPLETED = 'COMPLETED';
    public const string CANCELED = 'CANCELED';

    public const array VALUES = [
        self::BACKLOG,
        self::PLANNED,
        self::IN_PROGRESS,
        self::COMPLETED,
        self::CANCELED
    ];

    private string $value;

    public function __construct(string $value)
    {
        self::inArray($value);

        $this->value = $value;
    }

    public static function inArray(string $value): void
    {
        Assert::inArray($value, self::VALUES);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }


    /**
     * @return string[]
     */
    public static function getStatusChoices(): array
    {
        return [
            self::BACKLOG => self::BACKLOG,
            self::PLANNED => self::PLANNED,
            self::IN_PROGRESS => self::IN_PROGRESS,
            self::COMPLETED => self::COMPLETED,
            self::CANCELED => self::CANCELED,
        ];
    }
}
