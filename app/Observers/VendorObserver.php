<?php

namespace App\Observers;

use App\Models\Vendor;

class VendorObserver
{
    /**
     * Handle the Vendor "created" event.
     *
     * @return void
     */
    public function created(\App\Models\Admin\Vendor $vendor)
    {
        $vendor->ledger()->create([
            'code' => $vendor->code,
            'name' => $vendor->name,
            'balance' => 0,
            'group_id'=>12,
            'balance_type' => 'd',
            'ledger_type' => 'subsidiary',
        ]);

    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @return void
     */
    public function updated(\App\Models\Admin\Vendor $vendor)
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @return void
     */
    public function deleted(\App\Models\Admin\Vendor $vendor)
    {
        $vendor->ledger()->delete();
    }

    /**
     * Handle the Vendor "restored" event.
     *
     * @return void
     */
    public function restored(\App\Models\Admin\Vendor $vendor)
    {
        //
    }

    /**
     * Handle the Vendor "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(\App\Models\Admin\Vendor $vendor)
    {
        //
    }
}
