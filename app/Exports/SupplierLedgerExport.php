<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class SupplierLedgerExport implements WithMultipleSheets
{
    protected $supplier;
    protected $purchases;
    protected $payments;
    protected $totalOrdered;
    protected $totalPaid;
    protected $outstanding;
    
    public function __construct($supplier, $purchases, $payments, $totalOrdered, $totalPaid, $outstanding)
    {
        $this->supplier = $supplier;
        $this->purchases = $purchases;
        $this->payments = $payments;
        $this->totalOrdered = $totalOrdered;
        $this->totalPaid = $totalPaid;
        $this->outstanding = $outstanding;
    }
    public function sheets(): array
    {
        return [
            new PurchaseOrdersSheet($this->supplier, $this->purchases),
            new PaymentsSheet($this->supplier, $this->payments),
            new SummarySheet($this->supplier, $this->totalOrdered, $this->totalPaid, $this->outstanding),
        ];
    }
}


// Purchase Orders Sheet
class PurchaseOrdersSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $supplier;
    protected $purchases;
    
    public function __construct($supplier, $purchases)
    {
        $this->supplier = $supplier;
        $this->purchases = $purchases;
    }
    
    
    public function collection()
    {
        return $this->purchases->map(function($purchase, $index) {
            return [
                $index + 1,
                \Carbon\Carbon::parse($purchase->created_at)->format('d M Y'),
                $purchase->order_number ?: 'N/A',
                $purchase->total_amount,
                ucfirst($purchase->status ?: 'Pending'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Supplier Ledger Report - Purchase Orders'],
            ['Supplier: ' . $this->supplier->name],
            ['Phone: ' . ($this->supplier->phone ?: '-') . ' | Email: ' . ($this->supplier->email ?: '-')],
            [],
            ['Sr#', 'Date', 'Order No', 'Amount (Rs.)', 'Status'],
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // Sr#
            'B' => 15,  // Date
            'C' => 20,  // Order No
            'D' => 18,  // Amount
            'E' => 15,  // Status
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Merge cells for header
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        
        // Main title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '000000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
        ]);

        // Supplier info styling
        $sheet->getStyle('A2:E3')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => ['size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6']
            ],
        ]);

        // Column headers styling
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);

        // Set row height for headers
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(5)->setRowHeight(20);
        
        // Add borders and styling to data rows
        $lastRow = $sheet->getHighestRow();
        
        if ($lastRow > 5) {
            // All data cells borders
            $sheet->getStyle('A6:E' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
            ]);
            
            // Center align Sr#, Date, and Status
            $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B6:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E6:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Left align Order No
            $sheet->getStyle('C6:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            // Right align and format Amount column
            $sheet->getStyle('D6:D' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('D6:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Alternating row colors
            for ($row = 6; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F2F2F2']
                        ]
                    ]);
                }
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Purchase Orders';
    }
}

// Payments Sheet
class PaymentsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $supplier;
    protected $payments;
    
    public function __construct($supplier, $payments)
    {
        $this->supplier = $supplier;
        $this->payments = $payments;
    }
    
    public function collection()
    {
        return $this->payments->map(function($payment, $index) {
            return [
                $index + 1,
                \Carbon\Carbon::parse($payment->payment_date)->format('d M Y'),
                $payment->voucher_no ?: 'N/A',
                optional($payment->invoice)->invoice_number ?: 'N/A',
                $payment->payment_amount,
                ucfirst(str_replace('_', ' ', $payment->payment_mode ?: 'Cash')),
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Supplier Ledger Report - Payments'],
            ['Supplier: ' . $this->supplier->name],
            ['Phone: ' . ($this->supplier->phone ?: '-') . ' | Email: ' . ($this->supplier->email ?: '-')],
            [],
            ['Sr#', 'Date', 'Voucher No', 'Invoice No', 'Amount Paid (Rs.)', 'Payment Mode'],
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // Sr#
            'B' => 15,  // Date
            'C' => 18,  // Voucher No
            'D' => 18,  // Invoice No
            'E' => 20,  // Amount
            'F' => 18,  // Payment Mode
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for header
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        // Main title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '000000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
        ]);
        
        // Supplier info styling
        $sheet->getStyle('A2:F3')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => ['size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6']
            ],
        ]);

        // Column headers styling
        $sheet->getStyle('A5:F5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Add borders and styling to data rows
        $lastRow = $sheet->getHighestRow();
        
        if ($lastRow > 5) {
            $sheet->getStyle('A6:F' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
            ]);

            // Center align specific columns
            $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B6:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F6:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Left align text columns
            $sheet->getStyle('C6:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            // Right align and format Amount column
            $sheet->getStyle('E6:E' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('E6:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Alternating row colors
            for ($row = 6; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F2F2F2']
                        ]
                    ]);
                }
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Payments';
    }
}

// Summary Sheet
class SummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $supplier;
    protected $totalOrdered;
    protected $totalPaid;
    protected $outstanding;
    
    public function __construct($supplier, $totalOrdered, $totalPaid, $outstanding)
    {
        $this->supplier = $supplier;
        $this->totalOrdered = $totalOrdered;
        $this->totalPaid = $totalPaid;
        $this->outstanding = $outstanding;
    }

    public function collection()
    {
        return collect([
            ['Total Purchased', $this->totalOrdered],
            ['Total Paid', $this->totalPaid],
            ['Outstanding Balance', $this->outstanding],
        ]);
    }
    
    public function headings(): array
    {
        return [
            ['Supplier Ledger Summary'],
            ['Supplier: ' . $this->supplier->name],
            ['Phone: ' . ($this->supplier->phone ?: '-') . ' | Email: ' . ($this->supplier->email ?: '-')],
            [],
            ['Description', 'Amount (Rs.)'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for header
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        // Main title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
        ]);
        
        // Supplier info styling
        $sheet->getStyle('A2:B3')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => ['size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6']
            ],
        ]);

        // Column headers styling
        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Style data rows
        $sheet->getStyle('A6:B8')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'font' => ['size' => 12],
        ]);

        // Left align descriptions
        $sheet->getStyle('A6:A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A6:A8')->getFont()->setBold(true);

        // Format and align amounts
        $sheet->getStyle('B6:B8')->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('B6:B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B6:B8')->getFont()->setBold(true);

        // Highlight outstanding balance row
        $sheet->getStyle('A8:B8')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ],
        ]);
        
       
        return [];
    }
    
    public function title(): string
    {
        return 'Summary';
    }
}