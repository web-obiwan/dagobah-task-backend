<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Handler;

use App\Domain\Gitlab\Contracts\ExternalReferenceInterface;
use App\Entity\Project;
use App\Entity\Sprint;
use Gitlab\Client;
use Magicbart\ExternalReferenceBundle\Exception\NotFoundExternalReferenceException;
use Magicbart\ExternalReferenceBundle\Manager\ExternalReferenceManager;

readonly class CreateMiletoneHandler implements ExternalReferenceInterface
{
    public function __construct(
        private Client $client,
        private ExternalReferenceManager $externalReferenceManager,
    ) {
    }

    /**
     * @param Project $project
     * @param Sprint $sprint
     * @return bool
     * @throws NotFoundExternalReferenceException
     */
    public function process(Project $project, Sprint $sprint): bool
    {
        $this->client->milestones()->create(
            (int)$this->externalReferenceManager->getExternalId(
                Project::class,
                $project->getId(),
                self::GITLAB
            ),
            [
            'title' => $project->getName() . ' - ' . $sprint->getName(),
            'description' => '',
            'start_date' => $sprint->getBegunAt()->format('Y-m-d'),
            'due_date' => $sprint->getEndedAt()->format('Y-m-d'),
            ]
        );

        return true;
    }
}
