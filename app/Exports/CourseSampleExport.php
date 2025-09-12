<?php

namespace App\Exports;

use App\Models\Admin\Department;
use App\Models\Admin\CourseType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class CourseSampleExport implements FromArray, WithHeadings, WithEvents
{
    public function array(): array
    {
        return [
            [
                'Company 1', 'Main Branch', '2024-25',
                'Academics', 'Physics', 'PHY-101', 'Class 9', 'Yes'
            ],
            [
                'Company 2', 'Canal Branch', '2024-25',
                'Administration', 'Accounts', 'ACC-201', 'Class 10', 'No'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'company',
            'branch name',
            'academic session',
            'subject type',      // dropdown from CourseType (for example)
            'subject name',
            'subject code',
            'class',
            'active session',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Example: Dropdown for "subject type" column (D)
                $subjectTypes = CourseType::pluck('name')->toArray();
                $dropdownList = implode(',', $subjectTypes);

                for ($row = 2; $row <= 100; $row++) {
                    $validation = $sheet->getCell("D$row")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"' . $dropdownList . '"');
                }
            },
        ];
    }
}
