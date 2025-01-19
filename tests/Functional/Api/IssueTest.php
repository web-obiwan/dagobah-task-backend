<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Issue;

class IssueTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient->loginUser($this->buildUser([]));
    }

    public function testGetCollection(): void
    {
        $response = $this->apiClient->request('GET', '/api/issues');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(
            Issue::class,
            null,
            'jsonld',
            ['groups' => ['issue:read']]
        );
    }

    public function testGet(): void
    {
        $response = $this->apiClient->request('GET', '/api/issues/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Issue::class,
            null,
            'jsonld',
            ['groups' => ['issue:read']]
        );
    }


    public function testCreate(): void
    {
        $response = $this->apiClient->request('POST', '/api/issues', [
            'json' => [
                'name' => 'Issue 1',
                'storyPoint' => 10,
                'description' => null,
                'project' => '/api/projects/1',
                'reporter' => null,
                'owner' => '/api/users/1',
                'reviewer' => '/api/users/2',
                'priority' => '/api/priorities/2',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/issues/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(
            Issue::class,
            null,
            'jsonld',
            ['groups' => ['issue:read']]
        );
    }

    public function testUpdate(): void
    {
        $response = $this->apiClient->request('PUT', '/api/issues/1', [
            'json' => [
                'name' => 'Issue 1',
                'storyPoint' => 10,
                'description' => 'this a big issue',
                'project' => '/api/projects/1',
                'reporter' => null,
                'owner' => '/api/users/1',
                'reviewer' => '/api/users/2',
                'priority' => '/api/priorities/2',
                'isArchived' => false,
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Issue::class,
            null,
            'jsonld',
            ['groups' => ['issue:read']]
        );
    }
}
