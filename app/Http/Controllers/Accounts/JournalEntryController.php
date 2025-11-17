<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\CostCenter;
use App\Models\Accounts\ProfitCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::with(['lines.accountLedger', 'branch'])
            ->latest()
            ->paginate(20);
        return view('accounts.journal_entries.index', compact('entries'));
    }

    public function create()
    {
        $ledgers = AccountLedger::where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();
        $profitCenters = ProfitCenter::where('is_active', true)->get();
        return view('accounts.journal_entries.create', compact('ledgers', 'costCenters', 'profitCenters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_ledger_id' => 'required|exists:account_ledgers,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Validate balance
            $totalDebit = collect($request->lines)->sum('debit');
            $totalCredit = collect($request->lines)->sum('credit');
            
            if (abs($totalDebit - $totalCredit) > 0.01) {
                throw new \Exception('Journal entry is not balanced. Debit: ' . $totalDebit . ', Credit: ' . $totalCredit);
            }

            // Create journal entry
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $request->entry_date,
                'reference' => $request->reference,
                'description' => $request->description,
                'status' => 'draft',
                'entry_type' => $request->entry_type ?? 'journal_voucher',
                'branch_id' => auth()->user()->branch_id,
                'created_by' => auth()->id(),
            ]);

            // Create journal lines
            foreach ($request->lines as $line) {
                if ($line['debit'] > 0 || $line['credit'] > 0) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_ledger_id' => $line['account_ledger_id'],
                        'description' => $line['description'] ?? '',
                        'debit' => $line['debit'],
                        'credit' => $line['credit'],
                        'cost_center_id' => $line['cost_center_id'] ?? null,
                        'profit_center_id' => $line['profit_center_id'] ?? null,
                    ]);
                }
            }

            // Auto-post if requested
            if ($request->auto_post) {
                $entry->post();
            }

            DB::commit();
            return redirect()->route('accounts.journal.index')->with('success', 'Journal entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $entry = JournalEntry::with(['lines.accountLedger', 'branch'])->findOrFail($id);
        return view('accounts.journal_entries.show', compact('entry'));
    }

    public function edit($id)
    {
        $entry = JournalEntry::with('lines')->findOrFail($id);
        
        if ($entry->status != 'draft') {
            return back()->withErrors(['error' => 'Only draft entries can be edited']);
        }

        $ledgers = AccountLedger::where('is_active', true)->get();
        $costCenters = CostCenter::where('is_active', true)->get();
        $profitCenters = ProfitCenter::where('is_active', true)->get();
        
        return view('accounts.journal_entries.edit', compact('entry', 'ledgers', 'costCenters', 'profitCenters'));
    }

    public function update(Request $request, $id)
    {
        $entry = JournalEntry::findOrFail($id);
        
        if ($entry->status != 'draft') {
            return back()->withErrors(['error' => 'Only draft entries can be updated']);
        }

        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'lines' => 'required|array|min:2',
        ]);

        DB::beginTransaction();
        try {
            // Validate balance
            $totalDebit = collect($request->lines)->sum('debit');
            $totalCredit = collect($request->lines)->sum('credit');
            
            if (abs($totalDebit - $totalCredit) > 0.01) {
                throw new \Exception('Journal entry is not balanced');
            }

            // Update entry
            $entry->update([
                'entry_date' => $request->entry_date,
                'reference' => $request->reference,
                'description' => $request->description,
                'updated_by' => auth()->id(),
            ]);

            // Delete old lines and create new ones
            $entry->lines()->delete();
            foreach ($request->lines as $line) {
                if ($line['debit'] > 0 || $line['credit'] > 0) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_ledger_id' => $line['account_ledger_id'],
                        'description' => $line['description'] ?? '',
                        'debit' => $line['debit'],
                        'credit' => $line['credit'],
                        'cost_center_id' => $line['cost_center_id'] ?? null,
                        'profit_center_id' => $line['profit_center_id'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('accounts.journal.index')->with('success', 'Journal entry updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $entry = JournalEntry::findOrFail($id);
        
        if ($entry->status == 'posted') {
            return back()->withErrors(['error' => 'Posted entries cannot be deleted']);
        }

        $entry->delete();
        return redirect()->route('accounts.journal.index')->with('success', 'Journal entry deleted successfully');
    }

    public function approve(Request $request, $id)
    {
        $entry = JournalEntry::findOrFail($id);
        
        if ($entry->status != 'draft') {
            return back()->withErrors(['error' => 'Only draft entries can be posted']);
        }

        DB::beginTransaction();
        try {
            $entry->post();
            DB::commit();
            return redirect()->route('accounts.journal.index')->with('success', 'Journal entry posted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
