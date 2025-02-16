<?php

declare(strict_types=1);

namespace App\Creator\Pdf;

interface PdfCreatorInterface
{
    public function loadHtml(string $html): void;
    public function stream(string $filename = 'document.pdf'): void;
    public function setLandscape(): void;
}
