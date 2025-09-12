<?php

namespace App\Imports;

use App\Models\Admin\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanyExcelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Company([
            'name' => $row['company_name'], // column name should match Excel heading
        ]);
    }
}

