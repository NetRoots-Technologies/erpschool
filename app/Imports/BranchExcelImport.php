<?php

namespace App\Imports;

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BranchExcelImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {

        if (
            empty($row['name']) ||
            empty($row['company_name']) ||
            empty($row['ip']) ||
            empty($row['port']) ||
            empty($row['student_branch_code']) ||
            empty($row['address'])
        ) {
            
            return null;
        }

        $companyId = Company::where('name', $row['company_name'] ?? '')->value('id');

        return new Branch([
            'name'            => $row['name'] ?? 'Unnamed',
            'company_id'      => $companyId,
            'ip_config'       => $row['ip'] ?? null,
            'port'            => $row['port'] ?? null,
            'address'         => $row['address'] ?? 'No address',
            'branch_code'     => $row['student_branch_code'] ?? 'BR-X',
            'emp_branch_code' => $row['student_branch_code'] ?? 'BR-X',
        ]);
    }
}
