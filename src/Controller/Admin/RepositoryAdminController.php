<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Domain\Gitlab\Handler\CreateMilestoneHandler;
use App\Entity\Repository;
use App\Entity\Sprint;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RepositoryAdminController extends CRUDController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CreateMilestoneHandler $createMiletoneHandler)
    {
    }

    /**
     * @param int $id
     * @return Response
     */
    public function createMilestoneAction(int $id): Response
    {
        /** @var Repository $repository */
        $repository = $this->admin->getSubject();

        $sprint = $this->em->getRepository(Sprint::class)->findOneBy([], ['id' => 'DESC']);
        try {
            $this->createMiletoneHandler->process($repository, $sprint);
            $this->addFlash('sonata_flash_success', 'The milestone was successfully created.');
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());
        }



        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
