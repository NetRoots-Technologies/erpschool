<?php

namespace App\Helpers;

use Config;
use Exception;
use Validator;
// use App\Models\Groups;
use App\Models\Admin\Groups;
use App\Models\Admin\Weight;
use App\Models\Admin\Entries;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Settings;
use App\Models\Admin\Companies;
use App\Models\Admin\ItemsList;
use App\Models\Admin\Currencies;
use App\Models\Admin\EntryItems;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Vendor\Vendor;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\StockItemsModel;
use App\Models\Admin\LedgerCurrencies;
use App\Models\Reports\vendor_acount_report;

class CoreAccounts
{

    /*
     * Generate leading numbers and put before provided number
     *
     * @param: (int) $number
     * @return (int) $number
     */
    static function generateNumber($number)
    {
        return sprintf('%06d', $number);
    }

    static function getCurncyDecimalFixpoint($currency_code)
    {
        $currency_decimal_point = Currencies::where('code', $currency_code)->get('decimal_fixed_point')->first();

        $reposnse = $currency_decimal_point['decimal_fixed_point'];
        // dd($reposnse);
        return $reposnse;
    }

    static function getMetalDecimalFixpoint($metal_code)
    {
        $currency_decimal_point = Weight::where('code', $metal_code)->get('decimal_fixed_point')->first();

        $reposnse = $currency_decimal_point['decimal_fixed_point'];
        return $reposnse;
    }

    static function generateItemcode($chr, $number)
    {
        $code = sprintf('%06d', $number);
        return $code . $chr;
    }

    /*
     * Generate leading numbers and put before provided number
     *
     * @param: (int) $number
     * @return (int) $number
     */
    static function generateCode($number)
    {
        return sprintf('%04d', $number);
    }


    /*
     * Generate ledger numbers
     *
     *
     * Convert currency*/

    static function convertCurrency($amount, $rateself, $ratcvrt)
    {
        return round(($amount * $rateself) / $ratcvrt, 2);
    }


    /* @param: (int) $number
     * @return (int) $number
     */
    static function generateLedgerNumber($companyId, $groupNumber)
    {
        //dd($ledgerNumber);
        //return $companyId . '-' . $groupNumber . '-' . sprintf('%04d',$ledgerNumber);
        return $groupNumber . '-' . sprintf('%06d', (Ledgers::where(['group_id' => $companyId])->count()));
    }

    /**
     * Perform a decimal level calculations on two numbers
     *
     * Multiply the float by 100, convert it to integer,
     * Perform the integer operation and then divide the result
     * by 100 and return the result
     *
     * @param1 float number 1
     * @param2 float number 2
     * @op string operation to be performed
     * @return float result of the operation
     */

