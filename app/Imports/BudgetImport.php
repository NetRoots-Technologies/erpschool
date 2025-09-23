<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\BudgetDetail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class BudgetImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    protected $headersChecked = false;

    public function model(array $row)
    {

        if (!$this->headersChecked) {
            $this->headersChecked = true;

            if (count($row) > 8) {
                throw new \Exception("Excel file headers should not be more than 8 columns!");
            }
        }

        $startDate = null;
        $endDate = null;
        $month = null;

        if (is_numeric($row['start_date'])) {
            $startDate = Carbon::instance(Date::excelToDateTimeObject($row['start_date']))->format('Y-m-d');
        }

        if (is_numeric($row['end_date'])) {
            $endDate = Carbon::instance(Date::excelToDateTimeObject($row['end_date']))->format('Y-m-d');
        }

        if (is_numeric($row['month'])) {
            $month = Carbon::instance(Date::excelToDateTimeObject($row['month']))->format('m-Y');
        }

        $budget = Budget::firstOrCreate(
            ['title' => $row['title']],
            [
                'description' => $row['description'] ?? null,
                'timeFrame'   => $row['timeframe'] ?? 'monthly',
                'startDate'   => $startDate,
                'endDate'     => $endDate,
                'amount'      => $row['annual_amount'],
            ]
        );

        $check = BudgetDetail::where('budget_id', $budget->id)->sum('allocated_amount');
        if ($check > $row['annual_amount']) {
            throw new \Exception("Annual budget exceeded for '{$row['title']}'. Already allocated: {$check}, Annual: {$row['annual_amount']}");
            return $budget;
        }



        return BudgetDetail::firstOrCreate(
            [
                'budget_id'   => $budget->id,
                'month'       => $month,
            ],
            [
                'allocated_amount'      => $row['allocated_amount'],
                'allowed_spend'      => $row['allowed_spend'],
            ]
        );
    }
}
