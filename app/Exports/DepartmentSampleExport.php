<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class DepartmentSampleExport implements FromCollection
{
    public function collection()
    {
        return new Collection([
            ['Select Category', 'Select Company', 'Select Branch', 'Name'],
        ]);
    }
}
