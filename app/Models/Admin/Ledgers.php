<?php

namespace App\Models\Admin;

use App\Models\Accounts\AccountLedger;

/**
 * Legacy Ledgers model for backward compatibility
 * This is an alias for AccountLedger
 */
class Ledgers extends AccountLedger
{
    protected $table = 'account_ledgers';

    /**
     * Create a new Ledgers instance (alias for AccountLedger)
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
