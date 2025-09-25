<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class Quotecafe extends Seeder
{
    public function run(): void
    {
        $quotes = [
            [
                'supplier_id' => 1,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(2),
                'due_date' => Carbon::now()->addDays(5),
                'comments' => 'First test quote for cafe supplies',
                'type' => 'F',
                'items' => [
                    ['item_id' => 1, 'quantity' => 10, 'unit_price' => 250],
                ],
            ],
            [
                'supplier_id' => 2,
                'branch_id' => 2,
                'quote_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(3),
                'comments' => 'Second quote for food items',
                'type' => 'F',
                'items' => [
                   ['item_id' => 2, 'quantity' => 5,  'unit_price' => 150],

                ],
            ],
            [
                'supplier_id' => 3,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(3),
                'due_date' => Carbon::now()->addDays(4),
                'comments' => 'Third quote: milk and sugar order',
                'type' => 'F',
                'items' => [
                    ['item_id' => 3, 'quantity' => 12, 'unit_price' => 120],
                ],
            ],
            [
                'supplier_id' => 4,
                'branch_id' => 2,
                'quote_date' => Carbon::now()->subDays(4),
                'due_date' => Carbon::now()->addDays(6),
                'comments' => 'Fourth quote: new stock for bakery',
                'type' => 'F',
                'items' => [
                    ['item_id' => 4, 'quantity' => 12, 'unit_price' => 200],
                ],
            ],
            [
                'supplier_id' => 5,
                'branch_id' => 1,
                'quote_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(7),
                'comments' => 'Fifth quote: weekly vegetables and fruits',
                'type' => 'F',
                'items' => [
                    ['item_id' => 5, 'quantity' => 30, 'unit_price' => 600],
                    ['item_id' => 6, 'quantity' => 24, 'unit_price' => 400],
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
