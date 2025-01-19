<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Sprint;

class SprintTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient->loginUser($this->buildUser([]));
    }

    public function testGetCollection(): void
    {
        $response = $this->apiClient->request('GET', '/api/sprints');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(
            Sprint::class,
            null,
            'jsonld',
            ['groups' => ['sprint:read']]
        );
    }

    public function testGet(): void
    {
        $response = $this->apiClient->request('GET', '/api/sprints/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Sprint::class,
            null,
            'jsonld',
            ['groups' => ['sprint:read']]
        );
    }


    public function testCreate(): void
    {
        $response = $this->apiClient->request('POST', '/api/sprints', [
            'json' => [
                'name' => 'Sprint Jedi',
                'begunAt' => '2025-01-01',
                'endedAt' => '2025-01-07',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/sprints/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(
            Sprint::class,
            null,
            'jsonld',
            ['groups' => ['sprint:read']]
        );
    }

    public function testUpdate(): void
    {
        $response = $this->apiClient->request('PUT', '/api/sprints/1', [
            'json' => [
                'name' => 'Sprint Jedi',
                'begunAt' => '2025-01-01',
                'endedAt' => '2025-01-07',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Sprint::class,
            null,
            'jsonld',
            ['groups' => ['sprint:read']]
        );
    }
}
