<?php

declare(strict_types=1);

namespace App\Controller\Sprint;

use App\Creator\Pdf\DomPdfCreator;
use App\Creator\Pdf\PdfCreatorInterface;
use App\Entity\Sprint;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GetHtmlPdf extends AbstractController
{
    public function __construct(
        #[Autowire(service: DomPdfCreator::class)]
        private readonly PdfCreatorInterface $pdfCreator
    ) {
    }

    #[Route(
        '/admin/sprint/{id}/pdf',
        name: 'sprint_get_html_pdf',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Sprint $sprint, Request $request): Response
    {
        $export = $request->query->get('export', false);

        if ($export) {
            $this->pdfCreator->loadHtml($this->renderView('Sprint/pdf.html.twig', ['sprint' => $sprint]));
            $this->pdfCreator->setLandscape();
            $this->pdfCreator->stream();
        }

        return $this->render('Sprint/pdf.html.twig', ['sprint' => $sprint]);
    }
}
