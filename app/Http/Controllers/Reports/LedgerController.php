<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Student\Students;
use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountLedger;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\JournalEntryLine;

class LedgerController extends Controller
{
    public function coaListing(Request $request)
    {
        // Placeholder for chart of accounts listing
        return response()->json(['success' => true, 'data' => []]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $ledger = AccountLedger::findOrFail($id);
        $ledger->is_active = !$ledger->is_active;
        $ledger->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
     public function ReportLedgerShow()
    {
            // Load all ledgers for dropdown
        $availableLedgers = AccountLedger::orderBy('name')->get(['id','name']);


        $selectedAccount = null;

       return view('accounts.report_ledger.index', compact('availableLedgers', 'selectedAccount'));
     }

    public function reportLedgerData(Request $request)
    {
        // Validate optional filters (same as you already had)
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'account_ledger_id' => 'nullable|integer|exists:account_ledgers,id',
        ]);

        $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate   = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $lineModel = new JournalEntryLine();
        $lineTable = $lineModel->getTable();
        $entryModel = $lineModel->journalEntry()->getModel();
        $entryTable = $entryModel->getTable();

        $candidates = [
            'transaction_date','date','entry_date','journal_date',
            'posted_at','posting_date','created_at','created'
        ];

        $lineColumns = Schema::getColumnListing($lineTable);
        $lineDateColumn = null;
        foreach ($candidates as $col) {
            if (in_array($col, $lineColumns)) { $lineDateColumn = $col; break; }
        }

        $entryColumns = Schema::getColumnListing($entryTable);
        $entryDateColumn = null;
        if (! $lineDateColumn) {
            foreach ($candidates as $col) {
                if (in_array($col, $entryColumns)) { $entryDateColumn = $col; break; }
            }
        }

        // Build base query builder (do NOT call ->get() yet)
        $query = JournalEntryLine::with(['accountLedger', 'journalEntry'])->select("{$lineTable}.*");

        // Date filtering: include rows where either the line OR parent entry has a date in range
        if ($startDate && $endDate) {
            $query->where(function($q) use ($lineDateColumn, $entryDateColumn, $startDate, $endDate) {
                if ($lineDateColumn) {
                    $q->orWhereBetween($lineDateColumn, [$startDate, $endDate]);
                }
                if ($entryDateColumn) {
                    $q->orWhereHas('journalEntry', function($qq) use ($entryDateColumn, $startDate, $endDate) {
                        $qq->whereBetween($entryDateColumn, [$startDate, $endDate]);
                    });
                }
                // fallback to created_at if present
                $q->orWhereBetween('created_at', [$startDate, $endDate]);
            });
        }

        // Account filter: support ledger on line OR on parent journal entry
        if ($request->filled('account_ledger_id')) {
            $ledgerId = $request->input('account_ledger_id');
            $query->where(function($q) use ($ledgerId) {
                $q->where('account_ledger_id', $ledgerId)
                ->orWhereHas('journalEntry', function($qq) use ($ledgerId) {
                    $qq->where('account_ledger_id', $ledgerId);
                });
            });
        }

        // -----------------------
        // Totals endpoint (called by front-end with _totals=1)
        // -----------------------
        if ($request->filled('_totals')) {
            // clone query to avoid modifying original
            $totalsQuery = (clone $query)
                ->selectRaw('COALESCE(SUM(debit),0) AS debit_sum, COALESCE(SUM(credit),0) AS credit_sum')
                ->first();

            return response()->json([
                'totals' => [
                    'debit'  => (float) ($totalsQuery->debit_sum ?? 0),
                    'credit' => (float) ($totalsQuery->credit_sum ?? 0),
                ]
            ]);
        }
        // -----------------------
        // CSV export (called with _export=csv)
        // -----------------------
        if ($request->input('_export') === 'csv') {
            // order results by date (prefer line date, else entry date, else created_at)
            $orderBy = $lineDateColumn ?? $entryDateColumn ?? 'created_at';

            // Clone query to compute totals (avoid affecting original query)
            $totals = (clone $query)
                ->selectRaw('COALESCE(SUM(debit),0) AS debit_sum, COALESCE(SUM(credit),0) AS credit_sum')
                ->first();

            // Fetch rows (ordered)
            $rows = (clone $query)->orderBy($orderBy, 'asc')->get();

            $filename = 'ledger_export_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ];

            $callback = function() use ($rows, $lineDateColumn, $entryDateColumn, $totals) {
                $handle = fopen('php://output', 'w');

                // header row
                fputcsv($handle, ['Date', 'Journal Ref', 'Account', 'Description', 'Debit', 'Credit']);

                foreach ($rows as $r) {
                    // determine date value (line date preferred)
                    $d = null;
                    if ($lineDateColumn && isset($r->{$lineDateColumn})) $d = $r->{$lineDateColumn};
                    elseif ($entryDateColumn && isset($r->journalEntry) && isset($r->journalEntry->{$entryDateColumn})) $d = $r->journalEntry->{$entryDateColumn};
                    elseif (isset($r->created_at)) $d = $r->created_at;
                    $dateString = $d ? \Carbon\Carbon::parse($d)->toDateString() : '';

                    $journalRef = optional($r->journalEntry)->reference ?? '';
                    $accountName = optional($r->accountLedger)->name ?? '';
                    $desc = $r->description ?? $r->narration ?? '';

                    // ensure numbers are plain floats (no thousands separators) with 2 decimals
                    $debit = number_format($r->debit ?? 0, 2, '.', '');
                    $credit = number_format($r->credit ?? 0, 2, '.', '');

                    fputcsv($handle, [$dateString, $journalRef, $accountName, $desc, $debit, $credit]);
                }

                // write an empty separator row (optional)
                fputcsv($handle, []);

                // write totals row label + totals (aligns with columns: Date, Journal Ref, Account, Description, Debit, Credit)
                $totalDebit = number_format((float) ($totals->debit_sum ?? 0), 2, '.', '');
                $totalCredit = number_format((float) ($totals->credit_sum ?? 0), 2, '.', '');

                // You can change the "GRAND TOTALS" label as you like
                fputcsv($handle, ['', '', '', 'GRAND TOTALS', $totalDebit, $totalCredit]);

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }


        // -----------------------
        // Default: return DataTables JSON (your existing behavior)
        // -----------------------
        return DataTables::of($query)
            ->addColumn('date_display', function ($row) use ($lineDateColumn, $entryDateColumn) {
                $d = null;
                if ($lineDateColumn && isset($row->{$lineDateColumn})) $d = $row->{$lineDateColumn};
                elseif ($entryDateColumn && isset($row->journalEntry) && isset($row->journalEntry->{$entryDateColumn})) $d = $row->journalEntry->{$entryDateColumn};
                elseif (isset($row->created_at)) $d = $row->created_at;
                return $d ? \Carbon\Carbon::parse($d)->toDateString() : '-';
            })
            ->addColumn('account_name', function ($row) {
                return optional($row->accountLedger)->name ?? '-';
            })
            ->addColumn('journal_ref', function ($row) {
                return optional($row->journalEntry)->reference ?? '-';
            })
            ->editColumn('debit', function ($row) {
                return number_format($row->debit ?? 0, 2);
            })
            ->editColumn('credit', function ($row) {
                return number_format($row->credit ?? 0, 2);
            })
            ->rawColumns(['date_display','account_name','journal_ref'])
            ->make(true);
    }

 }
