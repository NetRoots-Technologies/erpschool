<?php

namespace App\Services;

use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\Accounts\Vendor;
use App\Models\Accounts\AccountGroup;
use Illuminate\Http\Request;

/**
 * LedgerService - Bridge to new Accounts system
 * Provides backward compatibility for old code
 */
class LedgerService
{
    /**
     * Create a new ledger
     */
    public function createLedger($data)
    {
        try {
            return AccountLedger::create([
                'name' => $data['name'] ?? $data['ledger_name'] ?? 'Unnamed Ledger',
                'code' => $data['code'] ?? $data['ledger_code'] ?? 'AUTO-' . time(),
                'account_group_id' => $data['account_group_id'] ?? $data['group_id'] ?? 1,
                'opening_balance' => $data['opening_balance'] ?? 0,
                'opening_balance_type' => $data['opening_balance_type'] ?? 'debit',
                'current_balance' => $data['opening_balance'] ?? 0,
                'current_balance_type' => $data['opening_balance_type'] ?? 'debit',
                'is_active' => $data['is_active'] ?? true,
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            \Log::error('LedgerService::createLedger failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update an existing ledger
     */
    public function updateLedger($id, $data)
    {
        try {
            $ledger = AccountLedger::find($id);
            if ($ledger) {
                $ledger->update([
                    'name' => $data['name'] ?? $ledger->name,
                    'code' => $data['code'] ?? $ledger->code,
                    'description' => $data['description'] ?? $ledger->description,
                    'is_active' => $data['is_active'] ?? $ledger->is_active,
                    'updated_by' => auth()->id(),
                ]);
                return $ledger;
            }
            return null;
        } catch (\Exception $e) {
            \Log::error('LedgerService::updateLedger failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get a ledger by ID
     */
    public function getLedger($id)
    {
        return AccountLedger::find($id);
    }
    
    /**
     * Get ledger by name
     */
    public function getLedgerByName($name)
    {
        return AccountLedger::where('name', 'LIKE', "%{$name}%")->first();
    }
    
    /**
     * Get all active ledgers
     */
    public function getAllLedgers()
    {
        return AccountLedger::where('is_active', true)->get();
    }
    
    /**
     * Get ledgers by type
     */
    public function getLedgersByType($type)
    {
        return AccountLedger::whereHas('accountGroup', function($q) use ($type) {
            $q->where('type', $type);
        })->where('is_active', true)->get();
    }
    
    /**
     * Create journal entry (for backward compatibility)
     */
    public function createJournalEntry($data)
    {
        try {
            DB::beginTransaction();
            
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $data['date'] ?? now(),
                'reference' => $data['reference'] ?? '',
                'description' => $data['description'] ?? 'Auto-generated entry',
                'status' => 'posted',
                'entry_type' => $data['type'] ?? 'journal',
                'source_module' => $data['source_module'] ?? 'legacy',
                'source_id' => $data['source_id'] ?? null,
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);
            
            // Create lines
            if (isset($data['lines']) && is_array($data['lines'])) {
                foreach ($data['lines'] as $line) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_ledger_id' => $line['ledger_id'] ?? $line['account_ledger_id'],
                        'description' => $line['description'] ?? '',
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                    ]);
                    
                    // Update ledger balance
                    $ledger = AccountLedger::find($line['ledger_id'] ?? $line['account_ledger_id']);
                    if ($ledger) {
                        $ledger->updateBalance($line['debit'] ?? 0, $line['credit'] ?? 0);
                    }
                }
            }
            
            DB::commit();
            return $entry;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('LedgerService::createJournalEntry failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get ledger balance
     */
    public function getLedgerBalance($ledgerId)
    {
        $ledger = AccountLedger::find($ledgerId);
        return $ledger ? $ledger->current_balance : 0;
    }

    /**
     * Create auto ledgers for entities (backward compatibility method)
     * 
     * @param array $groupIds - Array of group IDs
     * @param string $ledgerName - Name for the ledger
     * @param int $branchId - Branch ID
     * @param string $modelName - Model class name (e.g., Students::class)
     * @param int $modelId - Model instance ID
     * @return AccountLedger|null
     */
    public function createAutoLedgers($groupIds, $ledgerName, $branchId, $modelName, $modelId)
    {
        // dd($groupIds, $ledgerName, $branchId, $modelName, $modelId);
        try {
            // Get the first group ID (for backward compatibility)
            $groupId = is_array($groupIds) ? $groupIds[0] : $groupIds;

            // Extract module name from model class
            $moduleName = strtolower(class_basename($modelName));

            // Check if ledger already exists for this entity
            $existingLedger = AccountLedger::where('linked_module', $moduleName)
                ->where('linked_id', $modelId)
                ->first();

            if ($existingLedger) {
                \Log::info("Ledger already exists for {$moduleName} ID {$modelId}");
                return $existingLedger;
            }

            // Generate unique code
            $code = 'AUTO-' . strtoupper(substr($moduleName, 0, 3)) . '-' . $modelId . '-' . time();

            // Create the ledger
            $ledger = AccountLedger::create([
                'name' => $ledgerName,
                'code' => $code,
                'account_group_id' => $groupId,
                'opening_balance' => 0,
                'opening_balance_type' => 'debit',
                'current_balance' => 0,
                'current_balance_type' => 'debit',
                'is_active' => true,
                'is_system' => false,
                'linked_module' => $moduleName,
                'linked_id' => $modelId,
                'branch_id' => $branchId,
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1,
            ]);

            \Log::info("Auto ledger created: {$ledgerName} for {$moduleName} ID {$modelId}");

            return $ledger;

        } catch (\Exception $e) {
            \Log::error("LedgerService::createAutoLedgers failed: " . $e->getMessage());
            return null;
        }
    }

     public function createAutoLedgersForSuppliers($groupIds, $ledgerName, $branchId, $modelName, $modelId , $request)
    {
        
        try {
                $payablesGroup = AccountGroup::where('type', 'liability')
                    ->where('name', 'LIKE', '%Payable%')
                    ->first();
                if (!$payablesGroup) {
                    throw new \Exception('Accounts Payable group not found. Please setup chart of accounts first.');
                }

                  $lastCode = Vendor::orderBy('id', 'desc')->value('code');
                if (!$lastCode) {
                    $code = 'VEN-001';
                }
                preg_match('/(\d+)$/', $lastCode, $matches);
                $number = isset($matches[1]) ? (int)$matches[1] + 1 : 1;

                $code = 'VEN-' . str_pad($number, 3, '0', STR_PAD_LEFT);


                $ledger = AccountLedger::create([
                    'name' => 'Vendor - ' . $request['name'],
                    'code' => $code,
                    'account_group_id' => $payablesGroup->id,
                    'opening_balance' => 0,
                    'opening_balance_type' => 'credit',
                    'current_balance' => 0,
                    'current_balance_type' => 'credit',
                    'linked_module' => 'vendor',
                    'branch_id' => $branchId,
                    'created_by' => auth()->id(),
                ]);
                
            // Create vendor
            $vendor = Vendor::create([
                'name' => $request["name"],
                'code' =>  $code,
                'email' => $request["email"],
                'phone' => "",
                'contact_person' => $request["contact"],
                'address' => $request["address"],
                'city' => 'Lahore',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'tax_number' => "",
                'payment_terms' => "",
                'account_ledger_id' => $ledger->id,
                'branch_id' => $branchId,
                'created_by' => auth()->id(),
            ]);

            // Update ledger link
            $ledger->linked_id = $vendor->id;
            $ledger->save();

            return $ledger;

        } catch (\Exception $e) {
            \Log::error("LedgerService::createAutoLedgers failed: " . $e->getMessage());
            return null;
        }
    }

   
    /**
     * Get ledgers for a specific model/entity
     * 
     * @param int $groupId - Group ID
     * @param string $modelName - Model class name
     * @param int $modelId - Model instance ID
     * @return AccountLedger|null
     */
    // public function getLedgers($groupId, $modelName, $modelId)
    // {
    //     dd($groupId);
    //     // foreach ($groupId as $key => $id) {
    //     //    AccountGroup::where('id' , $id)->first();
    //     // }
       
    //     return AccountLedger::whereIn('account_group_id', $groupId)
    //         ->where('linked_module',  $modelName)
    //         ->where('linked_id', $modelId)
    //         ->get();
    // }

    public function getLedgers($groupIds, $modelName = null, $modelId = null): Collection
{
    
    // 0) Normalize inputs
    $groupIds = is_array($groupIds) ? $groupIds : [$groupIds];
    $groupIds = array_values(array_filter($groupIds, fn ($v) => !is_null($v)));

    // linked_module ko short name store karo (e.g. "Branches")
    $module = null;
    if (!empty($modelName)) {
        $module = class_exists($modelName) ? class_basename($modelName) : (string) $modelName;
    }

    // Agar module Branches hai, branchId = modelId assume (zarurat par 4th param bana sakte ho)
    $branchId = ($module === 'Branches') ? $modelId : null;

    // 1) Existing ledgers pick (same group + same link)
    $existing = AccountLedger::query()
        ->whereIn('account_group_id', $groupIds)
        ->when($module, fn ($q) => $q->where('linked_module', $module))
        ->when($modelId, fn ($q) => $q->where('linked_id', $modelId))
        ->when(!is_null($branchId), fn ($q) => $q->where(function ($qq) use ($branchId) {
            $qq->whereNull('branch_id')->orWhere('branch_id', $branchId);
        }))
        ->get()
        ->keyBy('account_group_id');

    $out = collect();

    // 2) Har groupId ke liye ensure (firstOrCreate) + push to result
    foreach ($groupIds as $gid) {
        if ($existing->has($gid)) {
            $out->push($existing->get($gid));
            continue;
        }

        $group = \App\Models\Accounts\AccountGroup::find($gid);
        if (!$group) {
            // yahan chahe to throw new \Exception(...) bhi kar sakte ho
            continue;
        }

        // assets/expenses => debit normal; otherwise credit
        $gtype  = strtolower((string) $group->type);
        $normal = in_array($gtype, ['asset','assets','expense','expenses'], true) ? 'debit' : 'credit';

        // Simple sequential code
        $nextId = (AccountLedger::max('id') ?? 0) + 1;
        $code   = 'LED-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);

        // Default name
        $name = $group->name . ($module && $modelId ? " - {$module} #{$modelId}" : ' Ledger');

        // Uniqueness key: same group + link + branch
        $ledger = AccountLedger::firstOrCreate(
            [
                'name'             => $name,
                'account_group_id' => $gid,
                'linked_module'    => $module,
                'linked_id'        => $modelId,
                'branch_id'        => $branchId,
            ],
            [
                'code'                  => $code,
                'description'           => $name,
                'opening_balance'       => 0,
                'opening_balance_type'  => $normal,
                'current_balance'       => 0,
                'current_balance_type'  => $normal,
                'currency_id'           => 1,
                'is_active'             => 1,
                'is_system'             => 0,
                'created_by'            => auth()->id(),
                'updated_by'            => auth()->id(),
            ]
        );

        $out->push($ledger);
    }

    return $out->values(); // collection of AccountLedger models
}

    /**
     * Create entry (for old system compatibility)
     * Used by purchase orders and billing
     */
    public function createEntry($data)
    {
        try {
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $data['voucher_date'] ?? $data['date'] ?? now(),
                'reference' => $data['number'] ?? $data['reference'] ?? '',
                'description' => $data['narration'] ?? $data['description'] ?? 'Legacy entry',
                'status' => 'posted',
                'entry_type' => 'journal',
                'source_module' => $data['source_module'] ?? 'legacy',
                'source_id' => $data['source_id'] ?? null,
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id() ?? 1,
                'created_by' => auth()->id() ?? 1,
            ]);
            
            \Log::info("Legacy journal entry created: ID {$entry->id}");
            return $entry;
        } catch (\Exception $e) {
            \Log::error('LedgerService::createEntry failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create entry items (for old system compatibility)
     * Used by purchase orders and billing
     */
    public function createEntryItems($data)
    {
        // dd($data , '2');
        try {
            $debit = 0;
            $credit = 0;
            
            if ($data['balanceType'] == 'd') {
                $debit = $data['amount'];
            } else {
                $credit = $data['amount'];
            }
            
            $line = JournalEntryLine::create([
                'journal_entry_id' => $data['entry_id'],
                'account_ledger_id' => $data['ledger_id'],
                'description' => $data['narration'] ?? '',
                'debit' => $debit,
                'credit' => $credit,
            ]);
            
            // Update ledger balance
            $ledger = AccountLedger::find($data['ledger_id']);
            if ($ledger) {
                $ledger->updateBalance($debit, $credit);
            }
            
            \Log::info("Legacy journal entry line created: ID {$line->id}, Ledger: {$data['ledger_id']}, Debit: {$debit}, Credit: {$credit}");
            return $line;
        } catch (\Exception $e) {
            \Log::error('LedgerService::createEntryItems failed: ' . $e->getMessage());
            return null;
        }
    }
}

