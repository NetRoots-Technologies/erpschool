<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requisition;
use Carbon\Carbon;

class RequisitionStationery extends Seeder
{
    public function run(): void
    {
        $requisitions = [
            [
                'requester_id'    => 1,
                'item_id'         => 8, // e.g., Pens
                'branch_id'       => 1,
                'type'            => 'S', // Stationery
                'quantity'        => 50,
                'priority'        => 'HIGH',
                'justification'   => 'Need pens for training session',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(1),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 9, // e.g., Notebooks
                'branch_id'       => 1,
                'type'            => 'S',
                'quantity'        => 30,
                'priority'        => 'MEDIUM',
                'justification'   => 'For documentation and record keeping',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(2),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 10, // e.g., A4 Paper
                'branch_id'       => 1,
                'type'            => 'S',
                'quantity'        => 10,
                'priority'        => 'HIGH',
                'justification'   => 'Printer running low on A4 paper',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(3),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 11, // e.g., Markers
                'branch_id'       => 1,
                'type'            => 'S',
                'quantity'        => 12,
                'priority'        => 'LOW',
                'justification'   => 'Need markers for whiteboards',
                'status'          => 'PENDING',
                'is_approved'     => 0,
                'requisition_to'  => 'supplier',
                'requested_date'  => Carbon::now()->subDays(4),
            ],
            [
                'requester_id'    => 1,
                'item_id'         => 12, // e.g., Sticky Notes
                'branch_id'       => 1,
                'type'            => 'S',
                'quantity'        => 20,
                'priority'        => 'MEDIUM',
                'justification'   => 'For desk notes and reminders',
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
