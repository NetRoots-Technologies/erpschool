<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class QuoteStationery extends Seeder
{
    public function run(): void
    {
        $quotes = [
            [
                'supplier_id' => 6, // Stationery supplier
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(2),
                'due_date' => Carbon::now()->addDays(4),
                'comments' => 'Stationery quote for pens and notebooks',
                'type' => 'S',
                'items' => [
                    ['item_id' => 8, 'quantity' => 50, 'unit_price' => 20], // Pens
                ],
            ],
            [
                'supplier_id' => 7,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(5),
                'comments' => 'Stationery quote for A4 papers and folders',
                'type' => 'S',
                'items' => [
                     ['item_id' => 9, 'quantity' => 30, 'unit_price' => 80], // Notebooks
                ],
            ],
            [
                'supplier_id' => 8,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(3),
                'due_date' => Carbon::now()->addDays(6),
                'comments' => 'Stationery quote for markers and sticky notes',
                'type' => 'S',
                'items' => [
                    ['item_id' => 10, 'quantity' => 40, 'unit_price' => 50],  // Sticky Notes
                ],
            ],
                        [
                'supplier_id' => 9,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(5),
                'comments' => 'Stationery quote for Marker',
                'type' => 'S',
                'items' => [
                     ['item_id' => 11, 'quantity' => 30, 'unit_price' => 80], // Marker
                ],
            ],
                        [
                'supplier_id' => 10,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(5),
                'comments' => 'Stationery quote for A4 papers and folders',
                'type' => 'S',
                'items' => [
                     ['item_id' => 12, 'quantity' => 30, 'unit_price' => 80], 
                     ['item_id' => 13, 'quantity' => 24, 'unit_price' => 120], 

                ],
            ],
        ];

        foreach ($quotes as $quoteData) {
            $quote = Quote::create([
                'supplier_id' => $quoteData['supplier_id'],
                'branch_id'   => $quoteData['branch_id'],
                'quote_date'  => $quoteData['quote_date'],
                'due_date'    => $quoteData['due_date'],
                'comments'    => $quoteData['comments'],
                'type'        => $quoteData['type'],
            ]);

            foreach ($quoteData['items'] as $item) {
                QuoteItem::create([
                    'quote_id'    => $quote->id,
                    'item_id'     => $item['item_id'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }
    }
}
