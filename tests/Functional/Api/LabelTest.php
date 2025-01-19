<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Label;

class LabelTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient->loginUser($this->buildUser([]));
    }

    public function testGetCollection(): void
    {
        $response = $this->apiClient->request('GET', '/api/labels');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(
            Label::class,
            null,
            'jsonld',
            ['groups' => ['label:read']]
        );
    }

    public function testGet(): void
    {
        $response = $this->apiClient->request('GET', '/api/labels/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Label::class,
            null,
            'jsonld',
            ['groups' => ['label:read']]
        );
    }


    public function testCreate(): void
    {
        $response = $this->apiClient->request('POST', '/api/projects', [
            'json' => [
                'name' => 'Documentation',
                'color' => '#0000FF',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/projects/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(
            Label::class,
            null,
            'jsonld',
            ['groups' => ['label:read']]
        );
    }

    public function testUpdate(): void
    {
        $response = $this->apiClient->request('PUT', '/api/labels/1', [
            'json' => [
                'name' => 'Super Feature',
                'color' => '#000000',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(
            Label::class,
            null,
            'jsonld',
            ['groups' => ['label:read']]
        );
    }
}
