<?php

namespace App\Imports;

use App\Models\HR\Designation;
use App\Models\Admin\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class DesignationExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::warning('No rows found in the Excel file.');
            throw new \Exception('No valid rows found in the Excel file.');
        }

        $headers = $rows->first() ? $rows->first()->keys()->toArray() : [];
        Log::info('Detected Headers:', $headers);

        $processed = 0;

        foreach ($rows as $index => $row) {
            Log::info("Row $index data:", $row->toArray());

            // Dynamically match headers (normalized to allow multiple formats)
            $nameKey = $this->matchHeader($row, ['name']);
            $deptKey = $this->matchHeader($row, ['select department', 'department', 'select_department']);

            if (!$nameKey || !$deptKey) {
                Log::warning("Row $index skipped: headers not matched");
                continue;
            }

            $name = trim((string) $row[$nameKey]);
            $departmentName = trim((string) $row[$deptKey]);

            if (empty($name) || empty($departmentName)) {
                Log::info("Row $index skipped: missing values", [
                    'name' => $name,
                    'department' => $departmentName
                ]);
                continue;
            }

            // Auto-create department if not found
            $department = Department::firstOrCreate(['name' => $departmentName]);

            try {
                Designation::create([
                    'name' => $name,
                    'department_id' => $department->id,
                ]);

                $processed++;
                Log::info("Row $index inserted: $name → Dept ID {$department->id}");
            } catch (\Exception $e) {
                Log::error("Error on row $index: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Matches actual Excel header keys against allowed normalized variations.
     *
     * @param \Illuminate\Support\Collection $row
     * @param array $expectedKeys
     * @return string|null
     */
    private function matchHeader($row, array $expectedKeys): ?string
    {
        foreach ($row->keys() as $actualKey) {
            $normalizedActual = $this->normalizeHeader($actualKey);
            foreach ($expectedKeys as $expected) {
                if ($normalizedActual === $this->normalizeHeader($expected)) {
                    return $actualKey; // return original for data access
                }
            }
        }
        return null;
    }

    /**
     * Normalizes a string: trims, lowercases, replaces spaces/dashes with underscores.
     *
     * @param string $key
     * @return string
     */
    private function normalizeHeader(string $key): string
    {
        return strtolower(str_replace([' ', '-'], '_', trim($key)));
    }
}
