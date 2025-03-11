<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Handler;

use App\Domain\Gitlab\Contracts\ExternalReferenceInterface;
use App\Entity\Repository;
use App\Entity\Sprint;
use Exception;
use Gitlab\Client;
use Magicbart\ExternalReferenceBundle\Exception\NotFoundExternalReferenceException;
use Magicbart\ExternalReferenceBundle\Manager\ExternalReferenceManager;

readonly class CreateMilestoneHandler implements ExternalReferenceInterface
{
    public function __construct(
        private Client $client,
        private ExternalReferenceManager $externalReferenceManager,
    ) {
    }

    /**
     * @param Repository $repository
     * @param Sprint $sprint
     * @return bool
     * @throws NotFoundExternalReferenceException
     * @throws Exception
     */
    public function process(Repository $repository, Sprint $sprint): bool
    {
        $target = 'gitab.repository.' . $repository->getId();

        if ($this->externalReferenceManager->exists(
            Sprint::class, $sprint->getId(), $target)) {
            throw new Exception('Milestone already exists.');
        }

        $response = $this->client->milestones()->create(
            (int)$this->externalReferenceManager->getExternalId(
                Repository::class,
                $repository->getId(),
                self::GITLAB
            ),
            [
            'title' => $repository->getProject()->getName() . ' - ' . $sprint->getName(),
            'description' => '',
            'start_date' => $sprint->getBegunAt()->format('Y-m-d'),
            'due_date' => $sprint->getEndedAt()->format('Y-m-d'),
            ]
        );

        $this->externalReferenceManager->add(
            Sprint::class,
            $sprint->getId(),
            $response['id'],
            'gitab.repository.' . $repository->getId(),
        );

        return true;
    }
}
