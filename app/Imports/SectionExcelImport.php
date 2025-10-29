<?php

namespace App\Imports;

use App\Models\Academic\Section;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\AcademicSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SectionExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Log::info('Importing row:', $row->toArray());

            try {
                $company        = Company::where('name', $row['company'])->first();
                $branch         = Branch::where('name', $row['branch'])->first();
                $session        = AcademicSession::where('name', $row['academic_session'])->first();
                $activeSession  = AcademicSession::where('name', $row['active_session'])->first();
                $academicClass  = AcademicClass::where('name', $row['class'])->first();

                Section::firstOrCreate(
                    [
                        'class_id' => $academicClass->id,
                        'name'     => $row['name'],
                    ],
                    [
                        'company_id'        => 1,
                        'branch_id'         => 1,
                        'session_id'        => 1,
                        'active_session_id' => 1,
                        'status'            => 1,
                    ]
                );

            } catch (\Throwable $e) {
                Log::error('Import Error', [
                    'message' => $e->getMessage(),
                    'row'     => $row->toArray(),
                ]);
            }
        }
    }
}
