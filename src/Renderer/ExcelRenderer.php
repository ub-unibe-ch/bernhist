<?php

namespace App\Renderer;

use App\Entity\Location;
use App\Entity\Topic;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelRenderer extends AbstractRenderer
{
    #[\Override]
    public function render(Location $location, Topic $topic, ?int $yearFrom = null, ?int $yearTo = null): Response
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle('BERNHIST');
        $spreadsheet->getProperties()->setCreator('BERNHIST');
        $spreadsheet->getProperties()->setCreated(date('Y-m-d H:i:s'));

        $currentRow = 1;

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$currentRow, 'Startjahr')
            ->setCellValue('B'.$currentRow, 'Endjahr')
            ->setCellValue('C'.$currentRow, 'Ort')
            ->setCellValue('D'.$currentRow, 'Wert')
            ->setCellValue('E'.$currentRow, 'Thema')
        ;

        ++$currentRow;

        $data = $this->getData($location, $topic, $yearFrom, $yearTo);

        foreach ($data as $dataEntry) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A'.$currentRow, $dataEntry->getYearFrom())
                ->setCellValue('B'.$currentRow, $dataEntry->getYearTo())
                ->setCellValue('C'.$currentRow, $dataEntry->getLocation()?->getName().' ('.$dataEntry->getLocation()?->getType()?->getName().')')
                ->setCellValue('D'.$currentRow, $this->presenter::present($dataEntry->getValue()))
                ->setCellValue('E'.$currentRow, $dataEntry->getTopic()?->getName().' ('.$dataEntry->getTopic()?->getType()?->getName().')')
            ;

            ++$currentRow;
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('BERNHIST');

        $spreadsheet->setActiveSheetIndex(0);

        return $this->createResponse($spreadsheet);
    }

    protected function createResponse(Spreadsheet $spreadsheet): Response
    {
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return $this->write($writer);
    }

    protected function write(IWriter $writer): Response
    {
        return new StreamedResponse(function () use ($writer): void {
            $writer->save('php://output');
        }, Response::HTTP_OK, [
            'Content-Type' => 'application/download',
            'Content-Disposition' => 'Content-Disposition: attachment',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
