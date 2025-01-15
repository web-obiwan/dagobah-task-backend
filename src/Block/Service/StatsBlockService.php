<?php

declare(strict_types=1);

namespace App\Block\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class StatsBlockService extends AbstractBlockService
{
    private EntityManagerInterface $entityManager;

    public function __construct(Environment $templating, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($templating);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Stats Block';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'entity' => 'Add Entity',
                'repository_method' => 'findAll',
                'title' => 'Insert block Title',
                'url' => '/admin/',
                'css_class' => 'bg-blue',
                'icon' => 'fa-users',
                'template' => 'Admin/Block/stats.html.twig',
            ]
        );
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
        $entity = $settings['entity'];
        $url = $settings['url'];
        $method = $settings['repository_method'];
        $count = $this->entityManager->getRepository($entity)->$method();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            [
                'count' => $count,
                'url' => $url,
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
            ],
            $response
        );
    }
}
