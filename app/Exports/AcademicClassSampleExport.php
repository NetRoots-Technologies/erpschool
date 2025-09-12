<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AcademicClassSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Company ID',
            'Session ID',
            'Branch ID',
            'Class Name',
            'School Type ID',
        ];
    }

    public function array(): array
    {
        return [
            ['1', '2025', '10', 'Class One', '2'],
            ['1', '2025', '11', 'Class Two', '3'],
        ];
    }
}
