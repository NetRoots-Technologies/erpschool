<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requisition;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequisitionCafe extends Seeder
{
    public function run(): void
    {
        $requisitions = [
            [
                'requester_id'    => 1,
                'item_id'         => 1,
                'branch_id'       => 1,
                'type'            => 'F', // Food
                'quantity'        => 10,
                'priority'        => 'HIGH',
                'justification'   => 'Need urgently for café opening',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(1),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 2,
                'branch_id'       => 1,
                'type'            => 'F',
                'quantity'        => 5,
                'priority'        => 'MEDIUM',
                'justification'   => 'Stock running low for milk',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(2),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 3,
                'branch_id'       => 1,
                'type'            => 'F', 
                'quantity'        => 20,
                'priority'        => 'LOW',
                'justification'   => 'Need paper cups and napkins',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(3),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 4,
                'branch_id'       => 1,
                'type'            => 'F',
                'quantity'        => 2,
                'priority'        => 'MEDIUM',
                'justification'   => 'Low stock on butter',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(4),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 5,
                'branch_id'       => 1,
                'type'            => 'F', 
                'quantity'        => 15,
                'priority'        => 'HIGH',
                'justification'   => 'New packaging material needed',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($requisitions as $req) {
            Requisition::create($req);
        }
    }
}
