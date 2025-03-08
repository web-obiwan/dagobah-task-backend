<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Client;

use App\Serializer\SerializerRegistry;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
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
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getVersion(): ResponseInterface
    {
        return $this->client->get('/api/v4/version');
    }
}
