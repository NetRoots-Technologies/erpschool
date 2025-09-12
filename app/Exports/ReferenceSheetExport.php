<?php

namespace App\Exports;

use App\Models\Admin\Company;
use App\Models\Academic\SchoolType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReferenceSheetExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        $companies = Company::select('name', 'id')->get();
        $schoolTypes = SchoolType::select('name', 'id')->get();

        $maxRows = max($companies->count(), $schoolTypes->count());

        $data = [];
        for ($i = 0; $i < $maxRows; $i++) {
            $company = $companies[$i] ?? ['name' => '', 'id' => ''];
            $school = $schoolTypes[$i] ?? ['name' => '', 'id' => ''];
            $data[] = [
                $company['name'],
                $company['id'],
                '',
                $school['name'],
                $school['id']
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Company ID',
            '',
            'School Type Name',
            'School Type ID'
        ];
    }

    public function title(): string
    {
        return 'Reference';
    }
}
