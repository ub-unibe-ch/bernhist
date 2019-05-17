<?php

namespace App\Renderer;


use App\Entity\Location;
use App\Entity\Topic;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfRenderer extends ExcelRenderer
{
    protected function createResponse(Spreadsheet $spreadsheet): Response
    {
        IOFactory::registerWriter('Pdf', Tcpdf::class);
        $writer = IOFactory::createWriter($spreadsheet, 'Pdf');
        return $this->write($writer);
    }
}