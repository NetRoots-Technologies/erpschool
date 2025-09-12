<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Credit Type',
            'Credit Ledger',
            'Asset Type',
            'Name',
            'Is Working?',
            'Company',
            'Branch',
            'Purchase Date',
            'Invoice',
            'Manufacturer',
            'Serial',
            'Warranty / AMC End Date',
            'Amount',
            'Depreciation Type',
            'Sales Tax',
            'Income Tax',
            'Narration',
            'Asset Note'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Cash',               // Credit Type
                'Main Ledger',        // Credit Ledger
                'Electronics',        // Asset Type
                'Laptop HP ProBook',  // Name
                'Yes',                // Is Working?
                'Cornerstone Ltd',    // Company
                'Head Office',        // Branch
                '2024-01-15',         // Purchase Date
                'INV-1001',           // Invoice
                'HP',                 // Manufacturer
                'SN12345678',         // Serial
                '2026-01-15',         // Warranty / AMC End Date
                '120000',             // Amount
                'Straight Line',      // Depreciation Type
                '10%',                // Sales Tax
                '5%',                 // Income Tax
                'New asset added',    // Narration
                'Used for development'// Asset Note
            ]
        ];
    }
}
