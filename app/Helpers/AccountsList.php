<?php

namespace App\Helpers;

use App\Models\Admin\Groups;
use App\Models\Admin\Ledgers;
use Illuminate\Support\Facades\Config;

/**
 * Class to store the entire account tree with the details
 */
class AccountsList
{
    var $id = 0;
    var $name = '';
    var $code = '';
    var $level = '';
    var $filter = array();

    var $g_parent_id = 0;        /* Group specific */
    var $g_affects_gross = 0;    /* Group specific */
    var $l_group_id = 0;        /* Ledger specific */
    var $l_type = 0;        /* Ledger specific */
    var $l_reconciliation = 0;    /* Ledger specific */
    var $l_notes = '';        /* Ledger specific */

    var $op_total = 0;
    var $op_dr_total = 0;
    var $op_cr_total = 0;
    var $cl_dr_total = 0;
    var $cl_cr_total = 0;
    var $op_total_dc = 'd';
    var $dr_total = 0;
    var $cr_total = 0;
    var $cl_total = 0;
    var $cl_total_dc = 'd';

    var $children_groups = array();
    var $children_ledgers = array();

    var $counter = 0;

    var $only_opening = false;
    var $start_date = null;
    var $end_date = null;
    var $affects_gross = -1;

    /**
     * Initializer
     */
    function AccountList()
    {
        return;
    }

    /**
     * Setup which group id to start from
     */
    function start($id)
    {
        if ($id == 0) {
            $this->id = 0;
            $this->name = "None";
        } else {
            $group = Groups::find($id);
            if (!$group) {
                return;
            }
            $group = $group->toArray();
            $this->id = $group['id'];
            $this->name = $group['name'];
            $this->code = $group['number'];
            $this->level = $group['level'];
            $this->g_parent_id = $group['parent_id'];
            $this->g_affects_gross = 0;
        }

        $this->op_total = 0;
        $this->op_dr_total = 0;
        $this->op_cr_total = 0;
        $this->cl_dr_total = 0;
        $this->cl_cr_total = 0;
        $this->op_total_dc = 'd';
        $this->dr_total = 0;
        $this->cr_total = 0;
        $this->cl_total = 0;
        $this->cl_total_dc = 'd';

        $this->add_sub_ledgers();
        $this->add_sub_groups();
    }

    /**
     * Find and add subgroups as objects
     */
    function add_sub_groups()
    {
        $conditions = array('Group.parent_id' => $this->id);
        /* Check if net or gross restriction is set */
        if ($this->affects_gross == 0) {
            //			$conditions['Group.affects_gross'] = 0;
        }
        if ($this->affects_gross == 1) {
            //			$conditions['Group.affects_gross'] = 1;
        }
        /* Reset is since its no longer needed below 1st level of sub-groups */
        $this->affects_gross = -1;

        /* If primary group sort by id else sort by name */
        if ($this->id == 0) {
            $child_group_q = Groups::where(['parent_id' => $this->id])->OrderBy('code', 'asc')->get()->toArray();
        } else {
            $child_group_q = Groups::where(['parent_id' => $this->id])->OrderBy('code', 'asc')->get()->toArray();
        }

        $counter = 0;
        foreach ($child_group_q as $row) {
            /* Create new AccountList object */
            $this->children_groups[$counter] = new AccountsList();
            $this->children_groups[$counter]->filter = $this->filter;
            /* Initial setup */
            $this->children_groups[$counter]->only_opening = $this->only_opening;
            $this->children_groups[$counter]->start_date = $this->start_date;
            $this->children_groups[$counter]->end_date = $this->end_date;
            $this->children_groups[$counter]->affects_gross = -1; /* No longer needed in sub groups */
            $this->children_groups[$counter]->start($row['id']);
            //echo '<pre>';print_r($this->children_groups[$counter]);echo '</pre>';exit;
            /* Calculating opening balance total for all the child groups */
            $temp1 = CoreAccounts::calculate_withdc(
                $this->op_total,
                $this->op_total_dc,
                $this->children_groups[$counter]->op_total,
                $this->children_groups[$counter]->op_total_dc
            );
            $this->op_total = $temp1['amount'];
            $this->op_total_dc = $temp1['dc'];
            // if($this->children_groups[$counter]->op_total_dc == 'd'){
            $this->op_dr_total = CoreAccounts::calculate($this->op_dr_total, $this->children_groups[$counter]->op_dr_total, '+');
            //}else{
            $this->op_cr_total = CoreAccounts::calculate($this->op_cr_total, $this->children_groups[$counter]->op_cr_total, '+');
            //}

            /* Calculating closing balance total for all the child groups */
            $temp2 = CoreAccounts::calculate_withdc(
                $this->cl_total,
                $this->cl_total_dc,
                $this->children_groups[$counter]->cl_total,
                $this->children_groups[$counter]->cl_total_dc
            );
            $this->cl_total = $temp2['amount'];
            $this->cl_total_dc = $temp2['dc'];
            // if($this->children_groups[$counter]->cl_total_dc == 'd'){
            $this->cl_dr_total = CoreAccounts::calculate($this->cl_dr_total, $this->children_groups[$counter]->cl_dr_total, '+');
            //}else{
            $this->cl_cr_total = CoreAccounts::calculate($this->cl_cr_total, $this->children_groups[$counter]->cl_cr_total, '+');
            // }

            /* Calculate Dr and Cr total */
            $this->dr_total = CoreAccounts::calculate($this->dr_total, $this->children_groups[$counter]->dr_total, '+');
            $this->cr_total = CoreAccounts::calculate($this->cr_total, $this->children_groups[$counter]->cr_total, '+');

            $counter++;
        }
    }

