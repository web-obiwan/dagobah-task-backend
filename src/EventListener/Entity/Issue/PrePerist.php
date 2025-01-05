<?php

declare(strict_types=1);

namespace App\EventListener\Entity\Issue;

use App\Entity\Issue;
use App\Repository\IssueRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Exception;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Issue::class)]
readonly class PrePerist
{
    public function __construct(
        private IssueRepository $issueRepository
    ) {
    }


    /**
     * @param Issue $issue
     * @throws Exception
     */
    public function prePersist(Issue $issue): void
    {
        $lastIssue = $this->issueRepository->findOneBy(
            [],
            ['id' => 'DESC']
        );

        if (null === $lastIssue) {
            $issue->setReference($issue->getProject()->getPrefix() . '-1');
        } else {
            $tmp = explode('-', $lastIssue->getReference());
            $issue->setReference(sprintf('%s-%d', $tmp[0], (int) $tmp[1] + 1));
        }
    }
}
