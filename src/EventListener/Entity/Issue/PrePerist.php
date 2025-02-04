<?php

declare(strict_types=1);

namespace App\EventListener\Entity\Issue;

use App\Entity\Issue;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Exception;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Issue::class)]
readonly class PrePerist
{
    /**
     * @param Issue $issue
     * @throws Exception
     */
    public function prePersist(Issue $issue): void
    {
        $project = $issue->getProject();

        if (count($project->getIssues()) === 0) {
            $issue->setReference($issue->getProject()->getPrefix() . '-1');
        } else {
            $lastIssue = $project->getIssues()->last();
            $tmp = explode('-', $lastIssue->getReference());
            $issue->setReference(sprintf('%s-%d', $tmp[0], (int) $tmp[1] + 1));
        }
    }
}
