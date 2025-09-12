<?php

namespace App\Imports;

use App\Models\Admin\StudentDataBank;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PreAdmissionImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure, WithCustomCsvSettings
{
    use SkipsFailures;

    private $successfulImports = 0;

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ',',
        ];
    }

    public function model(array $row)
    {
        Log::info('Processing row', ['row' => $row]);

        // Ensure required field is present
        if (empty(trim($row['reference_no'] ?? ''))) {
            Log::warning('Skipping row due to missing or empty Reference No', ['row' => $row]);
            return null;
        }

        try {
            $student = new StudentDataBank([
                'reference_no'         => $row['reference_no'],
                'first_name'           => $row['first_name'] ?? null,
                'last_name'            => $row['last_name'] ?? null,
                'student_age'          => isset($row['age']) ? (int) $row['age'] : null,
                'student_email'        => $row['email'] ?? null,
                'student_phone'        => isset($row['phone']) ? (string) $row['phone'] : null,
                'gender'               => $row['gender'] ?? null,
                'study_perviously'     => $row['previously_in_css'] ?? null,
                'admission_for'        => $row['seeking_admission_for'] ?? null,
                'father_name'          => $row['fathers_name'] ?? null,
                'father_cnic'          => $row['fathers_cnic'] ?? null,
                'mother_name'          => $row['mothers_name'] ?? null,
                'mother_cnic'          => $row['mothers_cnic'] ?? null,
                'b_form_no'            => $row['student_b_form_number'] ?? null,
                'present_address'      => $row['present_address'] ?? null,
                'landline_number'      => isset($row['landline_number']) ? (string) $row['landline_number'] : null,
                'previous_school'      => $row['previous_school_attended'] ?? null,
                'reason_of_switch'     => $row['reason_of_switching'] ?? null,
            ]);

            $student->save();
            $this->successfulImports++;
            Log::info('Successfully inserted record', ['reference_no' => $row['reference_no']]);
            return $student;
        } catch (\Exception $e) {
            Log::error('Error inserting StudentDataBank record', [
                'row' => $row,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'reference_no' => 'required|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'age' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => ['nullable', function ($attribute, $value, $fail) {
                if (!is_string($value) && !is_numeric($value)) {
                    $fail("The $attribute must be a string or numeric.");
                }
            }],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'previously_in_css' => ['nullable', Rule::in(['Yes', 'No'])],
            'seeking_admission_for' => 'nullable|string',
            'fathers_name' => 'nullable|string',
            'fathers_cnic' => 'nullable|string',
            'mothers_name' => 'nullable|string',
            'mothers_cnic' => 'nullable|string',
            'student_b_form_number' => 'nullable|string',
            'present_address' => 'nullable|string',
            'landline_number' => ['nullable', function ($attribute, $value, $fail) {
                if (!is_string($value) && !is_numeric($value)) {
                    $fail("The $attribute must be a string or numeric.");
                }
            }],
            'previous_school_attended' => 'nullable|string',
            'reason_of_switching' => 'nullable|string',
        ];
    }

    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('Import failed for row ' . $failure->row() . ': ' . implode(', ', $failure->errors()), [
                'row' => $failure->values(),
            ]);
        }
    }

    public function getSuccessfulImports(): int
    {
        return $this->successfulImports;
    }
}
