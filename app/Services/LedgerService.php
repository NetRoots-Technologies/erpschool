<?php

namespace App\Services;

use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get ledgers for a specific model/entity
     * 
     * @param int $groupId - Group ID
     * @param string $modelName - Model class name
     * @param int $modelId - Model instance ID
     * @return AccountLedger|null
     */
    public function getLedgers($groupId, $modelName, $modelId)
    {
        $moduleName = strtolower(class_basename($modelName));

        return AccountLedger::where('account_group_id', $groupId)
            ->where('linked_module', $moduleName)
            ->where('linked_id', $modelId)
            ->first();
    }
}

