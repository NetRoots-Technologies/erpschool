<?php

namespace App\Exports;

use App\Models\Admin\Company;
use App\Models\Academic\SchoolType;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class BranchSampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $companies = Company::pluck('name')->toArray();
        $schoolTypes = SchoolType::pluck('name')->toArray();

        return [
            'Reference' => new class($companies, $schoolTypes) implements FromArray, WithTitle {
                private $companies;
                private $schoolTypes;

                public function __construct($companies, $schoolTypes)
                {
                    $this->companies = $companies;
                    $this->schoolTypes = $schoolTypes;
                }

                public function array(): array
                {
                    $rows = [];
                    $max = max(count($this->companies), count($this->schoolTypes));
                    for ($i = 0; $i < $max; $i++) {
                        $rows[] = [
                            $this->companies[$i] ?? '',
                            $this->schoolTypes[$i] ?? '',
                        ];
                    }
                    return $rows;
                }

                public function title(): string
                {
                    return 'Reference';
                }
            },

            'Main' => new class($companies, $schoolTypes) implements FromArray, WithHeadings, WithEvents, WithTitle {
                private $companies;
                private $schoolTypes;

                public function __construct($companies, $schoolTypes)
                {
                    $this->companies = $companies;
                    $this->schoolTypes = $schoolTypes;
                }

                public function array(): array
                {
                    // Create 10 blank rows with 7 columns each (Name, Company Name, IP, Port, School Type, Student Branch Code, Address)
                    return array_fill(0, 10, ['', '', '', '', '', '', '']);
                }


                public function headings(): array
                {
                    return [
                        'Name',
                        'Company Name',
                        'IP',
                        'Port',
                        'School Type',
                        'Student Branch Code',
                        'Address',
                    ];
                }

                public function registerEvents(): array
                {
                    return [
                        AfterSheet::class => function (AfterSheet $event) {
                            $sheet = $event->sheet->getDelegate();
                            $spreadsheet = $sheet->getParent();
                            $referenceSheet = $spreadsheet->getSheetByName('Reference');

                            // Dynamically get list lengths
                            $companyCount = count(array_filter($this->companies));
                            $schoolCount = count(array_filter($this->schoolTypes));

                            // Create named ranges for dropdowns
                            $spreadsheet->addNamedRange(new NamedRange(
                                'CompanyList',
                                $referenceSheet,
                                'Reference!$A$1:$A$' . $companyCount
                            ));

                            $spreadsheet->addNamedRange(new NamedRange(
                                'SchoolTypeList',
                                $referenceSheet,
                                'Reference!$B$1:$B$' . $schoolCount
                            ));

                            // Apply dropdown for Company Name (column B)
                            for ($row = 2; $row <= 100; $row++) {
                                $validation = $sheet->getCell("B$row")->getDataValidation();
                                $validation->setType(DataValidation::TYPE_LIST);
                                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                                $validation->setAllowBlank(true);
                                $validation->setShowDropDown(true);
                                $validation->setFormula1('=CompanyList');
                            }

                            // Apply dropdown for School Type (column E)
                            for ($row = 2; $row <= 100; $row++) {
                                $validation = $sheet->getCell("E$row")->getDataValidation();
                                $validation->setType(DataValidation::TYPE_LIST);
                                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                                $validation->setAllowBlank(true);
                                $validation->setShowDropDown(true);
                                $validation->setFormula1('=SchoolTypeList');
                            }
                        }
                    ];
                }

                public function title(): string
                {
                    return 'Main';
                }
            },
        ];
    }
}