    /**
     * Find and add subledgers as array items
     */
    function add_sub_ledgers()
    {

        if (!$child_ledger_q = Ledgers::where('group_id', $this->id)->OrderBy('code', 'asc')->get()) {
            return;
        }
        $child_ledger_q = $child_ledger_q->toArray();
        $counter = 0;
        foreach ($child_ledger_q as $row) {
            //echo '<pre>';print_r($row);echo '</pre>';exit;

            $this->children_ledgers[$counter]['id'] = $row['id'];
            $this->children_ledgers[$counter]['name'] = $row['name'];
            $this->children_ledgers[$counter]['code'] = $row['number'];
            $this->children_ledgers[$counter]['l_group_id'] = $row['group_id'];
            $this->children_ledgers[$counter]['l_type'] = 0;
            $this->children_ledgers[$counter]['l_reconciliation'] = 1;
            $this->children_ledgers[$counter]['l_notes'] = '';
            // New Always attach opening balance as well
            $this->children_ledgers[$counter]['op_total'] = $row['opening_balance'];
            $this->children_ledgers[$counter]['op_total_dc'] = $row['balance_type'];
            /* Calculating current group opening balance total */
            $temp3 = CoreAccounts::calculate_withdc(
                $this->op_total,
                $this->op_total_dc,
                $this->children_ledgers[$counter]['op_total'],
                $this->children_ledgers[$counter]['op_total_dc']
            );
            $this->op_total = $temp3['amount'];
            $this->op_total_dc = $temp3['dc'];
            if ($row['balance_type'] == 'd') {
                $this->op_dr_total = CoreAccounts::calculate($this->op_dr_total, $row['opening_balance'], '+');
            } else {
                $this->op_cr_total = CoreAccounts::calculate($this->op_cr_total, $row['opening_balance'], '+');
            }
            if ($this->only_opening == true) {
                /* If calculating only opening balance */
                $this->children_ledgers[$counter]['dr_total'] = 0;
                $this->children_ledgers[$counter]['cr_total'] = 0;

                $this->children_ledgers[$counter]['cl_total'] = $this->children_ledgers[$counter]['op_total'];
                $this->children_ledgers[$counter]['cl_total_dc'] = $this->children_ledgers[$counter]['op_total_dc'];
            } else {




                $cl = Ledgers::closingBalance(
                    $row['id'],
                    $this->start_date,
                    $this->end_date,
                    $this->filter
                );

                $this->children_ledgers[$counter]['dr_total'] = $cl['dr_total'];
                $this->children_ledgers[$counter]['cr_total'] = $cl['cr_total'];
                $this->children_ledgers[$counter]['cl_total'] = $cl['amount'];
                $temp5 = CoreAccounts::calculate_withdc(
                    $cl['amount'],
                    $cl['dc'],
                    $this->children_ledgers[$counter]['op_total'],
                    $this->children_ledgers[$counter]['op_total_dc']
                );
                $this->children_ledgers[$counter]['cl_total'] = $temp5['amount'];
                $this->children_ledgers[$counter]['cl_total_dc'] = $temp5['dc'];

            }
            //echo '</pre>';print_r($this->children_ledgers[$counter]['cl_total']);echo'<pre>';exit;
            /* Calculating current group closing balance total */
            $temp4 = CoreAccounts::calculate_withdc(
                $this->cl_total,
                $this->cl_total_dc,
                $this->children_ledgers[$counter]['cl_total'],
                $this->children_ledgers[$counter]['cl_total_dc']
            );
            //echo '</pre>';print_r($temp4);echo'<pre>';exit;
            $this->cl_total = $temp4['amount'];
            $this->cl_total_dc = $temp4['dc'];
            if ($temp5['dc'] == 'd') {
                $this->cl_dr_total = CoreAccounts::calculate($this->cl_dr_total, $temp5['amount'], '+');
            } else {
                $this->cl_cr_total = CoreAccounts::calculate($this->cl_cr_total, $temp5['amount'], '+');
            }
            /* Calculate Dr and Cr total */
            $this->dr_total = CoreAccounts::calculate($this->dr_total, $this->children_ledgers[$counter]['dr_total'], '+');
            $this->cr_total = CoreAccounts::calculate($this->cr_total, $this->children_ledgers[$counter]['cr_total'], '+');

            $counter++;
        }
    }

