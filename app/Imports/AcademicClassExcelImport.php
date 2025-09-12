<?php

namespace App\Imports;

use App\Models\Academic\AcademicClass;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Models\Academic\SchoolType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcademicClassExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $company = Company::where('name', trim($row['company']))->first();
                $branch = Branch::where('name', trim($row['branch']))->first();
                $session = AcademicSession::where('name', trim($row['academic_session']))->first();
                $schoolType = SchoolType::where('name', trim($row['school_type']))->first();

                if (!$company || !$branch || !$session || !$schoolType) {
                    Log::warning("Missing reference data at row " . ($index + 2), [
                        'row' => $row->toArray(),
                        'missing' => [
                            'company' => $company ? 'OK' : 'Not Found',
                            'branch' => $branch ? 'OK' : 'Not Found',
                            'session' => $session ? 'OK' : 'Not Found',
                            'school_type' => $schoolType ? 'OK' : 'Not Found',
                        ]
                    ]);
                    continue; // skip this row
                }

                AcademicClass::create([
                    'company_id'     => $company->id,
                    'branch_id'      => $branch->id,
                    'session_id'     => $session->id,
                    'school_type_id' => $schoolType->id,
                    'name'           => trim($row['name']),
                    'status'         => 1,
                ]);
            } catch (\Throwable $e) {
                Log::error('AcademicClass Import Error at row ' . ($index + 2), [
                    'message' => $e->getMessage(),
                    'row'     => $row->toArray(),
                ]);
            }
        }
    }
}
