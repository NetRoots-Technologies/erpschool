<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class BranchMainSheetExport implements FromArray, WithHeadings, WithEvents
{
    public function array(): array
    {
        return [
            ['Ali Branch', '', '192.168.1.1', '8080', '', 'BR-101', '123 Main St'],
            ['Zeeshan Branch', '', '10.0.0.1', '8000', '', 'BR-102', '456 Canal Rd'],
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Company Name',       // Dropdown
            'IP',
            'Port',
            'School Type',        // Dropdown
            'Student Branch Code',
            'Address',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                for ($row = 2; $row <= 100; $row++) {
                    // Company dropdown in column B
                    $companyValidation = $sheet->getCell("B$row")->getDataValidation();
                    $companyValidation->setType(DataValidation::TYPE_LIST);
                    $companyValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $companyValidation->setAllowBlank(true);
                    $companyValidation->setShowInputMessage(true);
                    $companyValidation->setShowErrorMessage(true);
                    $companyValidation->setShowDropDown(true);
                    $companyValidation->setFormula1("='Reference'!\$A\$2:\$A\$100");

                    // School type dropdown in column E
                    $schoolValidation = $sheet->getCell("E$row")->getDataValidation();
                    $schoolValidation->setType(DataValidation::TYPE_LIST);
                    $schoolValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $schoolValidation->setAllowBlank(true);
                    $schoolValidation->setShowInputMessage(true);
                    $schoolValidation->setShowErrorMessage(true);
                    $schoolValidation->setShowDropDown(true);
                    $schoolValidation->setFormula1("='Reference'!\$D\$2:\$D\$100");
                }
            },
        ];
    }
}


