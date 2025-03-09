<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Handler;

use App\Domain\Gitlab\Client\GitlabClient;
use App\Domain\Gitlab\Contracts\ExternalReferenceInterface;
use App\Domain\Gitlab\DataTransformer\SprintToMilestoneTransformer;
use App\Entity\Project;
use App\Entity\Sprint;
use Magicbart\ExternalReferenceBundle\Exception\NotFoundExternalReferenceException;
use Magicbart\ExternalReferenceBundle\Manager\ExternalReferenceManager;

readonly class CreateMiletoneHandler implements ExternalReferenceInterface
{
    public function __construct(
        private GitlabClient $client,
        private SprintToMilestoneTransformer $transformer,
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
        $milestone = $this->transformer->transform($sprint);
        $milestone->setTitle($project->getName() . ' - ' . $milestone->getTitle());

        $this->client->postMilestone(
            (int)$this->externalReferenceManager->getExternalId(
                Project::class,
                $project->getId(),
                self::GITLAB
            ),
            $milestone);

        return true;
    }
}
