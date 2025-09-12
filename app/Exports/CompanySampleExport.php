<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class CompanySampleExport implements FromCollection
{
    public function collection()
    {
        // Provide a sample row with only the company name column
        return new Collection([
            ['Company Name'],
        ]);
    }
}
