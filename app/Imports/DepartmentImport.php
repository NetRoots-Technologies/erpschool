<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Admin\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Normalize values
                $companyName = trim($row['company'] ?? '');
                $branchName = trim($row['branch'] ?? '');
                $categoryName = trim($row['select_category'] ?? '');
                $deptName = trim($row['name'] ?? '');

                // Fetch related models
                $company = Company::where('name', $companyName)->first();
                $branch = Branch::where('name', $branchName)->first();
                $category = Category::where('name', $categoryName)->first();

                // Validation
                $missing = [];
                if (!$company) $missing[] = 'company';
                if (!$branch) $missing[] = 'branch';
                if (!$category) $missing[] = 'select_category';
                if (empty($deptName)) $missing[] = 'name';

                if (!empty($missing)) {
                    Log::warning('Skipping row. Missing: ' . implode(', ', $missing), $row->toArray());
                    continue;
                }

                // Create department
                Department::create([
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                    'name' => $deptName,
                    'status' => 1,
                ]);

            } catch (\Throwable $e) {
                Log::error('Import Error', [
                    'message' => $e->getMessage(),
                    'row' => $row->toArray(),
                ]);
            }
        }
    }
}