    static function toCodeWithName($code, $name)
    {
        if (strlen($code) <= 0) {
            return $name;
        } else {
            return '[' . $code . '] ' . $name;
        }
    }


    /**
     * Generate chart of accounts
     *
     * @param @account AccountList group account
     * @param @c int counter for number of level deep the account is
     * @param @THIS this $this CakePHP object passed inside function
     *
     * @return (void)
     */

    function generate_account_chart($account, $c = 0)
    {
        $html = '';

        $counter = $c;

        /* Print groups */
        if ($account->id != 0) {
            if ($account->id <= 4) {
                $html .= '<tr class="tr-group tr-root-group">';
            } else {
                $html .= '<tr class="tr-group">';
            }
            $html .= '<td><b>';
            $html .= $this->print_space($counter);
            $html .= self::toCodeWithName($account->code, $account->name);
            $html .= '</b></td>';

            $html .= '<td>Group</td>';

            $html .= '<td>';
            $html .= CoreAccounts::toCurrency($account->op_total_dc, $account->op_total);
            $html .= '</td>';

            $html .= '<td class="td-actions"></td>';
            $html .= '<td class="td-actions"></td>';

            $html .= '<td>';
            $html .= CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
            $html .= '</td>';

            /* If group id less than 4 dont show edit and delete links */
            $html .= '</tr>';
        }

        /* Print child ledgers */
        if (count($account->children_ledgers) > 0) {
            $counter++;
            foreach ($account->children_ledgers as $id => $data) {
                $html .= '<tr class="tr-ledger">';
                $html .= '<td class="td-ledger">';
                $html .= $this->print_space($counter);
                $html .= self::toCodeWithName($data['code'], $data['name']);
                $html .= '</td>';
                $html .= '<td>Ledger</td>';

                $html .= '<td>';
                $html .= CoreAccounts::toCurrency($data['op_total_dc'], $data['op_total']);
                $html .= '</td>';

                $html .= '<td class="td-actions">';
                $html .= '</td>';

                $html .= '<td class="td-actions">';
                $html .= '</td>';

                $html .= '<td>';
                $html .= CoreAccounts::toCurrency($data['cl_total_dc'], $data['cl_total']);
                $html .= '</td>';

                $html .= '</tr>';
            }
            $counter--;
        }

        /* Print child groups recursively */
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $html .= self::generate_account_chart($data, $counter);
            $counter--;
        }

