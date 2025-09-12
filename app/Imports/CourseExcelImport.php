<?php

namespace App\Imports;

use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Student\AcademicSession;
use App\Models\Admin\CourseType;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Course;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CourseExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::warning('No rows found in the Excel file.');
            throw new \Exception('No valid rows found in the Excel file.');
        }

        Log::info('Total Excel rows received: ' . $rows->count());
        Log::info('Excel Headers:', array_keys($rows->first()->toArray()));

        $processed = 0;

        foreach ($rows as $index => $row) {
            // Skip fully empty row
            if ($row->filter()->isEmpty()) {
                Log::info("Skipped row $index: Entire row is empty.");
                continue;
            }

            Log::debug("Raw row $index data:", $row->toArray());

            // Extract and trim values
            $companyName         = trim((string) ($row['company'] ?? ''));
            $branchName          = trim((string) ($row['branch'] ?? ''));
            $academicSessionName = trim((string) ($row['academic_session'] ?? ''));
            $courseTypeName      = trim((string) ($row['subject_type'] ?? ''));
            $courseName          = trim((string) ($row['subject_name'] ?? ''));
            $subjectCode         = trim((string) ($row['subject_code'] ?? ''));
            $className           = trim((string) ($row['class'] ?? ''));
            $activeSessionName   = trim((string) ($row['active_session'] ?? ''));

            // Log extracted values
            Log::debug("Extracted values from row $index:", [
                'company'          => $companyName,
                'branch'           => $branchName,
                'academic_session' => $academicSessionName,
                'subject_type'     => $courseTypeName,
                'subject_name'     => $courseName,
                'subject_code'     => $subjectCode,
                'class'            => $className,
                'active_session'   => $activeSessionName,
            ]);

            // Required fields check
            if (
                empty($companyName) || empty($branchName) || empty($academicSessionName) ||
                empty($courseTypeName) || empty($courseName) || empty($subjectCode) || empty($className)
            ) {
                Log::info("Skipped row $index: One or more required fields are empty.");
                continue;
            }

            try {
                // Auto-create or find related records
                $company = Company::firstOrCreate(['name' => $companyName]);

                $branch = Branch::firstOrCreate(
                    ['company_id' => $company->id, 'name' => $branchName],
                    ['company_id' => $company->id, 'name' => $branchName]
                );

                $academicSession = AcademicSession::firstOrCreate(['name' => $academicSessionName]);
                $courseType      = CourseType::firstOrCreate(['name' => $courseTypeName]);

                // ✅ Fix: set school_type_id to a default (replace 1 if needed)
                $schoolTypeId = 1;

                $class = AcademicClass::firstOrCreate(
                    [
                        'name'       => $className,
                        'branch_id'  => $branch->id,
                        'company_id' => $company->id,
                        'session_id' => $academicSession->id
                    ],
                    [
                        'school_type_id' => $schoolTypeId
                    ]
                );

                $activeSession = null;
                if (!empty($activeSessionName)) {
                    $activeSession = AcademicSession::firstOrCreate(['name' => $activeSessionName]);
                }

                // Create the course
                $course = Course::create([
                    'name'              => $courseName,
                    'subject_code'      => $subjectCode,
                    'course_type_id'    => $courseType->id,
                    'class_id'          => $class->id,
                    'branch_id'         => $branch->id,
                    'company_id'        => $company->id,
                    'session_id'        => $academicSession->id,
                    'active_session_id' => $activeSession?->id,
                    'status'            => 1
                ]);

                $processed++;
                Log::info("Inserted course in row $index: $courseName (Code: $subjectCode)");
            } catch (\Exception $e) {
                Log::error("Error inserting row $index: " . $e->getMessage(), [
                    'row_data' => $row->toArray()
                ]);
                continue;
            }
        }

        if ($processed === 0) {
            Log::warning('No rows were processed.');
            throw new \Exception('No valid rows were processed. Check Excel format and data.');
        }

        Log::info("Import completed. Total rows processed: $processed");
    }
}
