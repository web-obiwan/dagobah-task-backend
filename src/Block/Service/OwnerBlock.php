<?php

declare(strict_types=1);

namespace App\Block\Service;

use App\Entity\Issue;
use App\ValueObject\IssueStatus;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class OwnerBlock extends AbstractBlockService
{
    public function __construct(
        Environment $templating,
        private readonly Security $security,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct($templating);
    }

    public function getName(): string
    {
        return 'Owner Issue Block';
    }


    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null         $response
     * @return Response
     */
    public function execute(
        BlockContextInterface $blockContext,
        Response $response = null
    ): Response {

        $qb = $this->em->createQueryBuilder();
        $issues = $qb->select('i')
            ->from(Issue::class, 'i')
            ->where('i.owner = :owner')
            ->andWhere('i.status IN (:statuses)')
            ->orderBy('i.id', 'ASC')
            ->setParameter('owner', $this->security->getUser())
            ->setParameter('statuses', [IssueStatus::BACKLOG, IssueStatus::IN_PROGRESS])
            ->getQuery()
            ->getResult();

        return $this->renderResponse(
            'Admin/Block/owner.html.twig',
            [
                'block' => $blockContext->getBlock(),
                'title' => 'My issues',
                'issues' => $issues,
            ],
            $response
        );
    }
}
