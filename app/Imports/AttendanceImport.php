<?php

namespace App\Imports;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Academic\StudentAttendance;
use App\Models\Academic\StudentAttendanceData;
use App\Models\Admin\Branch;
use App\Models\Student\Students;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class AttendanceImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $firstRow = true;
        $columnNames = [];
        $data = [];
        $errors = [];

        foreach ($rows as $row_key => $row) {
            if ($this->isRowNull($row)) {
                continue;
            }
            if ($firstRow) {
                $columnNames = array_values(array_filter(array_map('trim', $row->toArray())));
                $columnCount = count($columnNames);
                $firstRow = false;
                continue;
            }

            $values = array_values(array_filter(array_map("trim", $row->toArray())));
            if (count($values) !== $columnCount) {
                continue;
            }

            $row_data = [];

            foreach ($columnNames as $key => $item) {
                $row_data[$item] = $values[$key];
            }

            $data[$row_key] = $row_data;
        }

        foreach ($data as $item) {
            $classId = AcademicClass::where('name', $item['Class'])->value('id') ?? null;
            $sectionId = Section::where('name', $item['Section'])->value('id') ?? null;
            $branchId = Branch::where('name', $item['Campus'])->value('id') ?? null;

            if (is_null($classId) || is_null($sectionId) || is_null($branchId)) {
                if (is_null($classId)) {
                    $errors[] = "Class '{$item['Class']}' not found.";
                }
                if (is_null($sectionId)) {
                    $errors[] = "Section '{$item['Section']}' not found.";
                }
                if (is_null($branchId)) {
                    $errors[] = "Branch '{$item['Campus']}' not found.";
                }
                continue;
            }

            $dateString = $item['Date'];

            if (!empty($dateString)) {
                if (is_numeric($dateString)) {
                    $dateString = Date::excelToDateTimeObject($dateString)->format('Y-m-d');
                } else {
                    try {
                        $dateString = Carbon::parse($dateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        dd("Date Parsing Error:", $dateString, $e->getMessage());
                    }
                }
            } else {
                dd("Empty Date Found:", $item);
            }

            $studentName = explode(' ', $item['Student Name']);
            $firstName = $studentName[0];
            $lastName = isset($studentName[1]) ? $studentName[1] : '';

            $studentId = Students::where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->value('id') ?? null;

            if (is_null($studentId)) {
                $errors[] = "Student '{$item['Student Name']}' not found.";
                continue;
            }

            $attendance = StudentAttendance::firstOrCreate(
                [
                    'branch_id' => $branchId,
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'attendance_date' => $dateString,
                ]
            );

            $existingStudentAttendance = StudentAttendanceData::where('student_id', $studentId)
                ->where('student_attendance_id', $attendance->id)
                ->exists();

            if (!$existingStudentAttendance) {
                StudentAttendanceData::create([
                    'student_id' => $studentId,
                    'attendance' => $item['Attendance'],
                    'student_attendance_id' => $attendance->id,
                ]);
            } else {
                $errors[] = "Attendance for '{$item['Student Name']}' on '{$dateString}' already exists.";
            }
        }

        if (!empty($errors)) {
            session()->flash('import_errors', $errors);
        }
    }

    private function isRowNull($row)
    {
        foreach ($row as $value) {
            if ($value !== null) {
                return false;
            }
        }
        return true;
    }
}
