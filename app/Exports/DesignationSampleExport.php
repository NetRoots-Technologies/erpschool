<?php

namespace App\Exports;

use App\Models\Admin\Department;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class DesignationSampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $departments = Department::pluck('name')->toArray();

        // 1. Main sheet (blank template rows + dropdowns)
        $main = new class($departments) implements FromArray, WithHeadings, WithEvents, WithTitle {
            private array $departments;
            public function __construct(array $departments)
            {
                $this->departments = $departments;
            }

            public function array(): array
            {
                // e.g. 10 blank rows
                return array_fill(0, 10, ['', '']);
            }

            public function headings(): array
            {
                return ['Name', 'Select Department'];
            }

            public function title(): string
            {
                return 'Main';
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet       = $event->sheet->getDelegate();
                        $spreadsheet = $sheet->getParent();
                        $refSheet    = $spreadsheet->getSheetByName('Reference');

                        // Count non‑empty departments
                        $count = count(array_filter($this->departments));

                        // Build the range string for the dropdown
                        $range = "Reference!\$A\$1:\$A\${$count}";

                        // Apply dropdown validation to column B rows 2–100
                        for ($row = 2; $row <= 100; $row++) {
                            $dv = $sheet->getCell("B{$row}")->getDataValidation();
                            $dv->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                               ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                               ->setAllowBlank(true)
                               ->setShowDropDown(true)
                               ->setFormula1("={$range}");
                        }
                    },
                ];
            }
        };

        // 2. Reference sheet (list of departments)
        $ref = new class($departments) implements FromArray, WithTitle {
            private array $departments;
            public function __construct(array $departments)
            {
                $this->departments = $departments;
            }

            public function array(): array
            {
                // Each department in its own row
                return array_map(fn($d) => [$d], $this->departments);
            }

            public function title(): string
            {
                return 'Reference';
            }
        };

        // Return Main first so importers reading sheet #1 see the template
        return [
            $main,
            $ref,
        ];
    }
}

