<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourseSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Company',
            'Academic Session',
            'Branch',
            'Class',
            'Active Session',
            'Subject Type',
            'Subject Name',
            'Subject Code',
        ];
    }

    public function array(): array
    {
        return [
            [
                'CSS',
                '2025-26 Academic Session',
                'Global Campus',
                'Grade 1',
                '2025-26 Academic Session',
                'CS',
                'Data Mining',
                '1092-DM',
            ],
            [
                'CSS',
                '2025-26 Academic Session',
                'Global Campus',
                'Grade 2',
                '2025-26 Academic Session',
                'IT',
                'Artificial Intelligence',
                '2093-AI',
            ],
        ];
    }
}
