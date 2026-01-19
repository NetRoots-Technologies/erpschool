<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Admin\Branches;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Fee\FeeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryReportController extends Controller
{
    public function journalVoucher(Request $request)
    {
        if (!Gate::allows('inventory-list')) {
            return abort(503);
        }

        if ($request->ajax()) {
            $branchId = $request->branch_id;
            $voucherDate = $request->voucher_date;
            $voucherNo = $request->voucher_no;
            $remarks = $request->remarks;
            $process = $request->process;
            $processFrom = $request->process_from;
            $processTo = $request->process_to;

            $feeBillingIds = collect();
            if (!empty($branchId)) {
                $feeBillingIds = FeeBilling::whereHas('student', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })->pluck('id');
            }

            $linesQuery = JournalEntryLine::with([
                'journalEntry',
                'accountLedger.accountGroup',
                'costCenter',
                'profitCenter',
            ])->whereHas('journalEntry', function ($q) use (
                $branchId,
                $voucherDate,
                $voucherNo,
                $remarks,
                $process,
                $processFrom,
                $processTo,
                $feeBillingIds
            ) {
                $q->where('entry_type', 'journal_voucher');

                if (!empty($branchId)) {
                    $q->where(function ($qb) use ($branchId, $feeBillingIds) {
                        $qb->where('branch_id', $branchId);

                        if ($feeBillingIds->isNotEmpty()) {
                            $qb->orWhere(function ($q2) use ($feeBillingIds) {
                                $q2->where('source_module', 'fee_billing')
                                    ->whereIn('source_id', $feeBillingIds);
                            });
                        }
                    });
                }

                if (!empty($voucherDate)) {
                    $q->whereDate('entry_date', $voucherDate);
                }

                if (!empty($voucherNo)) {
                    $q->where('entry_number', 'like', '%' . $voucherNo . '%');
                }

                if (!empty($remarks)) {
                    $q->where('description', 'like', '%' . $remarks . '%');
                }

                if (!empty($process)) {
                    $q->where('source_module', $process);
                }

                if (!empty($processFrom) && !empty($processTo)) {
                    $q->whereBetween('entry_date', [$processFrom, $processTo]);
                } elseif (!empty($processFrom)) {
                    $q->whereDate('entry_date', '>=', $processFrom);
                } elseif (!empty($processTo)) {
                    $q->whereDate('entry_date', '<=', $processTo);
                }
            });

            $lines = $linesQuery
                ->orderBy('journal_entry_id')
                ->orderBy('id')
                ->get();

            $data = $lines->map(function ($line, $index) {
                $entry = $line->journalEntry;
                $ledger = $line->accountLedger;
                $group = $ledger ? $ledger->accountGroup : null;

                return [
                    'sr' => $index + 1,
                    'nominal_ac' => $group ? trim(($group->code ?? '') . ' ' . ($group->name ?? '')) : '-',
                    'sub_ac' => $ledger ? trim(($ledger->code ?? '') . ' ' . ($ledger->name ?? '')) : '-',
                    'description' => $line->description ?? ($ledger->name ?? '-'),
                    'narration' => $entry->description ?? $entry->reference ?? '-',
                    'debit' => (float) ($line->debit ?? 0),
                    'credit' => (float) ($line->credit ?? 0),
                    'budget_id' => '-',
                    'acti_id' => $line->profitCenter ? ($line->profitCenter->name ?? $line->profit_center_id) : '-',
                    'cost' => $line->costCenter ? ($line->costCenter->name ?? $line->cost_center_id) : '-',
                    'iba' => '-',
                    'loc_id' => $entry->branch_id ?? '-',
                    'tax_id' => '-',
                    'party_id' => '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'totals' => [
                    'debit' => $lines->sum('debit'),
                    'credit' => $lines->sum('credit'),
                ],
            ]);
        }

        $branches = Branches::active()->get(['id', 'name']);
        $processes = JournalEntry::where('entry_type', 'journal_voucher')
            ->whereNotNull('source_module')
            ->where('source_module', '!=', '')
            ->distinct()
            ->orderBy('source_module')
            ->pluck('source_module');

        return view('admin.inventory_management.reports.journal_voucher', compact('branches', 'processes'));
    }
}
