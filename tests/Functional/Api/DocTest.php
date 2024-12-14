<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class DocTest extends ApiTestCase
{
    public function testDocSuccessfull(): void
    {
        $response = static::createClient()->request('GET', '/api/docs');

        $this->assertResponseIsSuccessful();
    }
}
