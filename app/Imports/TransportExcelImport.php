<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Student\Students;
use App\Models\Fleet\Vehicle;
use App\Models\Fleet\Route;
use App\Models\Fleet\Transportation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransportExcelImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    protected function getCell($row, array $keys)
    {
        foreach ($keys as $k) {
            if (isset($row[$k])) return $row[$k];
        }

        foreach ($keys as $k) {
            $k2 = str_replace(' ', '_', $k);
            if (isset($row[$k2])) return $row[$k2];
        }

        foreach ($keys as $k) {
            $k3 = strtolower(trim($k));
            if (isset($row[$k3])) return $row[$k3];
        }

        return null;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $sheetRowNumber = $index + 2; // header row = 1

            // Normalize values (use getCell if you expect variable headings)
            $studentIdRaw  = $row['student_id'] ?? $this->getCell($row, ['student_id', 'Student ID', 'student id']);
            $vehicleNumber = $row['vehicle_number'] ?? $this->getCell($row, ['vehicle_number', 'vehicle no', 'vehicle']);
            $routeName     = $row['route_name'] ?? $this->getCell($row, ['route_name', 'route']);
            $pickupPoint   = $row['pickup_point'] ?? $this->getCell($row, ['pickup_point', 'pickup']);
            $dropPoint     = $row['dropoff_point'] ?? $this->getCell($row, ['dropoff_point', 'dropoff', 'drop_off']);
            $monthlyCharge = is_numeric($row['monthly_charges'] ?? null) ? (float) ($row['monthly_charges']) : 0.0;
            $status        = strtolower(trim($row['status'] ?? 'active')) === 'active' ? 'active' : 'inactive';
            $notes         = $row['notes'] ?? null;

            // Use transaction per-row to avoid partial multi-row commit issues
            DB::beginTransaction();
            try {
                // vehicle
                $vehicle = Vehicle::where('vehicle_number', $vehicleNumber)->first();
                if (!$vehicle) {
                    throw new \Exception("Vehicle '{$vehicleNumber}' not found (row {$sheetRowNumber}).");
                }

                // route
                $route = Route::where('route_name', $routeName)->first();
                if (!$route) {
                    throw new \Exception("Route '{$routeName}' not found (row {$sheetRowNumber}).");
                }

                // student
                $student = Students::where('student_id', $studentIdRaw)->first();
                if (!$student) {
                    throw new \Exception("Student '{$studentIdRaw}' not found (row {$sheetRowNumber}).");
                }

                Transportation::create([
                    'student_id'      => $student->id,
                    'vehicle_id'      => $vehicle->id,
                    'route_id'        => $route->id,
                    'pickup_point'    => $pickupPoint,
                    'dropoff_point'   => $dropPoint,
                    'monthly_charges' => $monthlyCharge,
                    'status'          => $status,
                    'notes'           => $notes,
                    'company_id'      => auth()->user()->company_id ?? 1,
                    'branch_id'       => auth()->user()->branch_id ?? 1,
                    'start_date'      => Carbon::now(),
                ]);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();

                // Collect the error and continue with next row
                $this->errors[] = "Row {$sheetRowNumber}: " . $e->getMessage();
                // optionally log
                Log::warning("Import error on row {$sheetRowNumber}: " . $e->getMessage());
                // continue to next row
                continue;
            }
        }

        // After processing all rows, if there were errors, throw a single exception
        if (!empty($this->errors)) {
            // join with <br> so it will render nicely in an HTML toast
            throw new \Exception(implode('<br>', $this->errors));
        }
    }
}