        return $html;
    }

    /**
     * Generate Ledger Statement Table
     *
     * @param $account AccountList group account
     * @param @c int counter for number of level deep the account is
     *
     * @return $html return table rows
     *
     */
    function generateLedgerStatement($account, $c = 0)
    {
        //echo '<pre>';print_r($account);echo'</pre>';exit;
        $counter = $c;

        $html = '';
        if (isset($account->filter['account_type_id'])) {
            if ($account->filter['account_type_id'] >= $account->level) {
                if ($account->id != 0) {
                    if (in_array($account->id, Config::get('constants.accounts_main_heads'))) {
                        $html .= '<tr class="tr-group tr-root-group">';
                    } else {
                        $html .= '<tr class="tr-group">';
                    }
                    $html .= '<th class="td-group">';
                    $html .= $this->print_space($counter);
                    $html .= self::toCodeWithName($account->code, $account->name);
                    $html .= '</th>';

                    $html .= '<th>Group</td>';
                    if ($account->op_total_dc == 'd') {
                        $html .= '<th align="right">';
                        $html .= CoreAccounts::toCurrency($account->op_total_dc, $account->op_total);
                        $html .= '</th>';
                        $html .= '<th align="right">0.00</th>';
                    } else {
                        $html .= '<th align="right">0.00</th>';
                        $html .= '<th align="right">';
                        $html .= CoreAccounts::toCurrency($account->op_total_dc, $account->op_total);
                        $html .= '</th>';
                    }

                    $html .= '<th align="right">' . CoreAccounts::toCurrency('d', $account->dr_total) . '</th>';

                    $html .= '<th align="right">' . CoreAccounts::toCurrency('c', $account->cr_total) . '</th>';
                    if ($account->cl_total_dc == 'd') {
                        $html .= '<th align="right">' . CoreAccounts::toCurrency('d', $account->cl_total) . '</th>';
                        $html .= '<th align="right">0.00</th>';
                    } else {
                        $html .= '<th align="right">0.00</th>';
                        $html .= '<th align="right">' . CoreAccounts::toCurrency('c', $account->cl_total) . '</th>';
                    }
                    $html .= '</tr>';
                }

                if ($account->filter['account_type_id'] >= 7) {
                    if (count($account->children_ledgers) > 0) {
                        $counter++;
                        foreach ($account->children_ledgers as $id => $data) {

                            $html .= '<tr style="background-color:lightblue">';
                            $html .= '<td>';
                            $html .= $this->print_space($counter);
                            $html .= self::toCodeWithName($data['code'], $data['name']);
                            $html .= '</td>';
                            $html .= '<td>Ledger</td>';

                            if ($data['op_total_dc'] == 'd') {
                                $html .= '<td align="right">';
                                $html .= CoreAccounts::toCurrency($data['op_total_dc'], $data['op_total']);
                                $html .= '</td>';
                                $html .= '<td align="right">0.00</td>';
                            } else {
                                $html .= '<td align="right">0.00</td>';
                                $html .= '<td align="right">';
                                $html .= CoreAccounts::toCurrency($data['op_total_dc'], $data['op_total']);
                                $html .= '</td>';
                            }

                            $html .= '<td align="right">' . CoreAccounts::toCurrency('d', $data['dr_total']) . '</td>';

                            $html .= '<td align="right">' . CoreAccounts::toCurrency('c', $data['cr_total']) . '</td>';

                            if ($data['cl_total_dc'] == 'd') {
                                $html .= '<td align="right">' . CoreAccounts::toCurrency('d', $data['cl_total']) . '</td>';
                                $html .= '<td align="right">0.00</td>';
                            } else {
                                $html .= '<td align="right">0.00</td>';
                                $html .= '<td align="right">' . CoreAccounts::toCurrency('c', $data['cl_total']) . '</td>';
                            }

                            $html .= '</tr>';
                        }
                        $counter--;
                    }
                }
            }
        }


        /* Print child groups recursively */
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $html .= self::generateLedgerStatement($data, $counter);
            $counter--;
        }

        return $html;
    }

    /**
     * Generate Balance Sheet with Ledgers
     *
     * @param $account AccountList group account
     * @param @c int counter for number of level deep the account is
     *
     * @return $html return table rows
     *
     */
    function generateBalanceSheetWithLedgers($account, $c = 0, $dc_type)
    {
        $html = '';

        $counter = $c;
        if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
            if ($dc_type == 'd' && $account->cl_total_dc == 'c' && CoreAccounts::calculate($account->cl_total, 0, '!=')) {
                $html .= '<tr class="tr-group dc-error">';
            } else if ($dc_type == 'c' && $account->cl_total_dc == 'd' && CoreAccounts::calculate($account->cl_total, 0, '!=')) {
                $html .= '<tr class="tr-group dc-error">';
            } else {
                $html .= '<tr class="tr-group">';
            }

            $html .= '<td class="td-group">';
            $html .= $this->print_space($counter);
            $html .= self::toCodeWithName($account->code, $account->name);
            $html .= '</td>';

            $html .= '<td class="text-right" align="right">';
            $html .= CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
            $html .= $this->print_space($counter);
            $html .= '</td>';

            $html .= '</tr>';
        }
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $html .= self::generateBalanceSheetWithLedgers($data, $counter, $dc_type);
            $counter--;
        }
        if (count($account->children_ledgers) > 0) {
            $counter++;
            foreach ($account->children_ledgers as $id => $data) {
                if ($dc_type == 'd' && $data['cl_total_dc'] == 'c' && CoreAccounts::calculate($data['cl_total'], 0, '!=')) {
                    $html .= '<tr class="tr-ledger dc-error">';
                } else if ($dc_type == 'c' && $data['cl_total_dc'] == 'd' && CoreAccounts::calculate($data['cl_total'], 0, '!=')) {
                    $html .= '<tr class="tr-ledger dc-error">';
                } else {
                    $html .= '<tr class="tr-ledger">';
                }

                $html .= '<td class="td-ledger">';
                $html .= $this->print_space($counter);
                $html .= self::toCodeWithName($data['code'], $data['name']);
                $html .= '</td>';

                $html .= '<td class="text-right" align="right">';
                $html .= CoreAccounts::toCurrency($data['cl_total_dc'], $data['cl_total']);
                $html .= $this->print_space($counter);
                $html .= '</td>';

                $html .= '</tr>';
            }
            $counter--;
        }

        return $html;
    }


    /**
     * Generate Balance Sheet
     *
     * @param $account AccountList group account
     * @param @c int counter for number of level deep the account is
     *
     * @return $html return table rows
     *
     */
    function generateSheet($account, $c = 0, $dc_type)
    {

        $html = '';
        $counter = $c;
        if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
            if ($dc_type == 'd' && $account->cl_total_dc == 'c' && CoreAccounts::calculate($account->cl_total, 0, '!=')) {
                $html .= '<tr class="tr-group dc-error">';
            } else if ($dc_type == 'c' && $account->cl_total_dc == 'd' && CoreAccounts::calculate($account->cl_total, 0, '!=')) {
                $html .= '<tr class="tr-group dc-error">';
            } else {
                $html .= '<tr class="tr-group">';
            }

            $html .= '<td class="td-group">';
            $html .= $this->print_space($counter);
            $html .= self::toCodeWithName($account->code, $account->name);
            $html .= '</td>';

            if (count($account->children_groups)) {
                $html .= '<td class="text-right" align="right"></td>';
            } else {
                $html .= '<td class="text-right" align="right">';
                if ($account->id == 472 || $account->g_parent_id == 472) {
                    $Total_dr = CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
                    $html .= '$' . $Total_dr;
                } else {
                    $html .= CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        if (count($account->children_groups)) {
            foreach ($account->children_groups as $id => $data) {
                $counter++;
                $html .= self::generateSheet($data, $counter, $dc_type);
                $counter--;
            }

            if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
                $html .= '<tr class="tr-group">';
                $html .= '<td class="total-bg-filled">' . $this->print_space($counter) . 'Total of ' . self::toCodeWithName($account->code, $account->name) . '</td>';
                $html .= '<td class="text-right total-bg-filled" style="border-top: 1px solid black !important;" align="right">';
                if ($account->id == 472 || $account->g_parent_id == 472) {
                    $Total_dr = CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
                    $html .= '$' . $Total_dr;
                } else {
                    $html .= CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total);
                }


                //                $html .= $this->print_space($counter);
                $html .= '</td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }

    function print_space($count)
    {
        $html = '';
        for ($i = 1; $i <= $count; $i++) {
            $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $html;
    }

    static function printSpace($count)
    {
        $html = '';
        for ($i = 1; $i <= $count; $i++) {
            $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $html;
    }
}