    static function calculate($param1 = 0, $param2 = 0, $op = '')
    {

        $decimal_places = Currency::_decimal_places();

        if (extension_loaded('bcmath')) {
            switch ($op) {
                case '+':
                    return bcadd($param1, $param2, $decimal_places);
                    break;
                case '-':
                    return bcsub($param1, $param2, $decimal_places);
                    break;
                case '==':
                    if (bccomp($param1, $param2, $decimal_places) == 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '!=':
                    if (bccomp($param1, $param2, $decimal_places) == 0) {
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                    break;
                case '<':
                    if (bccomp($param1, $param2, $decimal_places) == -1) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>':
                    if (bccomp($param1, $param2, $decimal_places) == 1) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>=':
                    $temp = bccomp($param1, $param2, $decimal_places);
                    if ($temp == 1 || $temp == 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case 'n':
                    return bcmul($param1, -1, $decimal_places);
                    break;
                default:
                    die();
                    break;
            }
        } else {
            $result = 0;

            if ($decimal_places == 2) {
                $param1 = $param1 * 100;
                $param2 = $param2 * 100;
            } else if ($decimal_places == 3) {
                $param1 = $param1 * 1000;
                $param2 = $param2 * 1000;
            }

            $param1 = (int) round($param1, 0);
            $param2 = (int) round($param2, 0);
            switch ($op) {
                case '+':
                    $result = $param1 + $param2;
                    break;
                case '-':
                    $result = $param1 - $param2;
                    break;
                case '==':
                    if ($param1 == $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '!=':
                    if ($param1 != $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '<':
                    if ($param1 < $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>':
                    if ($param1 > $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>=':
                    if ($param1 >= $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case 'n':
                    $result = -$param1;
                    break;
                default:
                    die();
                    break;
            }

            if ($decimal_places == 2) {
                $result = $result / 100;
            } else if ($decimal_places == 3) {
                $result = $result / 100;
            }

            return $result;
        }
    }

    /**
     * Perform a calculate with Debit and Credit Values
     *
     * @param1 float number 1
     * @param2 char nuber 1 debit or credit
     * @param3 float number 2
     * @param4 float number 2 debit or credit
     * @return array() result of the operation
     */
    static function calculate_withdc($param1, $param1_dc, $param2, $param2_dc)
    {
        $result = 0;
        $result_dc = 'd';
        if ($param1_dc == 'd' && $param2_dc == 'd') {
            $result = self::calculate($param1, $param2, '+');
            $result_dc = 'd';
        } else if ($param1_dc == 'c' && $param2_dc == 'c') {
            $result = self::calculate($param1, $param2, '+');
            $result_dc = 'c';
        } else {
            if (self::calculate($param1, $param2, '>')) {
                $result = self::calculate($param1, $param2, '-');
                $result_dc = $param1_dc;
            } else {
                $result = self::calculate($param2, $param1, '-');
                $result_dc = $param2_dc;
            }
        }

        return array('amount' => $result, 'dc' => $result_dc);
    }

    static function toCurrency($dc, $amount)
    {

        $decimal_places = Currency::_decimal_places();

        if (self::calculate($amount, 0, '==')) {
            return Currency::curreny_format(number_format(0, $decimal_places, '.', ''));
        }

        if ($dc == 'd') {
            if (self::calculate($amount, 0, '>')) {
                return 'Dr ' . Currency::curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Cr ' . Currency::curreny_format(number_format(self::calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else if ($dc == 'c') {
            if (self::calculate($amount, 0, '>')) {
                return 'Cr ' . Currency::curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Dr ' . Currency::curreny_format(number_format(self::calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else if ($dc == 'x') {
            /* Dr for positive and Cr for negative value */
            if (self::calculate($amount, 0, '>')) {
                return 'Dr ' . Currency::curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Cr ' . Currency::curreny_format(number_format(self::calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else {
            return Currency::curreny_format(number_format($amount, $decimal_places, '.', ''));
        }
    }

    /*
     * Get Setting by config id
     *
     * @param: (int) $id
     * @return (array) $response
     */
    public static function getConfigSettingID($id)
    {
        if ($Setting = Settings::where(['id' => $id])->first()) {
            return array(
                'status' => true,
                'id' => $Setting->description,
                'error' => ''
            );
        } else {
            return array(
                'status' => false,
                'id' => false,
                'error' => 'Setting not found.'
            );
        }
    }

    /*
     * Get Group by setting based config id
     *
     * @param: (int) $id
     * @return (array) $response
     */
    public static function getConfigGroup($id)
    {
        //$response = self::getConfigSettingID($id);
        //if($response['status']) {
        if ($Group = Groups::where(['id' => $id])->first()) {
            return array(
                'status' => true,
                'group' => $Group,
                'error' => ''
            );
        }
        //}
        //unset($response['id']);
        $response['group'] = '';
        return $response;
    }

    /*
     * Generate Number in Accounting
     *
     * @param  ind  $id
     * @return mixed $respose
     */
    public static function generateLevelAndNumber($parent_id, $adittional_number = 0)
    {
        $ParentGroup = Groups::findOrFail($parent_id);

        return [
            'number' => $ParentGroup->code . '-' . sprintf('%0' . (count(explode('-', $ParentGroup->number)) + 1) . 'd', (Groups::where(['parent_id' => $ParentGroup->id])->count() + ($adittional_number + 1))),
            'level' => ++$ParentGroup->level,
        ];
    }


    /*
     * Verify Parent Group in Accounting
     *
     * @param  ind  $id
     * @return mixed $respose
     */
    private static function verifyGroupNumber($old_number, $new_number)
    {
        if (count(explode('-', $old_number)) == count(explode('-', $new_number))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Create Group in Accounting
     *
     * @param  \App\Http\Requests\Admin\Groups\StoreUpdateRequest  $data
     * @return (array) $respose
     */
    public static function createGroup($data)
    {
        $rules = [
            'name' => 'required',
            'parent_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator
            );
        }
        $Group = Groups::where(['id' => $data['parent_id']])->first();

        if (!$Group) {
            return array(
                'status' => false,
                'error' => 'Parent Group ID does not exists'
            );
        } else {
            $data['created_by'] = Auth::user()->id;
            $data['updated_by'] = Auth::user()->id;
            $data['account_type_id'] = $Group->account_type_id;
            $data['parent_id'] = $Group->id;

            $levelAndNumber = self::generateLevelAndNumber($Group->id, 0);
            $data['number'] = $levelAndNumber['number'];
            $data['level'] = $levelAndNumber['level'];
            $Groups = Groups::create($data);
            return array(
                'status' => true,
                'error' => 'Group has been created',
                'id' => $Groups->id,
                'groups' => $Groups,
            );
        }
    }

    public static function createGoldPurchaseEntry($data)
    {
        $itemList = ItemsList::where('id', $data['item_id'])->first();

        $Stock_ledger = Groups::where(['parent_id' => 18])->where(['parent_type' => $data['item_cate']])->first();
        $Assets_id = $Stock_ledger->id;
        $transit_ledger = Ledgers::where('parent_type', $data['item_cate'])->where('group_id', $Assets_id)->first();
        $vendor_ledger = Ledgers::where('parent_type', $data['suplierid'])->where('group_id', Config::get('constants.acounts_supplier_local'))->first();
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['currence_type'] = 3;
        $Ddata['other_currency_type'] = 3;
        $Ddata['status'] = 0;
        $entry = Entries::create($Ddata);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));
        $Gold_purchase = array(
            "number" => "??????",
            "voucher_date" => date('Y-m-d'),
            "employee_id" => Auth::user()->id,
            "narration" => "Gold Purchase has been done",
            "entry_type_id" => "7",
            "branch_id" => $data['branch_id'],
            "currence_type" => $data['currency'],
            "other_currency_type" => $data['transaction_currency'],
            "rate" => $data['rate'],
            "entry_id" => $entry->id,
            "entry_items" => array(
                1 => [
                    'ledger_id' => $transit_ledger->id,
                    'dr_amount' => $data['weight'],
                    'other_amount' => $data['weight'],
                    'currence_type' => 3,
                    'other_currency_type' => 3,
                    'rate' => $data['rate'],
                    'narration' => "" . $data['weight'] . "g Gold Purchase has been done",
                ],
                2 => [
                    'ledger_id' => $vendor_ledger->id,
                    'cr_amount' => $data['weight'],
                    'other_amount' => $data['amount'],
                    'currence_type' => 3,
                    'other_currency_type' => 1,
                    'rate' => 1,
                    'narration' => "" . $data['weight'] . " Gold Purchase has been done",
                ],
            ),
            "diff_total" => 0,
            "dr_total" => $data['weight'],
            "cr_total" => $data['weight'],
            "other_dr_total" => $data['amount'],
            "other_cr_total" => $data['amount'],
        );
        //dd($Gold_purchase);
        CoreAccounts::createJEntry($Gold_purchase);
    }

    public static function createLdsInventory($data)
    {
        $Tagg_itemsID = Groups::where(['parent_id' => Config::get('constants.acounts_process_tag')])->where(['parent_type' => $data['lds_type']])->value('id');
        $Tagg_itemsID = Ledgers::where('group_id', $Tagg_itemsID)->value('id');
        $stock_itemsID = Groups::where(['parent_id' => Config::get('constants.acounts_sale_tag')])->where(['parent_type' => $data['lds_type']])->value('id');
        $stock_itemsID = Ledgers::where('group_id', $stock_itemsID)->value('id');
        $Gold_purchase = array(
            "number" => "??????",
            "voucher_date" => date('Y-m-d'),
            "employee_id" => Auth::user()->id,
            "narration" => "LDS Stock shift to Inventory",
            "entry_type_id" => "1",
            "branch_id" => 1,//$data['branch_id'],
            "currence_type" => 2, // $data['currency'],
            "other_currency_type" => 2, // $data['currency'],
            "rate" => 105.00, //$data['rate'],
            "entry_items" => array(
                1 => [
                    'ledger_id' => $stock_itemsID,
                    'dr_amount' => $data['grand_total_price'] + $data['parcelothercharges'],
                    'other_amount' => $data['grand_total_price'] + $data['parcelothercharges'],
                    'cr_amount' => 0,
                    'narration' => "LDS Stock shift to Inventory",
                ],
                2 => [
                    'ledger_id' => $Tagg_itemsID,
                    'dr_amount' => 0,
                    'cr_amount' => $data['grand_total_price'] + $data['parcelothercharges'],
                    'other_amount' => $data['grand_total_price'] + $data['parcelothercharges'],
                    'narration' => "LDS Stock shift to Inventory",
                ],
            ),
            "diff_total" => 0,
            "dr_total" => $data['grand_total_price'] + $data['parcelothercharges'],
            "cr_total" => $data['grand_total_price'] + $data['parcelothercharges'],
            "other_dr_total" => $data['grand_total_price'] + $data['parcelothercharges'],
            "other_cr_total" => $data['grand_total_price'] + $data['parcelothercharges'],
        );
        CoreAccounts::createJEntry($Gold_purchase);
    }

    public static function createLDSPurchaseEntry($data, $stock = null, $acc_data)
    {
        //dd($stock);
        $count = 1;
        $Stock_inventory = array();
        //dd(Config::get('constants.acounts_process_tag'));
        foreach ($stock as $key) {
            if ($key['shift_to_tagging'] == 'Inventory') {
                $stock_itemsID = Groups::where(['parent_id' => Config::get('constants.acounts_sale_pack')])->where(['parent_type' => $acc_data['lds_type']])->value('id');
                $Ineventory_items = Ledgers::where('parent_type', $key['packet_no'])->where('group_id', $stock_itemsID)->value('id');
            } else {
                //$stock_itemsID =  Groups::where(['parent_id' => Config::get('constants.acounts_process_tag')])->where(['parent_type' => $data['lds_type']])->value('id');
                //$Ineventory_items = Ledgers::where('group_id',$stock_itemsID)->value('id');
                $Ineventory_items = 29;
            }
            $vendorType = Vendor::where('id', $acc_data['agent'])->value('vendor_type');
            $supID = Ledgers::where('parent_type', $acc_data['suplierid'])->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $acc_data['agent'])->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $acc_data['agent'])->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            //agent commission
            $entData[0]['ledger_id'] = $agent;
            $newEntData[0] = array_merge($data[0], $entData[0]);
            //freight paid
            $entData[1]['ledger_id'] = $supID;
            $newEntData[1] = array_merge($data[1], $entData[1]);
            //miscleanous charges
            $entData[2]['ledger_id'] = $supID;
            $newEntData[2] = array_merge($data[2], $entData[2]);
            //supplier entry
            $entData[3]['ledger_id'] = $supID;
            $newEntData[3] = array_merge($data[3], $entData[3]);
        }
        $LDS_purchase = array(
            "number" => "??????",
            "voucher_date" => date('Y-m-d'),
            "employee_id" => Auth::user()->id,
            "narration" => "LDS Purchase has been done",
            "entry_type_id" => "7",
            "branch_id" => $acc_data['branch_id'],
            "currence_type" => $acc_data['basic_currency'],
            "other_currency_type" => $acc_data['transaction_currency'],
            "rate" => $acc_data['rate'],
            "entry_items" => $newEntData,
            "diff_total" => 0,
            "dr_total" => $acc_data['basic_amount'],
            "cr_total" => $acc_data['basic_amount'],
            "other_dr_total" => round($acc_data['basic_amount'] * $acc_data['rate'], 2),
            "other_cr_total" => round($acc_data['basic_amount'] * $acc_data['rate'], 2),
            "entry_id" => $acc_data['entry_id']
        );
        //dd($LDS_purchase);
        CoreAccounts::createJEntry($LDS_purchase);

    }

    /*
     * Create Group in Accounting
     *
     * @param  \App\Http\Requests\Admin\Groups\StoreUpdateRequest  $data
     * @return (array) $respose
     */
    public static function updateGroup($data, $id)
    {
        $rules = [
            'name' => 'required',
            'parent_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }

        if (!$Group = Groups::where(['id' => $id])->first()) {
            return array(
                'status' => false,
                'error' => 'No Group ID found.'
            );
        }

        // Those Groups can't be moved who have child groups or ledgers associagted with it.
        if ((Groups::hasChildLedgers($Group->id) || Groups::hasChildGroups($Group->id)) && $Group->parent_id != $data['parent_id']) {
            return array(
                'status' => false,
                'error' => 'Parent Group can not be changed due to one or more Group(s) / Ledger(s) are associated with."' . $Group->name . '" group.'
            );
        } else {
            if (!$ParentGroup = Groups::where(['id' => $data['parent_id']])->first()) {
                return array(
                    'status' => false,
                    'error' => 'Parent Group ID does not exists'
                );
            } else {
                $levelAndNumber = self::generateLevelAndNumber($ParentGroup->id);
                // Set Current Level
                $data['level'] = $levelAndNumber['level'];
                if (!self::verifyGroupNumber($Group->number, $levelAndNumber['number'])) {
                    $data['number'] = $levelAndNumber['number'];
                }
                $data['updated_by'] = Auth::user()->id;
                $data['account_type_id'] = $ParentGroup->account_type_id;
                $Group->update($data);
                return array(
                    'status' => true,
                    'error' => 'Group has been created'
                );
            }
        }
    }

    /*
     * Create Ledger in Accounting
     *
     * @param (array) $data
     * @return (array) $response
     */
    public static function createLedger($data)
    {
        $rules = [
            'name' => 'required',
            'group_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }

        $Ldata['created_by'] = Auth::user()->id;
        // Get selected group
        if (!$Group = Groups::where(['id' => $data['group_id']])->first()) {
            return array(
                'status' => false,
                'error' => 'Group ID does not exists'
            );
        } else {
            if ($Group->account_type_id == 1 || $Group->account_type_id == 3) {
                $balance_type = 'd';
            } else {
                $balance_type = 'c';
            }
            $Ldata['name'] = $data['name'];
            $Ldata['group_id'] = $Group->id;
            $Ldata['group_number'] = $Group->number;
            $Ldata['account_type_id'] = $Group->account_type_id;
            $Ldata['created_by'] = Auth::user()->id;
            $Ldata['branch_id'] = Auth::user()->branch_id;
            if (isset($data['parent_type'])) {
                $Ldata['parent_type'] = $data['parent_type'];
            }
            $Ledger = Ledgers::create($Ldata);
            if (isset($data['currency_id'])) {
                $currencies = $data['currency_id'];
                foreach ($currencies as $key => $val) {
                    $array[] = array('currency_id' => $data['currency_id'][$key], 'balance_type' => $data['balance_type'][$key], 'amount' => $data['amount'][$key], 'ledger_id' => $Ledger->id);
                }
                LedgerCurrencies::insert($array);
            } else {
                $array = array('currency_id' => 1, 'balance_type' => 'd', 'amount' => 0, 'ledger_id' => $Ledger->id);
                LedgerCurrencies::insert($array);
            }
            $Ledger->update(['number' => CoreAccounts::generateLedgerNumber($Group->id, $Group->number)]);

            return array(
                'status' => true,
                'error' => 'Ledger has been created',
                'id' => $Ledger->id
            );
        }
    }

    /*
     * create ledger while emoloyee creating
     */
    public static function createEmployeeLedger($data)
    {
        $EmpLedger['name'] = $data['name'];
        $EmpLedger['group_id'] = $data['staff_head'];
        $EmpLedger['parent_type'] = $data['parent_type'];
        self::createLedger($EmpLedger);
        //create staff_bonus_head
        $EmpLedger['group_id'] = $data['staff_bonus_head'];
        self::createLedger($EmpLedger);
        //create staff_medical
        $EmpLedger['group_id'] = $data['staff_medical'];
        self::createLedger($EmpLedger);
        //create staff_eobi
        $EmpLedger['group_id'] = $data['staff_eobi'];
        self::createLedger($EmpLedger);
    }

    /*
     * Create Ledger in Accounting
     *
     * @param (array) $data
     * @return (array) $response
     */
    public static function updateLedger($data, $id)
    {
        //dd($data);
        $rules = [
            'name' => 'required',
            'group_id' => 'required',
            //'balance_type' => 'required',
            //'opening_balance' => 'required|numeric',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }

        if (!$Ledger = Ledgers::where(['id' => $id])->first()) {
            return array(
                'status' => false,
                'error' => 'No Ledger ID found.'
            );
        }

        $Ldata['updated_by'] = Auth::user()->id;
        // Get selected group
        if (!$Group = Groups::where(['id' => $data['group_id']])->first()) {
            return array(
                'status' => false,
                'error' => 'Group ID does not exists'
            );
        } else {
            $Ldata['name'] = $data['name'];
            $Ldata['group_id'] = $Group->id;
            $Ldata['group_number'] = $Group->number;
            $Ldata['account_type_id'] = $Group->account_type_id;
            $Ledger->update($Ldata);
            if (isset($data['currency_id']) && !empty($data['currency_id'])) {
                $LedgerCurrency = LedgerCurrencies::where('ledger_id', $id)->delete();
                $currencies = $data['currency_id'];
                foreach ($currencies as $key => $val) {
                    if ($data['currency_id'][$key] != 0) {
                        $array[] = array('currency_id' => $data['currency_id'][$key], 'balance_type' => $data['balance_type'][$key], 'amount' => $data['amount'][$key], 'ledger_id' => $Ledger->id);
                    }
                }
                LedgerCurrencies::insert($array);
            }
            return array(
                'status' => true,
                'error' => 'Ledger has been updated'
            );
        }
    }


    public static function createLcInventory($data)
    {

        //dd($data);
        $rules = [
            'voucher_date' => 'required',
            'entry_type_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'employee_id' => 'required|numeric',
            'department_id' => 'sometimes|nullable|numeric',
            'narration' => 'required',
            'cr_total' => 'required',
            'diff_total' => 'required|numeric|min:0|max:0',
            'entry_items' => 'required',
        ];
        $rules['entry_items.ledger_id.1'] = 'required';
        $rules['entry_items.ledger_id.2'] = 'required';
        $rules['entry_items.dr_amount.2'] = 'required_if:entry_items.dr_amount,0|numeric';

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }
        $data['dr_total'] = $data['cr_total'];
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['status'] = 0;
        //dd($data);
        $entry = Entries::create($data);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));

        /*
         * Create Entry Items records associated to Etnry now
         */
        $entry_items = array();
        foreach ($data['entry_items']['counter'] as $key => $val) {
            $item = array(
                'status' => 0,
                'entry_type_id' => $data['entry_type_id'],
                'entry_id' => $entry->id,
                'voucher_date' => $data['voucher_date'],
                'ledger_id' => $data['entry_items']['ledger_id'][$val],
                'narration' => $data['narration'],
            );
            if ($key == '1') {
                $item['amount'] = $data['cr_total'];
                $item['dc'] = 'c';
            } else {
                $item['amount'] = $data['cr_total'];
                $item['dc'] = 'd';
            }
            $entry_items[] = $item;
        }
        //dd($entry_items);

        // EntryItems::insert($entry_items);
//        foreach($entry_items as $value){
//            $ledger_id = $value['ledger_id'];
//            $Ledgers = Ledgers::where(['id' => $ledger_id])->get();
//            $closing_balance = $Ledgers[0]->closing_balance;
//            if($value['dc']=='c'){
//                $nclosebalnc = $closing_balance - $value['amount'];
//                DB::table('ledgers')
//                    ->where('id',$ledger_id)
//                    ->update(['closing_balance' => $nclosebalnc]);
//            }else{
//                $nclosebalnc = $closing_balance + $value['amount'];
//                DB::table('ledgers')
//                    ->where('id',$ledger_id)
//                    ->update(['closing_balance' => $nclosebalnc]);
//            }
//
//        }


        return array(
            'status' => true,
            'error' => 'Ledger has been created'
        );
    }

    /*
     * Create LCEntry voucher in Accounting
     *
     * @param (array) $data
     * @return (array) $response
     */
    public static function createLcEntry($data)
    {
        // dd($data);
        $data['entry_items']['narration'][1] = $data['narration'];

        $rules = [
            'voucher_date' => 'required',
            'entry_type_id' => 'required|numeric',
            //'branch_id' => 'required|numeric',
            //'employee_id' => 'required|numeric',
            //'department_id' => 'sometimes|nullable|numeric',
            'narration' => 'required',
            'dr_total' => 'required|numeric|min:1|same:cr_total',
            'cr_total' => 'required|numeric|min:1|same:dr_total',
            'diff_total' => 'required|numeric|min:0|max:0',
            'entry_items' => 'required',
        ];

        if (isset($data['entry_items']) && count($data['entry_items'])) {
            $entry_items = $data['entry_items'];
            foreach ($entry_items['counter'] as $key => $val) {
                $rules['entry_items.ledger_id.' . $val] = 'required';
                $rules['entry_items.dr_amount.' . $val] = 'required_if:entry_items.cr_amount,0|numeric';
                $rules['entry_items.cr_amount.' . $val] = 'required_if:entry_items.dr_amount,0|numeric';
                $rules['entry_items.lc_duties.' . $val] = 'required';
                $rules['entry_items.narration.' . $val] = 'required';


            }
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }

        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['employee_id'] = Auth::user()->id;
        $data['branch_id'] = 1;
        $data['status'] = 0;
        //        $entry = Entries::create($data);
//        $entry->update(array(
//            'number' => CoreAccounts::generateNumber($entry->id),
//        ));

        /*
         * Create Entry Items records associated to Etnry now
         */
        $entry_items = array();
        $lc_items = array();
        $lc_duties = array();
        foreach ($data['entry_items']['counter'] as $key => $val) {
            if ($val != 1) {
                $ComercialInvoiceModel = ComercialInvoiceModel::where('id', $data['entry_items']['ledger_id'][$val])->first();
                $lcID = Ledgers::where(['parent_type' => $ComercialInvoiceModel->lcno])->first();
                $lc_items['duties'] = $data['entry_items']['lc_duties'][$val];
                $lc_items['ledgerLc_id'] = $ComercialInvoiceModel->lcno;
                $lc_items['Comercial_id'] = $ComercialInvoiceModel->id;
                $lc_items['created_by'] = Auth::user()->id;
                $lc_items['created_at'] = date('Y-m-d');
                $item['ledger_id'] = $lcID->id;
            }
            $item = array(
                'status' => 0,
                'entry_type_id' => $data['entry_type_id'],
                //'entry_id' => $entry->id,
                'voucher_date' => $data['voucher_date'],
                'ledger_id' => $data['entry_items']['ledger_id'][$val],
                'narration' => $data['entry_items']['narration'][$val],
            );
            if ($data['entry_items']['dr_amount'][$val]) {
                $item['amount'] = $data['entry_items']['dr_amount'][$val];
                $item['dc'] = 'd';
                $lc_items['amount'] = $item['amount'];
            } else {
                $item['amount'] = $data['entry_items']['cr_amount'][$val];
                $item['dc'] = 'c';
            }
            $entry_items[] = $item;
            if (!empty($lc_items)) {
                //$checkCosting = LcDutyModel::where('id',$lc_items)->first();
                //dd($checkCosting);
                $lc_duties[] = $lc_items;
            }


        }
        // $lc_duties = array_filter($lc_duties);
        EntryItems::insert($entry_items);
        LcDutyModel::insert($lc_duties);
        return array(
            'status' => true,
            'error' => 'Ledger has been created'
        );
    }

    /*
     * Create Entry in Accounting
     *
     * @param (array) $data
     * @return (array) $response
     */
    public static function createEntry($data)
    {
        $rules = [
            'voucher_date' => 'required',
            'entry_type_id' => 'required|numeric',
            //'branch_id' => 'required|numeric',
            // 'employee_id' => 'required|numeric',
            // 'department_id' => 'sometimes|nullable|numeric',
//            'narration' => 'required',
            'dr_total' => 'required|numeric|min:1|same:cr_total',
            'cr_total' => 'required|numeric|min:1|same:dr_total',
            'diff_total' => 'required|numeric|min:0|max:0',
            'entry_items' => 'required',
        ];

        if (isset($data['entry_items']) && count($data['entry_items'])) {
            $entry_items = $data['entry_items'];
            foreach ($entry_items['counter'] as $key => $val) {
                $rules['entry_items.ledger_id.' . $val] = 'required';
                $rules['entry_items.dr_amount.' . $val] = 'required_if:entry_items.cr_amount,0|numeric';
                $rules['entry_items.cr_amount.' . $val] = 'required_if:entry_items.dr_amount,0|numeric';
                //                $rules['entry_items.narration.'. $val] = 'required';


            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }
        $data['other_dr_total'] = 0;

        $data['other_cr_total'] = 0;
        $data['currence_type'] = 1;
        $data['other_currency_type'] = 1;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['employee_id'] = Auth::user()->id;
        $data['branch_id'] = 1;
        $data['status'] = 0;

        $entry = Entries::create($data);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));


        /*
         * Create Entry Items records associated to Etnry now
         */
        $entry_items = array();
        foreach ($data['entry_items']['counter'] as $key => $val) {
            $item = array(
                'status' => 0,
                'entry_type_id' => $data['entry_type_id'],
                'entry_id' => $entry->id,
                'voucher_date' => $data['voucher_date'],
                'ledger_id' => $data['entry_items']['ledger_id'][$val],
                'narration' => $data['entry_items']['narration'][$val],
            );

            if ($data['entry_items']['dr_amount'][$val]) {
                $item['amount'] = $data['entry_items']['dr_amount'][$val];
                $item['other_amount'] = $data['entry_items']['dr_amount'][$val];
                $item['dc'] = 'd';
                $item['currence_type'] = $data['entry_items']['currency_id'][$val];
                $item['other_currency_type'] = $data['entry_items']['currency_id'][$val];
            } else {
                $item['amount'] = $data['entry_items']['cr_amount'][$val];
                $item['other_amount'] = $data['entry_items']['cr_amount'][$val];
                $item['dc'] = 'c';
                $item['currence_type'] = $data['entry_items']['currency_id'][$val];
                $item['other_currency_type'] = $data['entry_items']['currency_id'][$val];
            }
            $entry_items[] = $item;
        }

        EntryItems::insert($entry_items);
        $entry_items_fetch = EntryItems::where('entry_id', $entry->id)->get();
        foreach ($entry_items_fetch as $item_fetch) {
            if ($item_fetch->dc == "d") {
                $ledger = Ledgers::where('id', $item_fetch->ledger_id)->first();
                $vendor_id = $ledger->parent_type;
                //                CoreAccounts::_insert_report_item($entry, $item_fetch, $vendor_id);

            }
        }

        return array(
            'status' => true,
            'error' => 'Ledger has been created'
        );
    }


    public static function _insert_report_item($entry, $item_fetch, $vendor_id)
    {
        //        $vendor_acount_report = new vendor_acount_report ();
//        $vendor_acount_report->entry_id = $entry->id;
//        $vendor_acount_report->entry_item_id = $item_fetch->id;
//        $vendor_acount_report->date = $entry->voucher_date;
//        $vendor_acount_report->vendor_id = $vendor_id;
//        $vendor_acount_report->save();
    }


    /*
     * Update Entry in Accounting
     *
     * @param (array) $data
     * @return (array) $response
     */
    public static function updateEntry($data, $id)
    {

        $rules = [
            'voucher_date' => 'required',
            'entry_type_id' => 'required|numeric',
            //'branch_id' => 'required|numeric',
            //'employee_id' => 'required|numeric',
            //'department_id' => 'sometimes|nullable|numeric',
            'narration' => 'required',
            'dr_total' => 'required|numeric|min:1|same:cr_total',
            'cr_total' => 'required|numeric|min:1|same:dr_total',
            'diff_total' => 'required|numeric|min:0|max:0',
            'entry_items' => 'required',
        ];

        if (isset($data['entry_items']) && count($data['entry_items'])) {
            $entry_items = $data['entry_items'];
            foreach ($entry_items['counter'] as $key => $val) {
                $rules['entry_items.ledger_id.' . $val] = 'required';
                $rules['entry_items.dr_amount.' . $val] = 'required_if:entry_items.cr_amount,0|numeric';
                $rules['entry_items.cr_amount.' . $val] = 'required_if:entry_items.dr_amount,0|numeric';
                $rules['entry_items.narration.' . $val] = 'required';
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return array(
                'status' => false,
                'error' => $validator->errors()
            );
        }

        if (!$Entrie = Entries::where(['id' => $id])->first()) {
            return array(
                'status' => false,
                'error' => 'No Ledger ID found.'
            );
        }

        $data['updated_by'] = Auth::user()->id;
        // $data['status'] = 0;

        $Entrie->update($data);

        /*
         * Create Entry Items records associated to Etnry now
         */
        // Delete old entries
        EntryItems::where(['entry_id' => $id])->forceDelete();

        $entry_items = array();
        foreach ($data['entry_items']['counter'] as $key => $val) {
            $item = array(
                'status' => 0,
                'entry_type_id' => $data['entry_type_id'],
                'entry_id' => $id,
                'voucher_date' => $data['voucher_date'],
                'ledger_id' => $data['entry_items']['ledger_id'][$val],
                'narration' => $data['entry_items']['narration'][$val],
            );
            if ($data['entry_items']['dr_amount'][$val]) {
                $item['amount'] = $data['entry_items']['dr_amount'][$val];
                $item['dc'] = 'd';
            } else {
                $item['amount'] = $data['entry_items']['cr_amount'][$val];
                $item['dc'] = 'c';
            }
            $entry_items[] = $item;
        }

        EntryItems::insert($entry_items);

        return array(
            'status' => true,
            'error' => 'Ledger has been created'
        );
    }

    public static function createJEntry($data)
    {
        //        $data['created_by'] = Auth::user()->id;
//        $data['updated_by'] = Auth::user()->id;
//        $data['employee_id'] = Auth::user()->id;
//        $data['status'] = 0;
//        $entry = Entries::create($data);
//        $entry->update(array(
//            'number' => CoreAccounts::generateNumber($entry->id),
//        ));

        /*
         * Create Entry Items records associated to Etnry now
         */
        //dd($data['entry_items']);
        $entry_items = array();
        foreach ($data['entry_items'] as $key => $val) {
            $item = array(
                'status' => 0,
                'entry_type_id' => $data['entry_type_id'],
                'entry_id' => $data['entry_id'],
                'voucher_date' => $data['voucher_date'],
                'ledger_id' => $data['entry_items'][$key]['ledger_id'],
                'narration' => $data['entry_items'][$key]['narration'],
                'other_amount' => $data['entry_items'][$key]['other_amount'],
                'currence_type' => $data['entry_items'][$key]['currence_type'],
                'rate' => $data['entry_items'][$key]['rate'],
                'other_currency_type' => $data['entry_items'][$key]['other_currency_type']
            );
            if (isset($data['entry_items'][$key]['dr_amount'])) {
                $item['amount'] = $data['entry_items'][$key]['dr_amount'];
                $item['dc'] = 'd';
            } else {
                $item['amount'] = $data['entry_items'][$key]['cr_amount'];
                $item['dc'] = 'c';
            }
            $entry_items[] = $item;
        }
        EntryItems::insert($entry_items);
    }
    //    public static function createSale($data)
//    {
//
//
//        $rules = [
//            'sal_no' => 'required',
//            'sal_date' => 'required|numeric',
//            'valid_upto' => 'required|numeric',
//        ];
//
//
//        $validator = Validator::make($data, $rules);
//
//        if ($validator->fails()) {
//            return array(
//                'status' => false,
//                'error' => $validator->errors()
//            );
//        }
//
//        $data['created_by'] = Auth::user()->id;
//        $data['updated_by'] = Auth::user()->id;
//        $data['status'] = 0;
//
//        $Sales = SalesModel::create($data);
//        $Sales->update(array(
//            'sal_no' => CoreAccounts::generateNumber($Sales->id),
//        ));
//    }
//////////core code written by Muhammad azeem khalid
    public static function dr_cr($id = 0)
    {
        $list = '';
        $array = array('Dr' => 'd', 'Cr' => 'c');
        foreach ($array as $key => $val) {
            $list .= '<option ' . (($id == $val) ? 'selected' : '') . ' value="' . $val . '">' . $key . '</option>';
        }
        return $list;
    }

    public static function dr_cr_balance($amount = 0, $decimal = 0)
    {
        if ($amount > 0) {
            return number_format(abs($amount), $decimal) . ' DR';
        }
        if ($amount < 0) {
            return number_format(abs($amount), $decimal) . ' CR';
        } else {
            return 'Nill';
        }
    }

    //opening balance currency wise
    public static function ob_currency_wise($df, $selCurrRate = 0, $ledger_id = 0, $currency_id = 0)
    {
        $date_from = date('Y-m-d', strtotime('-1 day', strtotime($df)));
        $Ledgers = LedgerCurrencies::where('ledger_id', $ledger_id)->where('currency_id', $currency_id)->first(['currency_id', 'balance_type', 'amount']);
        $new_date = date("Y-m-d", strtotime('2020-01-01'));
        $dr = 0;
        $cr = 0;
        $ob = 0;
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type == 'd') {
            $ob = $Ledgers->amount;
        }
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type === 'c') {
            $ob = -($Ledgers->amount);
        }
        if ($currency_id > 0) {
            $Entries = EntryItems::where('ledger_id', $ledger_id)->where('other_currency_type', 1)->whereBetween('voucher_date', [$new_date, $date_from])->get();
            foreach ($Entries as $Ent) {
                $baseCurrRate = Currencies::where('id', $Ent->other_currency_type)->pluck('rate');
                if ($Ent->dc == 'd') {
                    $dr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
                }
                if ($Ent->dc == 'c') {
                    $cr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
                }
            }//foreach
        }
        $balance = ($ob) + ($dr) - ($cr);
        //$balance=self::dr_cr_balance($balance);
        return $balance;
    }

    public static function opening_balance1($df, $ledger_id = 0)
    {
        $date_from = date('Y-m-d', strtotime('-1 day', strtotime($df)));
        $new_date = date("Y-m-d", strtotime('2010-01-01'));
        $dr = 0;
        $cr = 0;
        $ob = 0;
        $Ledgers = Ledgers::where('id', $ledger_id)->first(['opening_balance', 'balance_type']);
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type == 'd') {
            $ob = $Ledgers->opening_balance;
        }
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type == 'c') {
            $ob = -($Ledgers->opening_balance);
        }
        $Entries = EntryItems::where('ledger_id', $ledger_id)->whereBetween('voucher_date', ['' . $new_date . '', '' . $date_from . ''])->get();
        foreach ($Entries as $Ent) {
            if ($Ent->dc == 'd') {
                $dr += $Ent['amount'];
            }
            if ($Ent->dc == 'c') {
                $cr += $Ent['amount'];
            }
        }//foreach

        $balance = ($ob) + ($dr) - ($cr);
        return $balance;

    }

    public static function opening_balance($df, $selCurrRate = 1, $ledger_id = 0, $currency_id = 1)
    {

        $date_from = date('Y-m-d', strtotime('-1 day', strtotime($df)));
        $Ledgers = LedgerCurrencies::where('ledger_id', $ledger_id)->where('currency_id', $currency_id)->first(['currency_id', 'balance_type', 'amount']);
        $new_date = date("Y-m-d", strtotime('2020-01-01'));
        $dr = 0;
        $cr = 0;
        $ob = 0;
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type == 'd') {
            $ob = $Ledgers->amount;
        }
        if (isset($Ledgers->balance_type) && $Ledgers->balance_type === 'c') {
            $ob = -($Ledgers->amount);
        }
        if ($currency_id > 0) {

            $Entries = EntryItems::where('ledger_id', $ledger_id)->where('other_currency_type', $currency_id)->whereBetween('voucher_date', [$new_date, $date_from])->get();

            foreach ($Entries as $Ent) {
                $baseCurrRate = Currencies::where('id', $Ent->other_currency_type)->pluck('rate');

                if ($Ent->dc == 'd') {
                    $dr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
                }
                if ($Ent->dc == 'c') {
                    $cr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
                }

            }//foreach
        } else {
            $Entries = EntryItems::where('ledger_id', $ledger_id)->where('other_currency_type', '!=', '')->whereBetween('voucher_date', [$new_date, $date_from])->get();

            foreach ($Entries as $Ent) {
                $baseCurrRate = Currencies::where('id', $Ent->currence_type)->pluck('rate');

                if ($Ent->dc == 'd') {
                    $dr += floatval($Ent['amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate[0]);
                }
                if ($Ent->dc == 'c') {
                    $cr += floatval($Ent['amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate[0]);
                }
            }//foreach
        }
        $balance = ($ob) + ($dr) - ($cr);
        //$balance=self::dr_cr_balance($balance);

        return $balance;

    }

    //combine opening balance for convert amount in standard currency
    public static function combine_ob($df, $selCurrRate = 0, $ledger_id = 0)
    {
        $date_from = date('Y-m-d', strtotime('-1 day', strtotime($df)));
        $Ledgers = LedgerCurrencies::where('ledger_id', $ledger_id)->get();
        $new_date = date("Y-m-d", strtotime('2020-01-01'));
        $dr = 0;
        $cr = 0;
        $ob = 0;
        foreach ($Ledgers as $ledger) {
            $currencyRate = Currencies::where('id', 1)->first('rate');
            if (isset($ledger->balance_type) && $ledger->balance_type == 'd') {
                $ob += $ledger->amount * $currencyRate->rate / $selCurrRate;
            }
            if (isset($ledger->balance_type) && $ledger->balance_type === 'c') {
                $ob += -($ledger->amount * $currencyRate->rate / $selCurrRate);
            }
        }
        $Entries = EntryItems::where('ledger_id', $ledger_id)->whereBetween('voucher_date', [$new_date, $date_from])->get();
        foreach ($Entries as $Ent) {
            $baseCurrRate = Currencies::where('id', 1)->pluck('rate');
            if ($Ent->dc == 'd') {
                $dr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
            }
            if ($Ent->dc == 'c') {
                $cr += floatval($Ent['other_amount']) * floatval($baseCurrRate[0]) / floatval($selCurrRate);
            }

        }//foreach
        $balance = ($ob) + ($dr) - ($cr);
        //$balance=self::dr_cr_balance($balance);
        return $balance;
    }

    //opening balance after converted in pkr
    public static function ob_pkr($df, $ledger_id = 0)
    {
        $date_from = date('Y-m-d', strtotime('-1 day', strtotime($df)));
        $Ledgers = LedgerCurrencies::where('ledger_id', $ledger_id)->get();
        $new_date = date("Y-m-d", strtotime('2020-01-01'));
        $dr = 0;
        $cr = 0;
        $ob = 0;
        foreach ($Ledgers as $ledger) {
            $currencyRate = Currencies::where('id', $ledger->currency_id)->first('rate');
            if ($currencyRate) {
                if (isset($ledger->balance_type) && $ledger->balance_type == 'd') {
                    $ob += $ledger->amount * $currencyRate->rate;
                }
                if (isset($ledger->balance_type) && $ledger->balance_type === 'c') {
                    $ob += -($ledger->amount * $currencyRate->rate);
                }
            }
        }
        $Entries = EntryItems::where('ledger_id', $ledger_id)->whereBetween('voucher_date', [$new_date, $date_from])->get();
        foreach ($Entries as $Ent) {
            $baseCurrRate = Currencies::where('id', $Ent->currence_type)->pluck('rate');
            if ($Ent->dc == 'd') {
                $dr += floatval($Ent['amount']) * floatval($baseCurrRate[0]);
            }
            if ($Ent->dc == 'c') {
                $cr += floatval($Ent['amount']) * floatval($baseCurrRate[0]);
            }

        }//foreach
        $balance = ($ob) + ($dr) - ($cr);
        return $balance;
    }

    // closing balance in pker
    public static function closing_balance_pkr($ledgerID, $cID = 1, $df = "", $dt = "")
    {
        $array = array();
        if ($cID > 0) {
            $Currencies = LedgerCurrencies::where(['ledger_id' => $ledgerID, 'currency_id' => $cID])->get();
        } else {
            $Currencies = LedgerCurrencies::where('ledger_id', $ledgerID)->get();
        }
        $ob = 0;
        $tdr = 0;
        $tcr = 0;
        foreach ($Currencies as $currency) {
            $currency_symbol = Currencies::where('id', $currency->currency_id)->first();
            if ($currency_symbol) {
                $ob = self::opening_balance($dt, $currency_symbol->rate, $ledgerID, $currency->currency_id);

                $dr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => $currency->currency_id, 'dc' => 'd'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');

                $cr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => $currency->currency_id, 'dc' => 'c'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');

                $balance = $ob + $dr - $cr;
                if ($ob > 0) {
                    $tdr = $ob + $dr;
                    $opening_balance = $ob;
                } else {
                    $tcr = $ob - $cr;
                    $opening_balance = $ob;
                }
                $array[] = array($currency_symbol->code, abs($tdr), abs($tcr), $currency_symbol->decimal_fixed_point, $balance, $opening_balance, ($dr), ($cr));


            }
        }
        return $array;
    }

    //fetch ledger's cash in hand which will effect to group id 12
    public static function cash_in_hand()
    {
        $list = '';
        $list = '<option value="">Cash In Hand</option>';
        $Ledgers = Ledgers::where('group_id', 12)->get();
        foreach ($Ledgers as $ledger) {
            $list .= '<option value="' . $ledger->id . '">' . $ledger->name . '</option>';
        }
        return $list;
    }

    //Muhammad Azeem Khalid
    public static function createPVEntry($data)
    {
        DB::beginTransaction();
        try {
            $Ddata['entry_type_id'] = 3;
            $Ddata['voucher_date'] = $data['voucher_date'];
            $Ddata['created_by'] = Auth::user()->id;
            $Ddata['updated_by'] = Auth::user()->id;
            $Ddata['employee_id'] = Auth::user()->id;
            $Ddata['status'] = 0;
            $Ddata['currence_type'] = 1;
            $Ddata['other_currency_type'] = 1;
            DB::enableQueryLog();
            $entry = Entries::create($Ddata);
            $entry->update(array(
                'number' => CoreAccounts::generateNumber($entry->id),
            ));

            $count = count($data['currency_id']);
            for ($i = 0; $i <= $count; $i++) {
                $Currency_rate = Currencies::where('id', $data['currency_id'][$i])->pluck('rate');
                $EntData['entry_type_id'] = 3;
                $EntData['entry_id'] = $entry->id;
                $EntData['ledger_id'] = $data['trans_acc_from'];
                $EntData['voucher_date'] = $data['voucher_date'];
                $EntData['amount'] = $data['amount'][$i];
                $EntData['other_amount'] = $data['amount'][$i];
                if (isset($Currency_rate[0])) {
                    $EntData['rate'] = $Currency_rate[0];
                }
                $EntData['dc'] = 'c';
                $EntData['currence_type'] = $data['currency_id'][$i];
                $EntData['other_currency_type'] = $data['currency_id'][$i];
                $EntData['narration'] = $data['narration'][$i];
                EntryItems::insert($EntData);
                $EntData['ledger_id'] = $data['trans_acc_to'];
                $EntData['dc'] = 'd';
                EntryItems::insert($EntData);
                $entry_items_fetch = EntryItems::where('entry_id', $entry->id)->get();


                foreach ($entry_items_fetch as $item_fetch) {
                    if ($item_fetch->dc == "d") {
                        $ledger = Ledgers::where('id', $item_fetch->ledger_id)->first();
                        $vendor_id = $ledger->parent_type;
                        //                        CoreAccounts::_insert_report_item($entry, $item_fetch, $vendor_id);
                    }
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

    }

    //get closing of ledger
    public static function closing_balance($ledgerID, $cID = 0, $df = "", $dt = "")
    {
        //        $array = array();
//        if ($cID > 0) {
//            $Currencies = LedgerCurrencies::where(['ledger_id' => $ledgerID, 'currency_id' => $cID])->get();
//        } else {
//            $Currencies = LedgerCurrencies::where('ledger_id', $ledgerID)->get();
//        }
        $ob = 0;
        $tdr = 0;
        $tcr = 0;
        //        dd($df, $dt);
//        foreach ($Currencies as $currency) {
        $ob = self::opening_balance($df, 1, $ledgerID, 1);
        //        dd($ob,$df);
        $currency_symbol = Currencies::where('id', 1)->first(['code', 'decimal_fixed_point', 'rate']);
        $dr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => 1, 'dc' => 'd'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');
        $cr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => 1, 'dc' => 'c'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');
        $balance = $ob + $dr - $cr;
        if ($ob > 0) {
            $tdr = $ob + $dr;
            $opening_balance = $ob;
        } else {
            $tcr = $ob - $cr;
            $opening_balance = $ob;
        }
        if ($currency_symbol) {
            $array[] = array($currency_symbol->code, abs($tdr), abs($tcr), $currency_symbol->decimal_fixed_point, $balance, $opening_balance, ($dr), ($cr));

        }
        //        }
        return $array;
    }
    public static function closing_balance1($ledgerID, $cID = 0, $df = "", $dt = "")
    {
        //        $array = array();
//        if ($cID > 0) {
//            $Currencies = LedgerCurrencies::where(['ledger_id' => $ledgerID, 'currency_id' => $cID])->get();
//        } else {
//            $Currencies = LedgerCurrencies::where('ledger_id', $ledgerID)->get();
//        }
        $ob = 0;
        $tdr = 0;
        $tcr = 0;
        //        dd($df, $dt);
//        foreach ($Currencies as $currency) {
//        $ob = self::opening_balance($df, 1, $ledgerID, 1);
        $ob = 0;
        //        dd($ob,$df);
        $currency_symbol = Currencies::where('id', 1)->first(['code', 'decimal_fixed_point', 'rate']);
        $dr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => 1, 'dc' => 'd'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');
        $cr = EntryItems::where(['ledger_id' => $ledgerID, 'currence_type' => 1, 'dc' => 'c'])->whereBetween('voucher_date', [$df, $dt])->get()->sum('amount');
        $balance = $ob + $dr - $cr;
        if ($ob > 0) {
            $tdr = $ob + $dr;
            $opening_balance = $ob;
        } else {
            $tcr = $ob - $cr;
            $opening_balance = $ob;
        }
        if ($currency_symbol) {
            $array[] = array($currency_symbol->code, abs($tdr), abs($tcr), $currency_symbol->decimal_fixed_point, $balance, $opening_balance, ($dr), ($cr));

        }
        //        }
        return $array;
    }
}
