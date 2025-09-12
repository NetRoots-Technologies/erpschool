<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class AcademicSessionSampleExport implements WithHeadings, WithEvents, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'Name',
            'Start Date',
            'End Date'
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:C1')->getFont()->setBold(true);

                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getCell("B{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DATE);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);

                    $endValidation = $sheet->getCell("C{$row}")->getDataValidation();
                    $endValidation->setType(DataValidation::TYPE_DATE);
                    $endValidation->setAllowBlank(true);
                    $endValidation->setShowDropDown(true);
                }
            },
        ];
    }



}
