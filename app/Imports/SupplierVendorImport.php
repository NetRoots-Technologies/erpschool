<?php

namespace App\Imports;

use App\Models\Supplier;
use App\Models\Accounts\Vendor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures,
    SkipsEmptyRows
};

class SupplierVendorImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows
{
    use SkipsFailures;

    protected int $branchId = 1;

    protected array $typeMap = [
        'food' => 'F',
        'stationary' => 'S',
        'uniform' => 'U',
        'general' => 'G',
        'f' => 'F',
        's' => 'S',
        'u' => 'U',
        'g' => 'G',
    ];

    /**
     * 🔥 DATA CLEAN BEFORE VALIDATION
     */
    public function prepareForValidation($data, $index)
    {
        return [
            'name'           => trim($data['name'] ?? ''),
            'email'          => trim($data['email'] ?? ''),
            'contact'        => trim($data['contact'] ?? ''),
            'address'        => trim($data['address'] ?? ''),
            'ntn_number'     => trim($data['ntn_number'] ?? ''),
            'tax_percentage' => trim($data['tax_percentage'] ?? 0),
            'type'           => strtolower(trim($data['type'] ?? '')),
        ];
    }

    /**
     * 📥 IMPORT LOGIC
     */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                $supplierType = $this->typeMap[$row['type']] ?? 'G';

                $supplier = Supplier::updateOrCreate(
                    ['email' => $row['email']],
                    [
                        'name'           => $row['name'],
                        'contact'        => $row['contact'],
                        'address'        => $row['address'],
                        'ntn_number'     => $row['ntn_number'],
                        'tax_percentage' => (float) $row['tax_percentage'],
                        'type'           => $supplierType,
                    ]
                );

                $supplier->branches()->syncWithoutDetaching([$this->branchId]);

                Vendor::firstOrCreate(
                    [
                        'email'     => $supplier->email,
                        'branch_id' => $this->branchId,
                    ],
                    [
                        'name'           => $supplier->name,
                        'code'           => 'VEN-' . strtoupper(Str::random(6)),
                        'phone'          => $supplier->contact,
                        'contact_person' => $supplier->name,
                        'address'        => $supplier->address,
                        'tax_number'     => $supplier->ntn_number,
                        'is_active'      => 1,
                        'branch_id'      => $this->branchId,
                        'created_by'     => auth()->id(),
                        'updated_by'     => auth()->id(),
                    ]
                );
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * ✅ VALIDATION
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|min:3',
            '*.email' => 'required|email',
            '*.contact' => 'required',
            '*.address' => 'required',
            '*.ntn_number' => 'required',
            '*.tax_percentage' => 'nullable|numeric',
            '*.type' => 'required|in:food,stationary,uniform,general,f,s,u,g',
        ];
    }
}
