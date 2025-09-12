<?php

namespace App\Imports;

use App\Helper\Helpers;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use App\Models\FamilyTreeTable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class StudentExcelImport implements ToCollection, WithHeadingRow
{
    protected $requiredHeaders = [
        'admission_class',
        'branch_name',
        'class_name',
        'student_id',
        'section_name',
        'admission_date',
        'special_child',
        'special_needs',
        'first_name',
        'last_name',
        'father_name',
        'father_cnic',
        'is_guardian',
        'guardian_name',
        'guardian_cnic',
        'student_gender',
        'student_dob',
        'student_current_address',
        'student_permanent_address',
        'student_city',
        'student_country',
        'student_cell_no',
        'student_landline',
        'student_email',
        'native',
        'first',
        'second'
    ];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception('Excel file is empty.');
        }

        $headers = array_keys($rows->first()->toArray());
        $missing = array_diff($this->requiredHeaders, $headers);

        if (count($missing)) {
            throw new \Exception('Missing required columns: ' . implode(', ', $missing));
        }

        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) {
                continue;
            }
            // echo  (isset($row['is_guardian']) && strtolower($row['is_guardian']) === 'yes') ? 1 : 0;
            // dd($row['is_guardian']);
            // dd($row['is_guardian']);
            // dd(trim($row['branch_name']));
            $branchId = Branch::where('name', trim($row['branch_name']))->value('id');
            // dd($branchId);
            $classId = AcademicClass::where('name', $row['class_name'])
                ->whereHas('branch', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->first('id');
            $classId = $classId->id;
            // dd($classId);
            // dd($row['section_name']);
            $sectionId=Section::where('name', $row['section_name'])->whereHas('academicClass',function($query) use($classId)
            {
                $query->where('class_id',$classId);
            })->first('id');
            $sectionId=$sectionId->id;
            // dd($sectionId);
            // echo $classId->id;
            // dd('hello');
            DB::beginTransaction();
            try {
                $guardianCnic = $row['guardian_cnic'] ?? $row['father_cnic'];
                $guardianName = $row['guardian_name'] ?? $row['father_name'];

                // dd($row['admission_date']);
                // dd(Helpers::transformDate($row['admission_date']));
                $student = Students::create([
                    'admission_class' => $row['admission_class'],
                    'branch_name' => $row['branch_name'],
                    'class_id' => $classId,
                    'student_id' => $row['student_id'],
                    'section_id' => $sectionId,
                    'admission_date' => Helpers::transformDate($row['admission_date']),
                    'special_child' => $row['special_child'] ?? 0,
                    'special_needs' => $row['special_needs'] ?? 0,
                    'is_guardian' => (isset($row['is_guardian']) && strtolower(trim($row['is_guardian'])) === 'yes') ? 1 : 0,
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'father_name' => $row['father_name'],
                    'father_cnic' => $row['father_cnic'],
                    'guardian_name' => $guardianName,
                    'guardian_cnic' => $guardianCnic,
                    'gender' => $row['student_gender'],
                    'student_dob' => Helpers::transformDate($row['student_dob']),
                    'student_current_address' => $row['student_current_address'],
                    'student_permanent_address' => $row['student_permanent_address'],
                    'city' => $row['student_city'],
                    'country' => $row['student_country'],
                    'cell_no' => $row['student_cell_no'],
                    'landline' => $row['student_landline'],
                    'student_email' => $row['student_email'],
                    'native_language' => $row['native'],
                    'first_language' => $row['first'],
                    'second_language' => $row['second'],
                ]);

                // Uncomment if you want to maintain family tree logic
                // $existing = FamilyTreeTable::where('cnic_number', $guardianCnic)->first();
                // if ($existing) {
                //     $existing->increment('no_of_children');
                // } else {
                //     FamilyTreeTable::create([
                //         'cnic_number' => $guardianCnic,
                //         'no_of_children' => 1
                //     ]);
                // }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Import error: ' . $e->getMessage());
            }
        }
    }
}
