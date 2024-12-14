<?php

declare(strict_types=1);

namespace App\Tests\Builder;

interface BuilderInterface
{
    public function build(): object;
}
