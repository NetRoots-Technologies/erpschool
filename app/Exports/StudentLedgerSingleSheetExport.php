<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StudentLedgerSingleSheetExport implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    protected $student;
    protected $bills;
    protected $collections;
    protected $adjustments;
    protected $from;
    protected $to;

    public function __construct($student, $bills, $collections, $adjustments, $from = null, $to = null)
    {
        $this->student = $student;
        $this->bills = $bills;
        $this->collections = $collections;
        $this->adjustments = $adjustments;
        $this->from = $from;
        $this->to = $to;
    }

    public function array(): array
    {
        $rows = [];

        // Reporting From / To
        $reportFrom = $this->from ? Carbon::parse($this->from)->format('d-M-Y') : '01-Jul-2000';
        $reportTo   = $this->to   ? Carbon::parse($this->to)->format('d-M-Y') : Carbon::now()->format('d-M-Y');
        $rows[] = ["Reporting From", $reportFrom . " to " . $reportTo, '', '', '', '', ''];

        // Student meta row (single row)
        $rows[] = [
            'Student ID:', $this->student->student_id ?? '',
            'Student Name:', $this->student->fullname ?? '',
            'Father Name:', $this->student->father_name ?? '',
            'Class/Sec/Year:', $this->student->academicClass->name ?? '',
            'Status:', 'Admitted|On Roll'
        ];

        $rows[] = []; // blank
        // Table header: Date | Vr.Type | Vr. No. | Narration | Debit | Credit | Balance
        $rows[] = ['Date', 'Vr.Type', 'Vr. No.', 'Narration', 'Debit', 'Credit', 'Balance'];

        // Build transactions list
        $transactions = [];

        // 1) Bills => FB (Debit)
        foreach ($this->bills as $bill) {
            $date = $bill->bill_date ?? $bill->created_at ?? null;
            $refNo = $bill->reference_no ?? $bill->invoice_no ?? $bill->bill_number ?? $bill->id;
            $narration = $bill->narration ?? $bill->description ?? $bill->title ?? 'Fee Bill';
            $amount = (float) ($bill->total_amount ?? $bill->amount ?? $bill->gross_amount ?? 0);

            $transactions[] = [
                'date' => $date ? Carbon::parse($date) : Carbon::now(),
                'vr_type' => 'FB',
                'vr_no' => $refNo,
                'narration' => $narration,
                'debit' => $amount,
                'credit' => 0,
            ];
        }

        // 2) Collections => FR (Credit)
        foreach ($this->collections as $c) {
            $date = $c->collection_date ?? $c->created_at ?? null;
            $ref = $c->collection_number ?? $c->voucher_no ?? $c->receipt_no ?? $c->id;
            // compose narration like sample: maybe include billing invoice etc.
            $baseNarr = $c->billing->description ?? $c->remarks ?? 'Fee Receipt';
            $fullNarr = trim($baseNarr);
            $amt = (float) ($c->paid_amount ?? 0);

            $transactions[] = [
                'date' => $date ? Carbon::parse($date) : Carbon::now(),
                'vr_type' => 'FR',
                'vr_no' => $ref,
                'narration' => $fullNarr,
                'debit' => 0,
                'credit' => $amt,
            ];
        }

        // 3) Adjustments => DA (discount) or RE (refund)
        foreach ($this->adjustments as $a) {
            $date = $a->created_at ?? null;
            $ref = $a->reference_no ?? $a->id;
            $amt = (float) ($a->amount ?? 0);
            $type = $a->adjustment_type ?? 'adjustment';
            $narr = ($type === 'discount' ? '(Discount) ' : '(Refund) ') . ($a->reason ?? '');

            $transactions[] = [
                'date' => $date ? Carbon::parse($date) : Carbon::now(),
                'vr_type' => ($type === 'discount' ? 'DA' : 'RE'),
                'vr_no' => $ref,
                'narration' => $narr,
                'debit' => $type === 'discount' ? $amt : 0,
                'credit' => $type === 'discount' ? 0 : $amt,
            ];
        }

        // Sort by date asc, and stable tie-breaker so FB before FR if same timestamp (use vr_type)
        $transactions = collect($transactions)->sortBy(function ($t) {
            return $t['date']->format('Y-m-d H:i:s') . '_' . ($t['vr_type'] ?? '');
        })->values();

        // Running balance calculation (start 0)
        $running = 0.0;

        // Balance brought forward row
        $rows[] = ['Balance brought forward', '', '', '', '', '', number_format($running, 0, '.', ',')];

        foreach ($transactions as $t) {
            $debit = $t['debit'] ?? 0;
            $credit = $t['credit'] ?? 0;
            $running = $running + $debit - $credit;

            // Format with thousands separator to match screenshot; keep numeric sign for negative balances
            $debitStr = $debit ? number_format($debit, 0, '.', ',') : '';
            $creditStr = $credit ? number_format($credit, 0, '.', ',') : '';
            $balanceStr = number_format($running, 0, '.', ','); // negative numbers will have leading '-'

            $rows[] = [
                $t['date']->format('d/m/Y'),
                $t['vr_type'],
                $t['vr_no'],
                $t['narration'],
                $debitStr,
                $creditStr,
                $balanceStr
            ];
        }

        // Totals row - put totals under Debit and Credit columns as in your final sample
        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');

        $rows[] = []; // blank line before totals
        $rows[] = [
            '', '', '', 'Totals',
            $totalDebit ? number_format($totalDebit, 0, '.', ',') : '',
            $totalCredit ? number_format($totalCredit, 0, '.', ',') : '',
            ''
        ];

        // Footer row (generated by + timestamp + FE0101 + page text)
        $generatedBy = auth()->check() ? auth()->user()->name : ($this->student->created_by_name ?? 'Generated');
        $footerText = $generatedBy . '  ' . Carbon::now()->format('d/m/Y  h:i:s A') . '  FE0101  Page -1 of 1';
        $rows[] = [];
        $rows[] = [$footerText, '', '', '', '', '', ''];

        return $rows;
    }

    public function title(): string
    {
        return 'Student Ledger';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold the header row (Date...Balance). Header row is at row 4 (1-based) because we added 3 rows prior
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);

        // Bold the "Balance brought forward" row (it's row 5)
        $sheet->getStyle('A5:G5')->getFont()->setBold(true);

        // Bold totals row (detect position)
        $highest = $sheet->getHighestRow();
        if ($highest > 6) {
            // totals placed 2 rows from bottom in this layout, so bold that row
            $sheet->getStyle('A' . ($highest-2) . ':G' . ($highest-2))->getFont()->setBold(true);
        }

        return [];
    }
}
