<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PreAdmissionExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        $data = [];
        $year = now()->format('y');
        $branch = 'LHR';

        for ($i = 1; $i <= 100; $i++) {
            $refNo = 'CSS-' . $year . '-' . $branch . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $data[] = array_merge([$refNo], array_fill(0, count($this->headings()) - 1, ''));
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Reference No',
            'First Name',
            'Last Name',
            'Age',
            'Email',
            'Phone',
            'Gender',
            'Previously in CSS',
            'Seeking Admission For',
            'Father\'s Name',
            'Mother\'s Name',
            'Father\'s CNIC',
            'Mother\'s CNIC',
            'Student B-Form Number',
            'Present Address',
            'Landline Number',
            'Previous School Attended',
            'Reason Of Switching',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $genderValidation = $this->createDropdownValidation(['Male', 'Female']);
                $cssValidation    = $this->createDropdownValidation(['Yes', 'No']);

                for ($row = 2; $row <= 500; $row++) {
                    $sheet->getCell("G$row")->setDataValidation(clone $genderValidation);
                    $sheet->getCell("H$row")->setDataValidation(clone $cssValidation);
                }
            },
        ];
    }

    private function createDropdownValidation(array $options): DataValidation
    {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setFormula1('"' . implode(',', $options) . '"');
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setShowErrorMessage(true);

        return $validation;
    }
}
