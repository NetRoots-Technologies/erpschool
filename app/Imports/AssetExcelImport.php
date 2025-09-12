<?php

namespace App\Imports;

use App\Models\HR\Asset;
use App\Models\HR\AssetType;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use App\Models\Account\Ledger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetExcelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Debug: Log available companies and branches
        $this->logAvailableData();

        DB::beginTransaction();
        try {
            $inserted = 0;

            foreach ($rows as $index => $row) {
                // Skip empty rows
                if ($row->filter()->isEmpty()) {
                    Log::info("Skipped empty row: " . ($index + 2));
                    continue;
                }

                // You can also check required fields before processing
                if (!$row['name'] || !$row['company'] || !$row['branch']) {
                    Log::warning("Missing required fields at row " . ($index + 2));
                    continue;
                }

                // Convert text values to IDs
                $companyId = $this->getCompanyId($row['company']);
                $branchId = $this->getBranchId($row['branch']);
                $assetTypeId = $this->getAssetTypeId($row['asset_type']);
                $creditLedgerId = $this->getCreditLedgerId($row['credit_ledger']);

                // Log the lookups for debugging
                Log::info("Row " . ($index + 2) . " - Company: '{$row['company']}' -> ID: {$companyId}, Branch: '{$row['branch']}' -> ID: {$branchId}");

                // Skip if essential IDs not found
                if (!$companyId || !$branchId) {
                    Log::warning("Company or Branch not found at row " . ($index + 2) .
                        " - Company: '{$row['company']}' (ID: {$companyId}), Branch: '{$row['branch']}' (ID: {$branchId})");
                    continue;
                }

                // Clean and validate numeric fields
                $amount = is_numeric($row['amount']) ? floor((float)$row['amount']) : 0;
                $salesTax = is_numeric($row['sales_tax']) ? floor((float)$row['sales_tax']) : 0;
                $incomeTax = is_numeric($row['income_tax']) ? floor((float)$row['income_tax']) : 0;
                $invoice = is_numeric($row['invoice']) ? floor((float)$row['invoice']) : null;

                // Handle date fields
                $purchaseDate = $this->parseDate($row['purchase_date']);
                $warrantyEndDate = $this->parseDate($row['warranty_amc_end_date']);

                // Map working status
                $isWorking = $this->mapWorkingStatus($row['is_working']);

                // Create asset with proper field mapping
                Asset::create([
                    'name' => $row['name'],
                    'asset_type_id' => $assetTypeId,
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'credit_type' => $this->mapCreditType($row['credit_type']),
                    'credit_ledger' => $creditLedgerId,
                    'working' => $isWorking,
                    'purchase_date' => $purchaseDate,
                    'invoice_number' => $invoice,
                    'manufacturer' => $row['manufacturer'] ?? null,
                    'serial_number' => $row['serial'] ?? null,
                    'end_date' => $warrantyEndDate,
                    'amount' => $amount,
                    'depreciation_type' => $this->mapDepreciationType($row['depreciation_type']),
                    'sale_tax' => $salesTax,
                    'income_tax' => $incomeTax,
                    'note' => $row['asset_note'] ?? null,
                ]);

                $inserted++;
            }

            DB::commit();
            Log::info("Imported $inserted assets successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Asset Import Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get company ID by name
     */
    private function getCompanyId($companyName)
    {
        if (empty($companyName)) return null;

        $company = Company::where('name', 'like', '%' . trim($companyName) . '%')->first();
        return $company ? $company->id : null;
    }

    /**
     * Get branch ID by name
     */
    private function getBranchId($branchName)
    {
        if (empty($branchName)) return null;

        $branch = Branch::where('name', 'like', '%' . trim($branchName) . '%')->first();
        return $branch ? $branch->id : null;
    }

    /**
     * Get asset type ID by name
     */
    private function getAssetTypeId($assetTypeName)
    {
        if (empty($assetTypeName)) return null;

        $assetType = AssetType::where('name', 'like', '%' . trim($assetTypeName) . '%')->first();
        return $assetType ? $assetType->id : null;
    }

    /**
     * Get credit ledger ID by name
     */
    private function getCreditLedgerId($ledgerName)
    {
        if (empty($ledgerName)) return null;

        $ledger = Ledger::where('name', 'like', '%' . trim($ledgerName) . '%')->first();
        return $ledger ? $ledger->id : null;
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        // Handle different date formats
        $dateString = str_replace(['\\', '/'], '-', $dateValue);

        try {
            return \Carbon\Carbon::createFromFormat('d-m-Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning("Invalid date format: $dateValue");
                return null;
            }
        }
    }

    /**
     * Map working status
     */
    private function mapWorkingStatus($workingStatus)
    {
        if (empty($workingStatus)) return null;

        $status = strtolower(trim($workingStatus));
        return in_array($status, ['yes', '1', 'true', 'working']) ? 'on' : null;
    }

    /**
     * Map credit type
     */
    private function mapCreditType($creditType)
    {
        if (empty($creditType)) return null;

        $type = strtolower(trim($creditType));
        return $type === 'cash' ? '0' : '1'; // Adjust based on your system
    }

    /**
     * Map depreciation type
     */
    private function mapDepreciationType($depreciationType)
    {
        if (empty($depreciationType)) return null;

        $type = strtolower(trim($depreciationType));

        switch ($type) {
            case 'straight line':
                return 'straight_line';
            case 'declining balance':
                return 'declining_balance';
            default:
                return 'straight_line'; // Default
        }
    }

    /**
     * Log available data for debugging
     */
    private function logAvailableData()
    {
        Log::info("Available Companies: " . Company::pluck('name')->implode(', '));
        Log::info("Available Branches: " . Branch::pluck('name')->implode(', '));
        Log::info("Available Asset Types: " . AssetType::pluck('name')->implode(', '));
    }
}
