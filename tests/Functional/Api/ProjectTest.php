<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Project;

class ProjectTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient->loginUser($this->buildUser([]));
    }

    public function testGetCollection(): void
    {
        $response = $this->apiClient->request('GET', '/api/projects');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(
            Project::class,
            null,
            'jsonld',
            ['groups' => ['project:read']]
        );
    }

    public function testGet(): void
    {
        $response = $this->apiClient->request('GET', '/api/projects/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Project::class,
            null,
            'jsonld',
            ['groups' => ['project:read']]
        );
    }


    public function testCreate(): void
    {
        $response = $this->apiClient->request('POST', '/api/projects', [
            'json' => [
                'name' => 'Project Top Secret',
                'prefix' => 'PTS',
                'description' => null,
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/projects/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(
            Project::class,
            null,
            'jsonld',
            ['groups' => ['project:read']]
        );
    }

    public function testUpdate(): void
    {
        $response = $this->apiClient->request('PUT', '/api/projects/1', [
            'json' => [
                'name' => 'Another Project',
                'description' => 'this is a big project',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Project::class,
            null,
            'jsonld',
            ['groups' => ['project:read']]
        );
    }
}
