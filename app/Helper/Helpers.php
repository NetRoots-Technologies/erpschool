<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helper\CoreAccounts;
use App\Models\Admin\Groups;
use App\Models\Admin\Entries;
use App\Models\Admin\Ledgers;
use App\Models\HRM\Employees;
use App\Models\Account\Ledger;
use App\Models\Fee\StudentFee;
use App\Models\Admin\Currencies;
use App\Models\Admin\EntryItems;
use App\Models\Student\Students;


use App\Models\Fee\PaidStudentFee;
use Illuminate\Support\Facades\Auth;
use App\Models\HR\CalculateComission;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Helpers
{
    public static function create_ledger($data)
    {

        //        try {

        $ledger = new Ledger();
        $ledger->name = $data['name'];
        //        $ledger->number = $data['number'];
        $ledger->parent_type = $data['parent_type'];
        //        $ledger->branch_id = $data['branch_id'];
//        $ledger->code = $data['code'];
        $ledger->group_id = $data['group_id'];
        //        $ledger->group_number = $data['group_number'];
        $ledger->opening_balance = 0;
        $ledger->closing_balance = 0;
        $ledger->balance_type = 'd';
        $ledger->save();
        return $ledger;
        //        } catch (\Exception $e) {

        //            return false;
//        }

    }

    public static function debit_amount_sum($id)
    {
        return EntryItems::where('entry_id', $id)->where('dc', 'd')->sum('amount');
    }

    public static function credit_amount_sum($id)
    {
        return EntryItems::where('entry_id', $id)->where('dc', 'c')->sum('amount');

    }


    public static function create_entry($data)
    {

        $data1['voucher_date'] = date('Y-m-d');
        $data1['created_by'] = Auth::user()->id;
        $data1['updated_by'] = Auth::user()->id;
        $data1['currence_type'] = 1;

        //        $data1['employee_id'] = Auth::user()->id;
//        $data['branch_id'] = $data['branch_id'];
        $data1['narration'] = $data['narration'];
        $data1['entry_type_id'] = $data['entry_type_id'];

        //        try {

        $entry = Entries::create($data1);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));

        return $entry->id;

        //        } catch (\Exception $e) {

        //            return $e->getMessage();
//        }

    }

    public static function create_entry_item($data)
    {
        $EntItem['entry_id'] = $data['entry_id'];
        $EntItem['voucher_date'] = date('Y-m-d');
        $EntItem['amount'] = $data['amount'];
        $EntItem['currence_type'] = 1;
        $EntItem['narration'] = 'student fee ' . $data['narration'];
        $EntItem['dc'] = $data['dc'];
        $EntItem['ledger_id'] = $data['ledger_id'];
        //        try {

        $entry_item = EntryItems::insert($EntItem);

        //        } catch (\Exception $e) {

        //            return $e->getMessage();
//        }


    }


    public static function get_ledger($group_id, $parent_type = null)
    {
        return Ledgers::where('group_id', $group_id)->where('parent_type', $parent_type)->first();
    }

    public static function get_group($parent_id, $parent_type_id)
    {
        return Groups::where('parent_id', $parent_id)->where('parent_type', $parent_type_id)->first();

    }

    public static function create_group($parent_id, $parent_type_id, $data)
    {
        $g = new Groups();
        $g->name = $data['name'];
        $g->number = $data['number'];
        $g->code = $data['code'];
        $g->level = $data['level'];
        $g->parent_id = $parent_id;
        $g->parent_type = $parent_type_id;
        $g->save();

    }

    public static function getStudentCounts()
    {

        return StudentFee::count('student_id');


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
        //        return StudentFee::where('total_paid_fee', '>=', 30000)->count();

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


        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        //fetch all expenses groups
        $GroupExp = Groups::where('account_type_id', 3)->orderBy('code')->get();

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




        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        //fetch all expenses groups
        $GroupExp = Groups::where('account_type_id', 3)->OrderBy('code')->get();

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

}
