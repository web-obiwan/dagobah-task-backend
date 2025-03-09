<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Client;

use App\Domain\Gitlab\Model\Milestone;
use App\Serializer\SerializerRegistry;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;

class GitlabClient
{
    protected SerializerInterface $serializer;

    public function __construct(
        #[Autowire(service: 'app.client.gitlab')]
        private readonly ClientInterface $client,
    ) {
        $this->serializer = SerializerRegistry::getSerializer();
    }

    /**
     * @throws GuzzleException
     */
    public function getVersion(): ResponseInterface
    {
        return $this->client->get('/api/v4/version');
    }

    public function postMilestone(int $projectId, Milestone $milestone): ResponseInterface
    {
        $body = $this->serializer->serialize($milestone, 'json');
        $request = new Request(
            'POST',
            "/api/v4/projects/$projectId/milestones",
            [],
            $body
        );

        return $this->client->sendAsync($request)->wait();
    }
}
