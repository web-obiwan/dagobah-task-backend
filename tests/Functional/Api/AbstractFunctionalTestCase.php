<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Builder\UserBuilder;

class AbstractFunctionalTestCase extends ApiTestCase
{
    protected Client $apiClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient = static::createClient();
    }


    /**
     * @param array<string> $roles
     * @return User
     */
    protected function buildUser(array $roles): User
    {
        $builder = new UserBuilder();
        $builder->withRoles($roles);
        return $builder->build();
    }
}
