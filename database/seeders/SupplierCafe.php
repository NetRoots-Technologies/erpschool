<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierCafe extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Karachi Food Traders',
                'number' => '03001234567',
                'company_name' => 'Karachi Food Traders Pvt Ltd',
                'contact' => 'Muhammad Ali',
                'address' => 'Gulshan-e-Iqbal, Karachi',
                'email' => 'karachifood@example.com',
                'rating' => 4,
                'status' => 1,
                'type' => 'F',
            ],
            [
                'name' => 'Lahore Dairy Supplies',
                'number' => '03211223344',
                'company_name' => 'Lahore Dairy & Co.',
                'contact' => 'Ahmad Raza',
                'address' => 'Ferozepur Road, Lahore',
                'email' => 'lahoredairy@example.com',
                'rating' => 5,
                'status' => 1,
                'type' => 'F',
            ],
            [
                'name' => 'Islamabad Stationers',
                'number' => '03137778899',
                'company_name' => 'Capital Office Supplies',
                'contact' => 'Hassan Nawaz',
                'address' => 'Blue Area, Islamabad',
                'email' => 'isbstationers@example.com',
                'rating' => 4,
                'status' => 1,
                'type' => 'F',
            ],
            [
                'name' => 'Multan Bakers Supply',
                'number' => '03013334455',
                'company_name' => 'Multan Bakery Mart',
                'contact' => 'Zeeshan Malik',
                'address' => 'Chowk BCG, Multan',
                'email' => 'multanbakers@example.com',
                'rating' => 5,
                'status' => 1,
                'type' => 'F',
            ],
            [
                'name' => 'Peshawar Food Importers',
                'number' => '03442223333',
                'company_name' => 'Frontier Food Traders',
                'contact' => 'Umar Farooq',
                'address' => 'University Town, Peshawar',
                'email' => 'peshfood@example.com',
                'rating' => 5,
                'status' => 1,
                'type' => 'F',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
