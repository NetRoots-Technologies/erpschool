<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierStationery extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Office Supplies Hub',
                'number' => '03001112222',
                'company_name' => 'Office Hub Pvt Ltd',
                'contact' => 'Imran Qureshi',
                'address' => 'Shahrah-e-Faisal, Karachi',
                'email' => 'officehub@example.com',
                'rating' => 4,
                'status' => 1,
                'type' => 'S',
            ],
            [
                'name' => 'Punjab Stationers',
                'number' => '03213334444',
                'company_name' => 'Punjab Office Mart',
                'contact' => 'Ali Hassan',
                'address' => 'Mall Road, Lahore',
                'email' => 'punjabstationers@example.com',
                'rating' => 5,
                'status' => 1,
                'type' => 'S',
            ],
            [
                'name' => 'Islamabad Paper House',
                'number' => '03125556677',
                'company_name' => 'Paper House & Co.',
                'contact' => 'Sana Sheikh',
                'address' => 'G-10 Markaz, Islamabad',
                'email' => 'isbpapers@example.com',
                'rating' => 4,
                'status' => 1,
                'type' => 'S',
            ],
            [
                'name' => 'Quetta Office Depot',
                'number' => '03332221100',
                'company_name' => 'Quetta Depot Ltd',
                'contact' => 'Faisal Baloch',
                'address' => 'Jinnah Road, Quetta',
                'email' => 'quettaoffice@example.com',
                'rating' => 4,
                'status' => 1,
                'type' => 'S',
            ],
            [
                'name' => 'Peshawar Stationery Mart',
                'number' => '03447778899',
                'company_name' => 'Peshawar Stationers Pvt Ltd',
                'contact' => 'Adnan Afridi',
                'address' => 'Saddar Road, Peshawar',
                'email' => 'peshstationery@example.com',
                'rating' => 5,
                'status' => 1,
                'type' => 'S',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
