<?php

namespace App\Observers;

use App\Models\Accounts\Customer;
use App\Models\Accounts\AccountLedger;

class AccountCustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer)
    {
        // Auto-create account ledger for customer
        if (!$customer->account_ledger_id) {
            $ledger = AccountLedger::create([
                'name' => 'Customer - ' . $customer->name,
                'code' => 'CUST-' . $customer->id . '-' . time(),
                'description' => 'Customer receivable account',
                'account_group_id' => 4, // Accounts Receivable
                'opening_balance' => 0,
                'opening_balance_type' => 'debit',
                'current_balance' => 0,
                'current_balance_type' => 'debit',
                'is_active' => true,
                'created_by' => $customer->created_by ?? 1
            ]);
            
            $customer->account_ledger_id = $ledger->id;
            $customer->save();
            
            \Log::info("Account ledger auto-created for customer: {$customer->name} (ID: {$customer->id})");
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer)
    {
        if ($customer->accountLedger) {
            $customer->accountLedger->delete();
        }
    }
}

