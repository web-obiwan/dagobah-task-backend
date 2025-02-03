<?php

declare(strict_types=1);

namespace App\Block\Service;

use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DeadlineBlock extends AbstractBlockService
{
    public function __construct(
        Environment $templating,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct($templating);
    }

    public function getName(): string
    {
        return 'Deadline Issue Block';
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

        $alert = new \DateTime();
        $alert->add(new \DateInterval('P30D'));

        $qb = $this->em->createQueryBuilder();
        $issues = $qb->select('i')
            ->from(Issue::class, 'i')
            ->where($qb->expr()->isNotNull('i.deadline'))
            ->andWhere($qb->expr()->lt('i.deadline', ':limit'))
            ->orderBy('i.deadline', 'ASC')
            ->setParameter('limit', $alert->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        return $this->renderResponse(
            'Admin/Block/deadline.html.twig',
            [
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Deadline',
                'issues' => $issues,
            ],
            $response
        );
    }
}
