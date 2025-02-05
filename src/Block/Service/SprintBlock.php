<?php

declare(strict_types=1);

namespace App\Block\Service;

use App\Entity\Issue;
use App\Entity\Sprint;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class SprintBlock extends AbstractBlockService
{
    public function __construct(
        Environment $templating,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct($templating);
    }

    public function getName(): string
    {
        return 'Sprint Block';
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
        $settings = $blockContext->getSettings();

        $qb = $this->em->createQueryBuilder();
        $sprints = $qb->select('s.id, s.name, s.begunAt, s.endedAt')
            ->from(Sprint::class, 's')
            ->leftJoin('s.issues', 'i')
            ->addSelect('COUNT(i.id) as nbIssue')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(4)
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        return $this->renderResponse(
            'Admin/Block/sprint.html.twig',
            [
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Deadline',
                'sprints' => $sprints,
            ],
            $response
        );
    }
}
