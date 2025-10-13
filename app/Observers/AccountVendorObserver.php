<?php

namespace App\Observers;

use App\Models\Accounts\Vendor;
use App\Models\Accounts\AccountLedger;

class AccountVendorObserver
{
    /**
     * Handle the Vendor "created" event.
     */
    public function created(Vendor $vendor)
    {
        // Auto-create account ledger for vendor
        if (!$vendor->account_ledger_id) {
            $ledger = AccountLedger::create([
                'name' => 'Vendor - ' . $vendor->name,
                'code' => 'VEN-' . $vendor->id . '-' . time(),
                'description' => 'Vendor payable account',
                'account_group_id' => 7, // Accounts Payable
                'opening_balance' => 0,
                'opening_balance_type' => 'credit',
                'current_balance' => 0,
                'current_balance_type' => 'credit',
                'is_active' => true,
                'created_by' => $vendor->created_by ?? 1
            ]);
            
            $vendor->account_ledger_id = $ledger->id;
            $vendor->save();
            
            \Log::info("Account ledger auto-created for vendor: {$vendor->name} (ID: {$vendor->id})");
        }
    }

    /**
     * Handle the Vendor "deleted" event.
     */
    public function deleted(Vendor $vendor)
    {
        if ($vendor->accountLedger) {
            $vendor->accountLedger->delete();
        }
    }
}

