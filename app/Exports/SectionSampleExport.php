<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SectionSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Company',
            'Academic Session',
            'Branch',
            'Class',
            'Active Session',
            'Name',
        ];
    }

    public function array(): array
    {
        return [
            ['CSS', '2025-26 Academic Session', 'Global Campus', 'Grade 1', '2025-26 Academic Session', 'Red'],
            ['CSS', '2025-26 Academic Session', 'Global Campus', 'Grade 2', '2025-26 Academic Session', 'Blue'],
        ];
    }
}
