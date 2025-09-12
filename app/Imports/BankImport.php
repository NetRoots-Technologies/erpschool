<?php

namespace App\Imports;

use App\Models\Admin\Biling;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Bill;
use App\Models\User;

class BankImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $uploadingDate = now();
        $firstRow = true;
        $columnNames = [];
        $data = [];
        foreach ($rows as $row_key => $row) {
            if ($this->isRowNull($row)) {
                continue;
            }
            if ($firstRow) {
                $columnNames = $row->toArray();
                $firstRow = false;
                continue;
            }

            $values = $row->toArray();
            $row_data = [];

            foreach ($columnNames as $key => $item) {
                $row_data[$item] = $values[$key];
            }

            $data[$row_key] = $row_data;
        }
        foreach ($data as $item) {
            $designationId = null;
            $paidDate = Carbon::createFromFormat('m/d/y', $item['Collect_Date'])->format('Y-m-d');
            $voucher = Biling::where('bill_number', $item['Bill_No'])->where('status', 0)->first();

            if ($voucher && $voucher->valid_date >= $uploadingDate) {

                $voucher->update([
                    'paid_amount' => $item['Bill_Amount'],
                    'paid_date' => $paidDate,
                    'status' => 1,
                    'remarks' => $item['Remarks'],
                ]);
                $remainingFee = $voucher->fee - $item['Bill_Amount'];
                if ($remainingFee > 0) {
                    $voucher->previous_amount += $remainingFee;
                    $voucher->save();
                }
            }
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
