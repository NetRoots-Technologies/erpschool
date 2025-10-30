<?php

namespace App\Imports;

use App\Models\HR\Asset;
use Illuminate\Support\Collection;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // dd($rows);
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                // Skip empty rows
                if ($row->filter()->isEmpty()) {
                    continue;
                }

                $company = Company::where('name', trim($row['company']))->first();
                $branch = Branch::where('name', trim($row['branch']))->first();

                Asset::create([
                    'credit_type' => $row['credit_type'] ?? null,
                    'asset_type' => $row['asset_type'] ?? null,
                    'name' => $row['name'] ?? null,
                    'code' => '22000-PAM-24-4',
                    'is_working' => $row['is_working'] ?? null,
                    'company_id' => 1,
                    'branch_id' => 1,
                    'purchase_date' => $row['purchase_date'] ?? null,
                    'invoice' => $row['invoice'] ?? null,
                    'manufacturer' => $row['manufacturer'] ?? null,
                    'serial' => $row['serial'] ?? null,
                    'warranty_amc_end_date' => $row['warranty___amc_end_date'] ?? null,
                    'amount' => $row['amount'] ?? null,
                    'depreciation_type' => $row['depreciation_type'] ?? null,
                    'sales_tax' => $row['sales_tax'] ?? null,
                    'income_tax' => $row['income_tax'] ?? null,
                    'narration' => $row['narration'] ?? null,
                    'asset_note' => $row['asset_note'] ?? null,
                ]);
            }

            DB::commit();
            Log::info("Simple asset import completed successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Asset Import Error: " . $e->getMessage());
            throw $e;
        }
    }
}
