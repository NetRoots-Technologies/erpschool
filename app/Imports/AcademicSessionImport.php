<?php

namespace App\Imports;

use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcademicSessionImport implements ToCollection, WithHeadingRow
{
    
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            try {

                $startDate = Carbon::instance(Date::excelToDateTimeObject($row['start_date']));
                $endDate = Carbon::instance(Date::excelToDateTimeObject($row['end_date']));

                $name = $row['name'];
                $sDate = $startDate->toDateString();
                $eDate = $endDate->toDateString();

                if (!$name || !$startDate || !$endDate) {
                    Log::warning("Missing reference data at row " , [
                        'row' => $row->toArray(),
                        'missing' => [
                            'name' => $name ? 'OK' : 'Not Found',
                            'startDate' => $startDate ? 'OK' : 'Not Found',
                            'endDate' => $endDate ? 'OK' : 'Not Found',
                        ]
                    ]);
                    continue; // skip this row
                }

                AcademicSession::create([
                    'name'=>$name,
                    'start_date'=>$sDate,
                    'end_date'=>$eDate
                ]);

            } catch (\Throwable $e) {
                Log::error('AcademicClass Import Error at row ' , [
                    'message' => $e->getMessage(),
                    'row' => $row->toArray(),
                ]);
            }
        }

    }
}

