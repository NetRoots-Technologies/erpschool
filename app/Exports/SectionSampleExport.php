<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SectionSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Company ID',
            'Session ID',
            'Branch ID',
            'Class ID',
            'Active Session ID',
            'Section Name',
        ];
    }

    public function array(): array
    {
        return [
            [1, 2025, 10, 3, 2025, 'Section A'],
            [1, 2025, 11, 4, 2025, 'Section B'],
        ];
    }
}
