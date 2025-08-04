<?php

namespace App\Renderer;

use Override;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use Symfony\Component\HttpFoundation\Response;

class PdfRenderer extends ExcelRenderer
{
    #[Override]
    protected function createResponse(Spreadsheet $spreadsheet): Response
    {
        IOFactory::registerWriter('Pdf', Tcpdf::class);
        $writer = IOFactory::createWriter($spreadsheet, 'Pdf');

        return $this->write($writer);
    }
}
