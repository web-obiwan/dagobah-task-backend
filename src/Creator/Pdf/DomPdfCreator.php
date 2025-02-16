<?php

declare(strict_types=1);

namespace App\Creator\Pdf;

use Dompdf\Dompdf;

readonly class DomPdfCreator implements PdfCreatorInterface
{
    private Dompdf $dompdf;
    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function loadHtml(string $html): void
    {
        $this->dompdf->loadHtml($html);
    }

    public function stream(string $filename = "document.pdf"): void
    {
        $this->dompdf->render();
        $this->dompdf->stream($filename);
    }

    public function setLandscape(): void
    {
        $this->dompdf->setPaper('A4', 'landscape');
    }
}
