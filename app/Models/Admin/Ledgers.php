<?php

namespace App\Models\Admin;

use Auth;
use App\Models\Admin\Groups;
use App\Helpers\CoreAccounts;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledgers extends Model
{
    protected $guarded = ['id'];

    protected $table = 'ledgers';

    // protected $timestamps = true;

    public function groups()
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    static function getParent($id)
    {
        $prefix = '';
        $result = Groups::where('id', $id)->first();
        if (count($result) > 0) {
            if ($result->parent_id == Config::get('constants.account_bank_balance_local')) {
                $prefix = 'PKR';
            } else if ($result->parent_id == Config::get('constants.account_bank_balance_us')) {
                $prefix = '(US$) ';
            } else {
                $prefix = '(EURO €) ';
            }
        }
        return $prefix;
    }

    static function getAllParent($id)
    {
        $prefix = 0;
        $ledgerid = $id;
        for ($i = 0; $i <= 2; $i++) {
            $result = Groups::where('id', $id)->first();
            if ($result) {
                $id = $result->parent_id;
                $Main_id = $result->id;
            } else {

            }

        }
        if ($Main_id == Config::get('constants.account_bank_balance') && $id == '134') {
            $groupname = self::getParent($ledgerid);
        } elseif ($id == Config::get('constants.accounts_sale_revenue')) {
            $groupname = $id;
        } elseif ($id == Config::get('constants.accounts_cost_sale')) {
            $groupname = '(COGS) ';
        } elseif ($id == Config::get('constants.accounts_cost_sale')) {
            $groupname = '(COGS) ';
        } else {
            $groupname = '0';
        }
        return $groupname;
    }

    static function openingBalance($id, $start_date = null, $filter = array())
    {

        /* Load models that are needed for calculations */
        $Entry = new EntryTypes();
        $Entryitem = new EntryItems();

        /* Opening balance */
        $op = self::find($id)->toArray();

        $op_total = 0;
        if (empty($op['opening_balance'])) {
            $op_total = 0;
        } else {
            $op_total = $op['opening_balance'];
        }
        $op_total_dc = $op['balance_type'];

        /* If start date is not specified then return here */
        if (is_null($start_date)) {
            return array('dc' => $op_total_dc, 'amount' => $op_total);
        }

        $where = array(
            'entry_items.status' => 1,
            'entry_items.ledger_id' => $op['id'],
        );

        // Set Branch ID if exists
        if (isset($filter['branch_id']) && $filter['branch_id']) {
            $where['entries.branch_id'] = $filter['branch_id'];
        }
        // Set Employee ID if exists
        if (isset($filter['employee_id']) && $filter['employee_id']) {
            $where['entries.employee_id'] = $filter['employee_id'];
        }
        // Set Department ID if exists
        if (isset($filter['department_id']) && $filter['department_id']) {
            $where['entries.department_id'] = $filter['department_id'];
        }
        // Set Entry Type ID if exists
        if (isset($filter['entry_type_id']) && $filter['entry_type_id']) {
            $where['entries.entry_type_id'] = $filter['entry_type_id'];
        }

        $where['entry_items.dc'] = 'd';
        $dr_total = EntryItems::join('entries', 'entries.id', '=', 'entry_items.entry_id')
            ->where($where)
            ->where('entry_items.voucher_date', '<', $start_date)
            ->sum('entry_items.amount');

        $where['entry_items.dc'] = 'c';
        $cr_total = EntryItems::join('entries', 'entries.id', '=', 'entry_items.entry_id')
            ->where($where)
            ->where('entry_items.voucher_date', '<', $start_date)
            ->sum('entry_items.amount');

        /* Add opening balance */
        if ($op_total_dc == 'd') {
            $dr_total_final = CoreAccounts::calculate($op_total, $dr_total, '+');
            $cr_total_final = $cr_total;
        } else {
            $dr_total_final = $dr_total;
            $cr_total_final = CoreAccounts::calculate($op_total, $cr_total, '+');
        }
        // echo '<pre>';print_r($cr_total_final);echo'</pre>';exit;
        /* Calculate final opening balance */
        if (CoreAccounts::calculate($dr_total_final, $cr_total_final, '>')) {
            $op_total = CoreAccounts::calculate($dr_total_final, $cr_total_final, '-');
            $op_total_dc = 'd';
        } else if (CoreAccounts::calculate($dr_total_final, $cr_total_final, '==')) {
            $op_total = 0;
            $op_total_dc = $op_total_dc;
        } else {
            $op_total = CoreAccounts::calculate($cr_total_final, $dr_total_final, '-');
            $op_total_dc = 'c';
        }

        return array('dc' => $op_total_dc, 'amount' => $op_total);
    }

    /**
     * Calculate closing balance of specified ledger account for the given
     * date range
     *
     * @param1 int ledger id
     * @param2 date start date
     * @param3 date end date
     * @return array D/C, Amount
     */
    static function closingBalance($id, $start_date = null, $end_date = null, $filter = array())
    {
        /* Opening balance */
        $op = self::find($id)->toArray();
        $op_total = 0;
        $op_total_dc = $op['balance_type'];
        if (is_null($start_date)) {
            if (empty($op['opening_balance'])) {
                $op_total = 0;
            } else {
                $op_total = $op['opening_balance'];
            }
            //echo '<pre>';print_r($op_total);echo '</pre>';exit;
        }

        $dr_total = 0;
        $cr_total = 0;
        $dr_total_dc = 0;
        $cr_total_dc = 0;

        $where = array(
            'entry_items.status' => 1,
            'entry_items.ledger_id' => $op['id'],
        );

        // Set Branch ID if exists
        if (isset($filter['branch_id']) && $filter['branch_id']) {
            $where['entries.branch_id'] = $filter['branch_id'];
        }
        // Set Employee ID if exists
        if (isset($filter['employee_id']) && $filter['employee_id']) {
            $where['entries.employee_id'] = $filter['employee_id'];
        }
        // Set Department ID if exists
        if (isset($filter['department_id']) && $filter['department_id']) {
            $where['entries.department_id'] = $filter['department_id'];
        }
        // Set Entry Type ID if exists
        if (isset($filter['entry_type_id']) && $filter['entry_type_id']) {
            $where['entries.entry_type_id'] = $filter['entry_type_id'];
        }


        $where['entry_items.dc'] = 'd';

        $query = EntryItems::join('entries', 'entries.id', '=', 'entry_items.entry_id')
            ->where($where);

        if (!is_null($start_date) && $start_date) {
            $query->where('entry_items.voucher_date', '>=', $start_date);
        }
        if (!is_null($end_date) && $end_date) {
            $query->where('entry_items.voucher_date', '<=', $end_date);
        }

        $dr_total = $query->sum('entry_items.amount');

        // echo '<pre>';print_r($op_total);echo '</pre>';exit;
        $where['entry_items.dc'] = 'c';
        $query = EntryItems::join('entries', 'entries.id', '=', 'entry_items.entry_id')
            ->where($where);
        if (!is_null($start_date) && $start_date) {
            $query->where('entry_items.voucher_date', '>=', $start_date);
        }
        if (!is_null($end_date) && $end_date) {
            $query->where('entry_items.voucher_date', '<=', $end_date);
        }
        $cr_total = $query->sum('entry_items.amount');
        //echo '<pre>';print_r($cr_total);echo '</pre>';exit;

        //        if (!is_null($start_date) && !is_null($end_date)) {
//            $where['entry_items.dc'] = 'd';
//            $dr_total = EntryItems::join('entries','entries.id','=', 'entry_items.entry_id')
//                ->where($where)
//                ->whereBetween('entry_items.voucher_date', array($start_date, $end_date))
//                ->sum('entry_items.amount');
//
//            $where['entry_items.dc'] = 'c';
//            $cr_total = EntryItems::join('entries','entries.id','=', 'entry_items.entry_id')
//                ->where($where)
//                ->whereBetween('entry_items.voucher_date', array($start_date, $end_date))
//                ->sum('entry_items.amount');
//        } else {
//            $where['entry_items.dc'] = 'd';
//            $dr_total = EntryItems::join('entries','entries.id','=', 'entry_items.entry_id')
//                ->where($where)
//                ->sum('entry_items.amount');
//
//            $where['entry_items.dc'] = 'c';
//            $cr_total = EntryItems::join('entries','entries.id','=', 'entry_items.entry_id')
//                ->where($where)
//                ->sum('entry_items.amount');
//        }

        /* Add opening balance */
        if ($op_total_dc == 'd') {
            $dr_total_dc = CoreAccounts::calculate($op_total, $dr_total, '+');
            $cr_total_dc = $cr_total;
        } else {
            $dr_total_dc = $dr_total;
            $cr_total_dc = CoreAccounts::calculate($op_total, $cr_total, '+');
        }
        //echo '<pre>';print_r($op_total);echo '</pre>';exit;
        /* Calculate and update closing balance */
        $cl = 0;
        $cl_dc = '';
        if (CoreAccounts::calculate($dr_total_dc, $cr_total_dc, '>')) {
            $cl = CoreAccounts::calculate($dr_total_dc, $cr_total_dc, '-');
            $cl_dc = 'd';
        } else if (CoreAccounts::calculate($cr_total_dc, $dr_total_dc, '==')) {
            $cl = 0;
            $cl_dc = $op_total_dc;
        } else {
            $cl = CoreAccounts::calculate($cr_total_dc, $dr_total_dc, '-');
            $cl_dc = 'c';
        }

        return array('dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total);
    }

    /* Calculate difference in opening balance */
    static function getOpeningDiff()
    {
        $total_op = 0;
        $ledgers = self::where('status', 1)->get();
        foreach ($ledgers as $row => $ledger) {
            if ($ledger->balance_type == 'd') {
                $total_op = CoreAccounts::calculate($total_op, $ledger->opening_balance, '+');
            } else {
                $total_op = CoreAccounts::calculate($total_op, $ledger->opening_balance, '-');
            }
        }

        /* Dr is more ==> $total_op >= 0 ==> balancing figure is Cr */
        if (CoreAccounts::calculate($total_op, 0, '>=')) {
            return array('opdiff_balance_dc' => 'c', 'opdiff_balance' => $total_op);
        } else {
            return array('opdiff_balance_dc' => 'd', 'opdiff_balance' => CoreAccounts::calculate($total_op, 0, 'n'));
        }
    }

    /**
     * Show Entry Ledgers
     *
     * @param {$id} Ledger ID
     *
     * @return {string} $ledgerstr
     */
    static function entryLedgers($id)
    {
        $rawentryitems = EntryItems::where(['entry_id' => $id, 'status' => 0])->get();
        /* Get dr and cr ledger id and count */
        $dr_count = 0;
        $cr_count = 0;
        $dr_ledger_id = '';
        $cr_ledger_id = '';
        foreach ($rawentryitems as $row => $entryitem) {
            if ($entryitem['dc'] == 'd') {
                $dr_ledger_id = $entryitem['ledger_id'];
                $dr_count++;
            } else {
                $cr_ledger_id = $entryitem['ledger_id'];
                $cr_count++;
            }
        }

        /* Get ledger name */
        $dr_name = self::where(['id' => $dr_ledger_id])->first()->name;
        $cr_name = self::where(['id' => $cr_ledger_id])->first()->name;

        if (strlen($dr_name) > 15) {
            $dr_name = substr($dr_name, 0, 15) . '...';
        }
        if (strlen($cr_name) > 15) {
            $cr_name = substr($cr_name, 0, 15) . '...';
        }

        /* if more than one ledger on dr / cr then add [+] sign */
        if ($dr_count > 1) {
            $dr_name = $dr_name . ' [+]';
        }
        if ($cr_count > 1) {
            $cr_name = $cr_name . ' [+]';
        }

        $ledgerstr = 'Dr ' . $dr_name . ' / ' . 'Cr ' . $cr_name;
        //echo '<pre>';print_r($ledgerstr);echo '</pre>';exit;
        return $ledgerstr;
    }

    //get all tag lds ledgers
    static function Tag_lds($group_id = 0)
    {
        $opt = '';

        $result = self::whereIn('group_id', $group_id)->where(['branch_id' => Auth::user()->branch_id])->get(['id', 'name']);
        foreach ($result as $row) {
            $opt .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return $opt;

    }

    //get ledgers agains group id
    static function get_ledgers($group_id = 0)
    {
        $opt = '';
        $result = self::where('group_id', $group_id)->get(['id', 'name']);
        foreach ($result as $row) {
            $opt .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return $opt;
    }

    //get banks & cashs of branch
    static function get_branch_banks($branch_id = 0)
    {
        $opt = '';
        $result = self::where('branch_id', $branch_id)->whereIn('group_id', Config::get('constants.cash_and_bank_balance'))->get(['id', 'name']);
        foreach ($result as $row) {
            $opt .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return $opt;
    }
    
    public function sourceable()
    {
        return $this->morphTo();
    }

}
