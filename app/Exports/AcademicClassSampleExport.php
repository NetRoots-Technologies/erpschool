<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AcademicClassSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Company',
            'Academic Session',
            'Branch',
            'Name',
            'School Type',
        ];
    }

    public function array(): array
    {
        return [
            ['CSS', '2025-26 Academic Session', 'Global Campus', 'Grade 1', 'Pre school'],
            ['CSS', '2025-26 Academic Session', 'Global Campus', 'Grade 2', 'Primary'],
        ];
    }
}
