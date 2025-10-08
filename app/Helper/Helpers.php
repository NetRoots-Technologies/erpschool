<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;
use App\Models\Accounts\JournalEntry;
use App\Models\Accounts\JournalEntryLine;
use App\Models\HRM\Employees;
use App\Models\Admin\Currencies;
use App\Models\Student\Students;
use Illuminate\Support\Facades\Auth;
use App\Models\HR\CalculateComission;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Helpers
{
    /**
     * Create ledger using new accounts system
     */
    public static function create_ledger($data)
    {
        try {
            return AccountLedger::create([
                'name' => $data['name'],
                'code' => $data['code'] ?? 'AUTO-' . time(),
                'account_group_id' => $data['group_id'] ?? $data['account_group_id'] ?? 1,
                'opening_balance' => $data['opening_balance'] ?? 0,
                'opening_balance_type' => $data['balance_type'] ?? 'debit',
                'current_balance' => $data['opening_balance'] ?? 0,
                'current_balance_type' => $data['balance_type'] ?? 'debit',
                'is_active' => true,
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Helpers::create_ledger failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get debit sum for journal entry
     */
    public static function debit_amount_sum($id)
    {
        return JournalEntryLine::where('journal_entry_id', $id)->sum('debit');
    }

    /**
     * Get credit sum for journal entry
     */
    public static function credit_amount_sum($id)
    {
        return JournalEntryLine::where('journal_entry_id', $id)->sum('credit');
    }

    /**
     * Create journal entry using new accounts system
     */
    public static function create_entry($data)
    {
        try {
            return JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date' => $data['voucher_date'] ?? now(),
                'reference' => $data['reference'] ?? '',
                'description' => $data['narration'] ?? 'Auto-generated entry',
                'status' => 'posted',
                'entry_type' => $data['entry_type'] ?? 'journal',
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id ?? null,
                'posted_at' => now(),
                'posted_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Helpers::create_entry failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create entry item (journal line) using new accounts system
     */
    public static function create_entry_item($data)
    {
        try {
            return JournalEntryLine::create([
                'journal_entry_id' => $data['entry_id'] ?? $data['journal_entry_id'],
                'account_ledger_id' => $data['ledger_id'] ?? $data['account_ledger_id'],
                'description' => $data['narration'] ?? '',
                'debit' => $data['dc'] == 'd' ? $data['amount'] : 0,
                'credit' => $data['dc'] == 'c' ? $data['amount'] : 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Helpers::create_entry_item failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get ledger by ID
     */
    public static function get_ledger($id)
    {
        return AccountLedger::find($id);
    }

    /**
     * Get ledger balance
     */
    public static function get_ledger_balance($id)
    {
        $ledger = AccountLedger::find($id);
        return $ledger ? $ledger->current_balance : 0;
    }

    /**
     * Update ledger balance
     */
    public static function update_ledger_balance($ledgerId, $debit, $credit)
    {
        $ledger = AccountLedger::find($ledgerId);
        if ($ledger) {
            $ledger->updateBalance($debit, $credit);
            return true;
        }
        return false;
    }

    public static function getStudentCounts()
    {
        // Fee module removed - this function needs to be updated
        return 0;
    }

    public static function getEmployeeCount()
    {

        return Employees::count();


    }

    public static function getStudent()
    {

        return Students::all()->count();

    }

    public static function getAgentCounts()
    {

        return \App\Models\HR\Agent::all()->count();

    }

    public static function getSessionCounts()
    {
        return \App\Models\Admin\Session::all()->count();
    }

    public static function moreThan30k()
    {
        // Fee module removed - this function needs to be updated
        return 0;
    }

    public static function agentsStudent()
    {

        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->agent_id;
            $data = Students::where('agent_id', $id)->count();
            return $data;
        }

    }

    public static function agentsComission()
    {

        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->agent_id;
            $data = CalculateComission::where('agent_id', $id)->where('slab_type', '=', 'Comission')->sum('total_comission');
            return $data;
        }

    }

    public static function agentsRecovery()
    {

        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->agent_id;
            $data = CalculateComission::where('agent_id', $id)->where('slab_type', '=', 'Recovery')->sum('total_comission');
            return $data;
        }

    }

    public static function agentstotalComission()
    {

        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->agent_id;
            $data = CalculateComission::where('agent_id', $id)->sum('total_comission');
            return $data;
        }

    }

    public static function agentsRecentStudent()
    {

        $user = auth()->user();
        if ($user->role_id == 2) {
            $id = $user->agent_id;
            $student_names = \App\Models\Student\Students::where('agent_id', $id)->latest()->limit(5)->get();
            return $student_names;
        }

    }

    public static function expense_summary_report_daily()
    {


        $expData = '';
        $decimal = 0;
        $currencyID = 0;
        $texp_balance = 0;
        $type = "";
        $end_date = date('Y-m-d');
        $start_date = Carbon::yesterday()->format('Y-m-d');


        $decimal = 2; // Default decimal places
        //fetch all expenses groups - Skip for now
        $GroupExp = collect();

        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $expData .= '<tr>';
            $expData .= '<td colspan="2"><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $expData .= '</tr>    ';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            if (count($Ledgers) > 0)

                foreach ($Ledgers as $Ledger) {

                    //                echo $ob=CoreAccounts::opening_balance($start_date, 0, 54, $currencyID);
                    $closing_balance = \App\Helpers\CoreAccounts::closing_balance1($Ledger->id, 1, $end_date, $end_date);
                    foreach ($closing_balance as $cb) {
                        $conRate = Currencies::where('code', $cb[0])->first('rate');
                        $expData .= '<tr style="text-align: center">';
                        $expData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                        $expData .= '<td align="right">' . number_format(($cb[4] * $conRate->rate), $decimal) . '</td>';
                        $expData .= '</tr>';
                        $texp_balance += ($cb[4] * $conRate->rate);
                    }
                }
        }


        return $texp_balance;
        //

    }

    public static function expense_summary_report_monthly()
    {


        $expData = '';
        $decimal = 0;
        $currencyID = 0;
        $texp_balance = 0;
        $type = "";
        //        $start_date = date('Y-m-d');
//        $previousMonth = Carbon::now()->subMonth()->format('Y-m-d');
//
//        $currentMonth = Carbon::now()->month;
//        $daysInMonth = Carbon::now()->daysInMonth;
//
//        $dates = [];
//
//        for ($i = 1; $i <= $daysInMonth; $i++) {
//            $date = Carbon::createFromDate(Carbon::now()->year, $currentMonth, $i);
//            $dates[] = $date;
//
//        }

        $first_date = date('Y-m-d', strtotime('first day of this month'));
        $last_date = date('Y-m-d', strtotime('today'));




        $decimal = 2; // Default decimal places
        //fetch all expenses groups - Skip for now
        $GroupExp = collect();

        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $expData .= '<tr>';
            $expData .= '<td colspan="2"><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $expData .= '</tr>    ';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            if (count($Ledgers) > 0)

                foreach ($Ledgers as $Ledger) {

                    //                echo $ob=CoreAccounts::opening_balance($start_date, 0, 54, $currencyID);
                    $closing_balance = \App\Helpers\CoreAccounts::closing_balance1($Ledger->id, 1, $first_date, $last_date);

                    foreach ($closing_balance as $cb) {
                        $conRate = Currencies::where('code', $cb[0])->first('rate');
                        $expData .= '<tr style="text-align: center">';
                        $expData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                        $expData .= '<td align="right">' . number_format(($cb[4] * $conRate->rate), $decimal) . '</td>';
                        $expData .= '</tr>';
                        $texp_balance += ($cb[4] * $conRate->rate);
                    }

                }

        }


        return $texp_balance;
        //

    }

    /**
     * Calculate depreciation.
     *
     * @param float $amount
     * @param string $purchaseDate
     * @param float $depreciationPercentage
     * @return float
     */
    public static function calculateSLDepreciation($amount, $purchaseDate, $depreciationPercentage)
    {
        $amount = (float) str_replace(',', '', $amount);
        $depreciationPercentage = (float) str_replace('%', '', $depreciationPercentage);

        $purchaseDate = Carbon::parse($purchaseDate);
        $currentDate = Carbon::now();
        $yearsDifference = $purchaseDate->diffInDays($currentDate) / 365;

        $annualDepreciation = $amount * ($depreciationPercentage / 100);
        $remaining_value = $amount - ($annualDepreciation * $yearsDifference);

        return number_format(max(0, $remaining_value), 3, '.', '');
    }

    public static function calculateDBDepreciation($amount, $purchaseDate, $depreciationPercentage)
    {
        $amount = (float) str_replace(',', '', $amount);
        $depreciationPercentage = (float) str_replace('%', '', $depreciationPercentage);

        $purchaseDate = Carbon::parse($purchaseDate);
        $currentDate = Carbon::now();
        $yearsDifference = $purchaseDate->diffInDays($currentDate) / 365;

        $depreciationFactor = 1 - ($depreciationPercentage / 100);
        $remaining_value = $amount * pow($depreciationFactor, $yearsDifference);

        return number_format(max(0, $remaining_value), 3, '.', '');
    }




    public static function makeAbbreviation(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z\s]/', '', $name);

        $name = preg_replace('/\s+/', ' ', trim($name));

        $words = explode(' ', $name);

        $abbreviation = array_reduce($words, function ($carry, $word) {
            return $carry . Str::upper(Str::substr($word, 0, 1));
        }, '');

        if (Str::length($abbreviation) < 3) {
            $firstWord = Str::upper($words[0] ?? '');
            $abbreviation .= Str::substr($firstWord, Str::length($abbreviation), 3 - Str::length($abbreviation));
        }

        return Str::substr($abbreviation, 0, 3);
    }

    public static function transformDate($value)
    {
        // Check if it's a valid Excel date (number)
        if (is_numeric($value)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }


    
   public static function parentId()
    {
        if (Auth::user()->type == 'owner' || Auth::user()->type == 'super admin') {
            return Auth::user()->id;
        } else {
            return Auth::user()->id;
        }
    }


    // Keep other non-accounting helper methods below
    // (Add any other utility methods that don't relate to accounts)
}