<?php

namespace App\Helpers;

use App\Models\Admin\MixItem_list;
use App\Models\Admin\StockItemsModel;
use App\Models\Admin\Currencies;
use App\Models\Admin\Companies;
use App\Models\Admin\Entries;
use App\Models\Admin\TaxSetting;
use App\Models\Admin\Vendor\Vendor;
use App\Models\Admin\Weight;
use App\Models\Admin\EntryItems;
use App\Models\Admin\Groups;
use App\Models\Admin\ItemsList;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Settings;
use App\Models\Admin\LedgerCurrencies;
use App\Helpers\CoreAccounts;
use App\Models\Purchase\Tagging_detail;
use App\Models\Purchase\GrnDetail;
use App\Models\Purchase\Total_Inventory;
use App\Models\Purchase\InventoryStock;
use App\Models\Admin\LdsPackets;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use Validator;
use Config;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GrnAccountEntries
{
    //Accounts Entries for jewelery...............................
    static function jewleryEntry($request, $grnID)
    {
        $param = $request->all();
        $Ddata['entry_type_id'] = 1;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['currence_type'] = 1;
        $Ddata['other_currency_type'] = 1;
        $Ddata['grnID'] = $grnID;
        $Ddata['status'] = 0;
        $entry = Entries::updateOrCreate(['grnID' => $grnID], $Ddata);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));


        //=====================================Entery Items========================================
        $jewelry = $param['jewelry']['item_category'];
        foreach ($jewelry as $jew => $value) {
            if (isset($param['jewelry']['on_approval'][$jew]) && $param['jewelry']['on_approval'][$jew] == 'on_approval') {
                $supID = Ledgers::where(['group_id' => Config::get('constants.advance_vl'), 'branch_id' => Auth::user()->branch_id])->value('id');

            } else {
                if ($request->supplier_type == 2) {
                    $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_karigar_local'))->value('id');
                } else {
                    $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
                }
            }
            if ($param['jewelry']['gold_type'][$jew] == 'swiss') {
                $curRate = Currencies::where('id', 5)->first(['rate']);
                $currID = 5;
            } else {
                $curRate = Currencies::where('id', 4)->first(['rate']);
                $currID = 4;
            }
            // if($param['jewelry']['item_category'][$jew]==143){

            if ($param['jewelry']['item_category'][$jew] == Config::get('constants.jewelery_sub_cat_id')) {
                $item_category = ItemsList::where('id', $param['jewelry']['item_id'][$jew])->first('id');
                if ($param['jewelry']['shift'][$jew] == 'shift_to_mix') {

                    $stock_ledger = Ledgers::where('id', $param['jewelry']['ledger'][$jew])->first('id');

                } else {
                    $stock_ledger = Ledgers::where(['group_id' => Config::get('constants.stock_for_sale_mix'), 'parent_type' => $item_category->id])->first('id');

                }

            }//end mix data
            else if ($param['jewelry']['shift'][$jew] == 'shift_to_worker') {
                $stock_ledger = Ledgers::where('parent_type', $param['jewelry']['shift_to_karigar'][$jew])->where('group_id', Config::get('constants.acounts_karigar_local'))->first('id');
            } else if ($param['jewelry']['shift'][$jew] == 'shift_to_mix') {

                $stock_ledger = Ledgers::where('id', $param['jewelry']['ledger'][$jew])->first('id');
                //                dd($param['jewelry']['ledger'][$jew]);
            } else {

                $itemList = ItemsList::where('id', $param['jewelry']['item_id'][$jew])->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);
                $group = Groups::where('parent_id', 17)->where('parent_type', $itemList->item_category)->first('id');
                $stock_ledger = Ledgers::where(['parent_type' => $itemList->item_subcategory, 'group_id' => $group->id])->first('id');
            }

            //stock entry
            $entData['grnID'] = $grnID;
            $entData['ledger_id'] = $stock_ledger->id;
            $entData['voucher_date'] = date('Y-m-d');
            $entData['entry_type_id'] = 7;
            $entData['entry_id'] = $entry->id;
            $entData['amount'] = $param['jewelry']['total_pure_payable_weight'][$jew] * $curRate->rate;
            $entData['currence_type'] = 1;
            $entData['other_amount'] = $param['jewelry']['total_pure_payable_weight'][$jew];
            $entData['other_currency_type'] = $currID;
            $entData['rate'] = $curRate->rate;
            $entData['dc'] = 'd';
            $entData['narration'] = 'Jewelery Has been Purchase';
            EntryItems::create($entData);
            $entData['amount'] = $param['jewelry']['grand_total_pkr'][$jew];
            $entData['other_currency_type'] = 1;
            $entData['other_amount'] = $param['jewelry']['grand_total_pkr'][$jew];
            $entData['rate'] = 1;
            $entData['dc'] = 'd';

            EntryItems::create($entData);
            //lds entry
            if (isset($param['jewelry']['gems']) && count($param['jewelry']['gems']) > 0) {
                $gems = $param['jewelry']['gems']['gems_type'];
                foreach ($gems as $gm => $value) {

                    $currency = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->first(['id', 'rate']);
                    if (isset($param['jewelry']['gems']['own_gems'][$gm])) {
                        if ($param['jewelry']['gems']['type'][$gm] == 0) {
                            $total_inventory = Total_Inventory::where('lds_packet_no', $param['jewelry']['gems']['gems_name'][$gm])->first();
                            $ldspacket = LdsPackets::where('id', $total_inventory->lds_packet_no)->first();
                            $Group = Groups::where(['parent_id' => 84])->where(['parent_type' => $ldspacket['packet_category']])->first();
                            $cr_ledger = Ledgers::where(['parent_type' => $ldspacket->id, 'group_id' => $Group->id])->first('id');
                            $cr_ledger = $cr_ledger->id;
                            $entData['ledger_id'] = $cr_ledger;

                            $entData['currence_type'] = 1;
                            $entData['other_currency_type'] = $currency['id'];
                            $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                            if ($currency['id'] == 1) {
                                $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm];
                            } else {
                                $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                            }
                            //                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];

                            //                            $entData['amount'] = ($param['jewelry']['gems']['pkr_rate'][$gm]);
                            $entData['amount'] = $entData['other_amount'] * $entData['rate'];

                            $entData['dc'] = 'c';
                            EntryItems::create($entData);
                            $entData['ledger_id'] = $stock_ledger->id;
                            $entData['dc'] = 'd';
                            $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                            EntryItems::create($entData);

                        } else if ($param['jewelry']['gems']['type'][$gm] == 1) {

                            $tag = Tagging_detail::with('itemShortName')->where('tag_number', $param['jewelry']['gems']['gems_name'][$gm])->get();


                            $inventory = InventoryStock::where('tag_no', $param['jewelry']['gems']['gems_name'][$gm])->first();

                            $entData['ledger_id'] = $inventory->ledger_id;
                            if ($param['jewelry']['gems']['gems_currency'][$gm] == "USD") {

                                $entData['amount'] = ($param['jewelry']['gems']['dollar_rate'][$gm]);

                            } else {
                                $entData['amount'] = ($param['jewelry']['gems']['pkr_rate'][$gm]);

                            }


                            $entData['currence_type'] = 1;
                            $entData['other_currency_type'] = $currency['id'];
                            $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                            if ($currency['id'] == 1) {
                                $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm];
                            } else {
                                $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                            }
                            //                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                            $entData['dc'] = 'c';
                            EntryItems::create($entData);

                            $entData['ledger_id'] = $stock_ledger->id;
                            $entData['dc'] = 'd';
                            $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                            EntryItems::create($entData);


                        }


                    } else {


                        $entData['ledger_id'] = $stock_ledger->id;
                        if (Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('code') == "PKR") {
                            $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm];

                        } else {
                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];

                        }

                        // $entData['amount'] = ($param['jewelry']['gems']['pkr_rate'][$gm]);
                        $entData['currence_type'] = 1;
                        $entData['other_currency_type'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('id');
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                        $entData['amount'] = $entData['other_amount'] * $entData['rate'];
                        //                        dd($entData);

                        if (Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('code') == "PKR") {
                            $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm] ?? 0;
                        } else {
                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm] ?? 0;
                        }

                        $entData['dc'] = 'd';

                        EntryItems::create($entData);
                        $entData['ledger_id'] = $supID;
                        $entData['dc'] = 'c';
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['gems_currency'][$gm])->value('rate');
                        EntryItems::create($entData);
                    }
                }
            }//gems end................
            //vendor entry items
            $entData['ledger_id'] = $supID;
            $entData['amount'] = ($param['jewelry']['total_pure_payable_weight'][$jew] * $curRate->rate);
            $entData['other_currency_type'] = $currID;
            $entData['other_amount'] = $param['jewelry']['total_pure_payable_weight'][$jew];
            $entData['rate'] = $curRate->rate;
            $entData['dc'] = 'c';
            EntryItems::create($entData);
            $entData['amount'] = $param['jewelry']['grand_total_pkr'][$jew];
            $entData['other_currency_type'] = 1;
            $entData['other_amount'] = $param['jewelry']['grand_total_pkr'][$jew];
            $entData['rate'] = 1;
            $a = EntryItems::create($entData);


        }//end foreach

    }

    //=====================================================pure gold entry
    static function PureGoldEntry($data, $key, $total_gold_weight = 0, $grnID)
    {
        $param = $data->all();
        //calculate per gram rate of gold
        $itemList = ItemsList::where('id', $param['pure_gold']['item_id'][$key])->first();
        $itemCurrency = Currencies::where('code', $itemList->currency)->first(['id', 'rate']);
        $perGram_rate = $data->total_grn_amount * $itemCurrency->rate / $total_gold_weight;
        $Stock_ledger = Groups::where(['parent_id' => 18])->where(['parent_type' => $data->grn_type])->first();
        $Assets_id = $Stock_ledger->id;
        $transit_ledger = Ledgers::where('parent_type', $itemList->item_subcategory)->where('group_id', $Assets_id)->first();
        $vendor_ledger = Ledgers::where('parent_type', $data->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->first();
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['currence_type'] = $itemCurrency->id;
        $Ddata['other_currency_type'] = 3;
        $Ddata['grnID'] = $grnID;
        $Ddata['status'] = 0;
        $entry = Entries::create($Ddata);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));
        //stock entry
        $entData['grnID'] = $grnID;
        $entData['ledger_id'] = $transit_ledger->id;
        $entData['voucher_date'] = date('Y-m-d');
        $entData['entry_type_id'] = 7;
        $entData['entry_id'] = $entry->id;
        $entData['amount'] = $data->total_grn_amount;
        $entData['currence_type'] = $itemCurrency->id;
        $entData['other_amount'] = $total_gold_weight;
        $entData['other_currency_type'] = 3;
        $entData['rate'] = $perGram_rate;
        $entData['dc'] = 'd';
        $entData['narration'] = 'Pure Gold Has been purchase against Gold Weight ' . $total_gold_weight . '';
        EntryItems::create($entData);
        //vendor entry
        $entData['ledger_id'] = $vendor_ledger->id;
        $entData['dc'] = 'c';
        $entData['amount'] = $param['pure_gold']['item_list_total_price'][$key];
        $entData['other_currency_type'] = $itemCurrency->id;
        $entData['rate'] = $itemCurrency->rate;
        $entData['other_amount'] = $param['pure_gold']['item_list_total_price'][$key];
        EntryItems::create($entData);
        if ($param['import_type'] == 'none_official') {
            //agent entry
            $vendorType = Vendor::where('id', $data->agent)->value('vendor_type');
            $supID = Ledgers::where('parent_type', $data->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $data->agent)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $data->agent)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $entData['ledger_id'] = $agent;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $itemCurrency->id;
            $entData['amount'] = $data->carrier_charges_rate_amount;
            $entData['other_currency_type'] = Currencies::where('code', $data->carrier_charges_currency)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $data->carrier_charges_currency)->first('rate')->rate;
            $entData['other_amount'] = $data->carrier_charges_rate;
            $entData['narration'] = 'Agent Charges Has been paid while purchase against Gold Weight ' . $total_gold_weight . '';

            EntryItems::create($entData);
            //freight charges entry
            $entData['ledger_id'] = $vendor_ledger->id;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $itemCurrency->id;
            $entData['amount'] = $data->freight_charges_amount;
            $entData['other_currency_type'] = Currencies::where('code', $data->freight_charges_currency)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $data->freight_charges_currency)->first('rate')->rate;
            $entData['other_amount'] = $data->freight_charges_rate;
            $entData['narration'] = 'Freight Charges Has been paid while purchase against Gold Weight ' . $total_gold_weight . '';
            EntryItems::create($entData);
            //miscellaneous charges entry
            $entData['ledger_id'] = $vendor_ledger->id;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $itemCurrency->id;
            $entData['amount'] = $data->misc_charges_rate_amount;
            $entData['other_currency_type'] = Currencies::where('code', $data->misc_charges_currency)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $data->misc_charges_currency)->first('rate')->rate;
            $entData['other_amount'] = $data->misc_charges_rate;
            $entData['narration'] = 'Miscellaneous Charges Has been paid while purchase against Gold Weight ' . $total_gold_weight . '';
            EntryItems::create($entData);
        }
        if ($param['import_type'] == 'official') {
            //agent entry
            $vendorType = Vendor::where('id', $data->agent_id)->value('vendor_type');
            $supID = Ledgers::where('parent_type', $data->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $data->agent_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $data->agent_id)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $entData['ledger_id'] = $agent;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $itemCurrency->id;
            $entData['amount'] = $data->carrier_charges_rate_amount_official;
            $entData['other_currency_type'] = Currencies::where('code', $data->carrier_charges_currency_official)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $data->carrier_charges_currency_official)->first('rate')->rate;
            $entData['other_amount'] = $data->carrier_charges_rate_offcial;
            $entData['narration'] = 'Agent Charges Has been paid while purchase against Gold Weight ' . $total_gold_weight . '';
            EntryItems::create($entData);
            //taxes and duties paid to agent
            $entData['dc'] = 'c';
            $entData['amount'] = $data->off_imp_grand_total_duty + $data->off_imp_grand_total_tax + $data->off_imp_grand_total_misc;
            $entData['other_currency_type'] = 1;
            $entData['rate'] = 1;
            $entData['other_amount'] = $data->off_duty_total + $data->off_imp_total_tax + $data->off_imp_other;
            $entData['narration'] = 'Charges Has been paid';
            EntryItems::create($entData);
            //freight charges entry
            $entData['ledger_id'] = $vendor_ledger->id;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $itemCurrency->id;
            $entData['amount'] = $data->freight_charges_amount_offcial;
            $entData['other_currency_type'] = Currencies::where('code', $data->freight_charges_currency_official)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $data->freight_charges_currency_official)->first('rate')->rate;
            $entData['other_amount'] = $data->freight_charges_rate_official;
            $entData['narration'] = 'Freight Charges Has been paid while purchase against Gold Weight ' . $total_gold_weight . '';
            EntryItems::create($entData);
        }
    }

    //create watches entries
    static function watchEntry($request, $grnID)
    {
        $param = $request->all();
        $totalQty = 0;
        $net_expesne = 0;
        $total_item = 0;
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['grnID'] = $grnID;
        $Ddata['other_currency_type'] = 1;
        $Entry = Entries::create($Ddata);
        $entID = $Entry->id;
        $detail = $param['watch']['item_name_watch'];
        foreach ($detail as $key => $value) {
            $itemList = ItemsList::where('id', $param['watch']['item_name_watch'][$key])->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);
            $baseCurrency = Currencies::where('code', $itemList->currency)->first('id');
            $curRate = Currencies::where('code', $itemList->currency)->value('rate');
            $totalQty += $param['watch']['quantity'][$key];
            $brand = $itemList->brand;
            //stock in process item
            $group = Groups::where('parent_id', 17)->where('parent_type', $itemList->item_category)->first('id');
            $Stock_ledger = Ledgers::where(['parent_type' => $itemList->item_subcategory, 'group_id' => $group->id])->first('id');
            $Stock_ledger = $Stock_ledger->id;
            $total_expense = 0;
            if (isset($request->total_expense)) {
                $total_expense = $request->total_expense;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['watch']['remaining_price'][$key];
                $grand_total = ((isset($param['watch']['remaining_price'][$key])) ? $param['watch']['remaining_price'][$key] : $param['watch']['item_list_total_price'][$key]);
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }
            if (isset($request->total_expense_official)) {
                $total_expense = $request->total_expense_official;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['watch']['remaining_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }
            //stock dr entry
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $Stock_ledger;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['dc'] = 'd';
            $EntData['narration'] = 'Brand: ' . $brand . ', Qty:' . $totalQty . ' Watches purchase has been done';
            if (!isset($param['watch']['convention'][$key])) {
                //gold expense cost
                $total_amount = $request->total_grn_amount - $total_expense;
                $gold_grand_total = $param['watch']['total_gold_price'][$key];
                $Gpercentage = $total_expense / $total_amount;
                $net_gold_expesne = $gold_grand_total * $Gpercentage;
                $EntData['rate'] = ($param['watch']['total_gold_price'][$key] + $net_gold_expesne) / ($param['watch']['watch_pure_weight'][$key]) * (Currencies::where('id', $baseCurrency->id)->first('rate')->rate);
                $EntData['amount'] = $param['watch']['total_gold_price'][$key] + $net_gold_expesne;
                $EntData['other_currency_type'] = 3;
                $EntData['other_amount'] = $param['watch']['watch_pure_weight'][$key];
                EntryItems::create($EntData);
            }
            $EntData['rate'] = $curRate;
            if (isset($param['watch']['convention'][$key])) {
                $EntData['amount'] = $param['watch']['item_list_total_price'][$key] + $net_expesne;
                $EntData['other_amount'] = $param['watch']['item_list_total_price'][$key] + $net_expesne;
            } else {
                $EntData['amount'] = $param['watch']['remaining_price'][$key] + $net_expesne;
                $EntData['other_amount'] = $param['watch']['remaining_price'][$key] + $net_expesne;
            }
            $EntData['other_currency_type'] = $baseCurrency->id;
            EntryItems::create($EntData);
            $total_item += $param['watch']['total_unit_retail_price'][$key];
        }
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
            'currence_type' => $baseCurrency->id
        ));
        //Accounts Entries
        $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
        if (isset($request->import_type) && $request->import_type == 'none_official') {
            $vendorType = Vendor::where('id', $request->agent)->value('vendor_type');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            //agent entry item
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $agent;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency)->value('id');
            $EntData['other_amount'] = $request->carrier_charges_rate;
            $EntData['dc'] = 'c';
            $EntData['narration'] = 'Commission paid to agent when Watch purchase has been done';
            EntryItems::create($EntData);
            //freight charges currency
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->freight_charges_amount;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency)->value('id');
            $EntData['other_amount'] = $request->freight_charges_rate;
            $EntData['narration'] = 'Freight Charges paid when Watch purchase has been done';
            EntryItems::create($EntData);
            //miscleneous charges
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->misc_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->misc_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->misc_charges_currency)->value('id');
            $EntData['other_amount'] = $request->misc_charges_rate;
            $EntData['narration'] = 'Miscellaneous Charges paid when Watch purchase has been done';
            EntryItems::create($EntData);
        }
        if (isset($request->import_type) && $request->import_type == 'official') {
            //agent entry
            $vendorType = Vendor::where('id', $request->agent_id)->value('vendor_type');
            $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $EntData['ledger_id'] = $agent;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount_official;
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->carrier_charges_rate_offcial;
            $EntData['narration'] = 'Agent Charges Has been paid';
            EntryItems::create($EntData);
            //taxes and duties paid to agent
            $EntData['dc'] = 'c';
            $EntData['amount'] = $request->off_imp_grand_total_duty + $request->off_imp_grand_total_tax + $request->off_imp_grand_total_misc;
            $EntData['other_currency_type'] = 1;
            $EntData['rate'] = 1;
            $EntData['other_amount'] = $request->off_duty_total + $request->off_imp_total_tax + $request->off_imp_other;
            $EntData['narration'] = 'Charges Has been paid';
            EntryItems::create($EntData);
            //freight charges entry
            $EntData['ledger_id'] = $supID;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->freight_charges_amount_offcial;
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->freight_charges_rate_official;
            $EntData['narration'] = 'Freight Charges Has been paid ';
            EntryItems::create($EntData);
        }
        //vendor entry
        $EntData['ledger_id'] = $supID;
        $EntData['dc'] = 'c';
        $EntData['amount'] = $total_item;
        $EntData['rate'] = Currencies::where('id', $baseCurrency)->value('rate');
        $EntData['other_currency_type'] = $baseCurrency->id;
        $EntData['other_amount'] = $total_item;
        $EntData['narration'] = 'Brand: ' . $brand . ', Qty:' . $totalQty . ' Watches purchase has been done';
        EntryItems::create($EntData);
    }

    //create accessories entry
    static function AccessoriesEntry($request, $grnID)
    {
        $param = $request->all();
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['grnID'] = $grnID;
        $Ddata['currence_type'] = 1;
        $Ddata['other_currency_type'] = 1;
        $Entry = Entries::create($Ddata);
        $entID = $Entry->id;
        $net_expesne = 0;
        $detail = $param['accessory']['item_id'];
        $totalQty = 0;
        $net_total = 0;
        foreach ($detail as $key => $value) {
            $itemList = ItemsList::where('id', $param['accessory']['item_id'][$key])
                ->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);

            $baseCurrency = Currencies::where('code', $itemList->currency)->first('id');
            $curRate = Currencies::where('code', $itemList->currency)->value('rate');
            $totalQty += $param['accessory']['quantity'][$key];
            $itemSubCategory = ItemsList::where('id', $param['accessory']['item_id'][$key])->first(['item_subcategory', 'item_category', 'brand']);
            $brand = $itemSubCategory['brand'];
            $group = Groups::where('parent_id', 17)->where('parent_type', $itemSubCategory['item_category'])->first('id');
            $Stock_ledger = Ledgers::where(['parent_type' => $itemSubCategory['item_subcategory'], 'group_id' => $group->id])->first('id');
            $Stock_ledger = $Stock_ledger->id;
            //stock dr entry
            if (isset($request->total_expense)) {
                $total_expense = $request->total_expense;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['accessory']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }
            if (isset($request->total_expense_official)) {
                $total_expense = $request->total_expense_official;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['accessory']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }
            $net_total += $param['accessory']['item_list_total_price'][$key];
            //stock entry item wise
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $Stock_ledger;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['rate'] = $curRate;
            $EntData['amount'] = $param['accessory']['item_list_total_price'][$key] + $net_expesne;
            $EntData['other_currency_type'] = $baseCurrency->id;
            $EntData['other_amount'] = $param['accessory']['item_list_total_price'][$key] + $net_expesne;
            $EntData['dc'] = 'd';
            $EntData['narration'] = 'Brand ' . $brand . ', Qty:' . $totalQty . ' Accessories purchase has been done';
            EntryItems::create($EntData);
        }//end foreach
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
            'currence_type' => $baseCurrency->id
        ));
        $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
        //General Entries while Accessories tagging
        if (isset($request->import_type) && $request->import_type == 'none_official') {
            //agent entry item
            $vendorType = Vendor::where('id', $request->agent)->value('vendor_type');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $EntData['ledger_id'] = $agent;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency)->value('id');
            $EntData['other_amount'] = $request->carrier_charges_rate;
            $EntData['dc'] = 'c';
            $EntData['narration'] = 'Commission paid to agent when Accessories purchase has been done';
            EntryItems::create($EntData);
            //freight chagres
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->freight_charges_amount;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency)->value('id');
            $EntData['other_amount'] = $request->freight_charges_rate;
            $EntData['narration'] = 'Freight Charges paid when Accessories purchase has been done';
            EntryItems::create($EntData);
            //miscleneous charges
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->misc_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->misc_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->misc_charges_currency)->value('id');
            $EntData['other_amount'] = $request->misc_charges_rate;
            $EntData['narration'] = 'Miscellaneous Charges paid when Accessories purchase has been done';

            EntryItems::create($EntData);
        }
        if ($param['import_type'] == 'official') {
            //agent entry
            $vendorType = Vendor::where('id', $request->agent_id)->value('vendor_type');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $entData['voucher_date'] = date('Y-m-d');
            $entData['grnID'] = $grnID;
            $entData['ledger_id'] = $agent;
            $entData['entry_id'] = $Entry->id;
            $entData['entry_type_id'] = 7;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $baseCurrency->id;
            $entData['amount'] = $request->carrier_charges_rate_amount_official;
            $entData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('rate')->rate;
            $entData['other_amount'] = $request->carrier_charges_rate_offcial;
            $entData['narration'] = 'Agent Charges Has been paid';
            EntryItems::create($entData);
            //taxes and duties paid to agent
            $entData['dc'] = 'c';
            $entData['amount'] = $request->off_imp_grand_total_duty + $request->off_imp_grand_total_tax + $request->off_imp_grand_total_misc;
            $entData['other_currency_type'] = 1;
            $entData['rate'] = 1;
            $entData['other_amount'] = $request->off_duty_total + $request->off_imp_total_tax + $request->off_imp_other;
            $entData['narration'] = 'Charges Has been paid';
            EntryItems::create($entData);
            //freight charges entry
            $entData['ledger_id'] = $supID;
            $entData['dc'] = 'c';
            $entData['currence_type'] = $supID;
            $entData['amount'] = $request->freight_charges_amount_offcial;
            $entData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency_official)->first('id')->id;
            $entData['rate'] = Currencies::where('code', $request->freight_charges_currency_official)->first('rate')->rate;
            $entData['other_amount'] = $request->freight_charges_rate_official;
            $entData['narration'] = 'Freight Charges Has been paid';
            EntryItems::create($entData);
        }
        //vendor entry
        $EntData['ledger_id'] = $supID;
        $EntData['dc'] = 'c';
        $EntData['amount'] = $net_total;
        $EntData['rate'] = Currencies::where('id', $baseCurrency->id)->value('rate');
        $EntData['other_currency_type'] = $baseCurrency->id;
        $EntData['other_amount'] = $net_total;
        $EntData['narration'] = 'Brand: ' . $brand . ', Qty:' . $totalQty . ' Accessories purchase has been done';
        EntryItems::create($EntData);
    }

    static function LdsEntry($request, $grnID)
    {

        $param = $request->all();
        $net_expesne = 0;
        $acc_data = array();
        $stock_data = array();
        //        dd($param['lds']['grn_currency']);
        $Exchange_rate = Currencies::select('id', 'rate')->where('code', $param['lds']['grn_currency'][1])->first();
        //create new entry
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['grnID'] = $grnID;
        $Ddata['currence_type'] = 2;
        $Ddata['other_currency_type'] = 1;
        $Entry = Entries::create($Ddata);
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
        ));
        $entID = $Entry->id;
        $totalQty = 0;
        $item_total = 0;
        $detail = $param['lds']['item_id'];
        foreach ($detail as $key => $value) {
            $itemList = ItemsList::where('id', $param['lds']['item_id'][$key])
                ->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);
            $baseCurrency = Currencies::where('code', $itemList->currency)->first('id');
            if (isset($param['lds']['shift_to_tagging'][$key])) {
                $shift_to_tagging = $param['lds']['shift_to_tagging'][$key];
            }
            ;
            //            if (isset($param['lds']['parcel_number'][$key])) {
//                $parcel_number = $param['lds']['parcel_number'][$key];
//            };
            $totalQty += $param['lds']['quantity'][$key];
            $itemCurrency = Currencies::where('code', $itemList->currency)->value('id');
            if (isset($request->total_expense)) {
                $total_expense = $request->total_expense;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['lds']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }
            if (isset($request->total_expense_official)) {
                $total_expense = $request->total_expense_official;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['lds']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;
            }

            //per packet entry item....
            if ($shift_to_tagging == 'Inventory') {
                $stock_itemsID = Groups::where(['parent_id' => Config::get('constants.acounts_sale_pack')])->where(['parent_type' => $request->lds_type])->value('id');
                $parcel_number = LdsPackets::where('item_id', $param['lds']['item_id'][$key])->first();
                $Ineventory_items = Ledgers::where('parent_type', $parcel_number->id)->where('group_id', $stock_itemsID)->value('id');
                //
//                $ldspacket = LdsPackets::where('id', $data['good_issuance']['item_id'][$key])->first();
//                $Group = Groups::where(['parent_id' => 88])->where(['parent_type' => $ldspacket['packet_category']])->first();
//                $dr_ledger = Ledgers::where(['parent_type' => $data['good_issuance']['item_id'][$key], 'group_id' => $Group->id])->first('id');
//                $item=ItemsList::where('id',$ldspacket->item_id)->first();


            } else {
                //$stock_itemsID =  Groups::where(['parent_id' => Config::get('constants.acounts_process_tag')])->where(['parent_type' => $data['lds_type']])->value('id');
                //$Ineventory_items = Ledgers::where('group_id',$stock_itemsID)->value('id');
                //$Ineventory_items =$param['lds']['stock_ledger_id'][$key] ;
                $Ineventory_items = $param['lds']['stock_ledger_id'][$key];

            }
            if ($shift_to_tagging == 'Inventory') {
                //                dd($Ineventory_items);
                if ($Ineventory_items) {


                    $EntData['grnID'] = $grnID;
                    $EntData['ledger_id'] = $Ineventory_items;
                    $EntData['entry_type_id'] = 7;
                    $EntData['entry_id'] = $entID;
                    $EntData['voucher_date'] = date('Y-m-d');
                    $EntData['currence_type'] = $baseCurrency->id;
                    $EntData['amount'] = $param['lds']['item_list_total_price'][$key] + $net_expesne;
                    $EntData['rate'] = Currencies::where('code', $request->grn_currency)->value('rate');
                    $EntData['other_currency_type'] = $baseCurrency->id;
                    $EntData['other_amount'] = $param['lds']['item_list_total_price'][$key] + $net_expesne;
                    $EntData['dc'] = 'd';
                    $EntData['narration'] = 'Qty ' . $param['lds']['quantity'][$key] . ' LDS purchase has been done';
                    EntryItems::create($EntData);
                }
            } else {

                $EntData['grnID'] = $grnID;
                $EntData['ledger_id'] = $Ineventory_items;
                $EntData['entry_type_id'] = 7;
                $EntData['entry_id'] = $entID;
                $EntData['voucher_date'] = date('Y-m-d');
                $basec = 1;
                if ($baseCurrency != null) {
                    $basec = $baseCurrency->id;
                }
                $EntData['currence_type'] = $basec;
                $EntData['amount'] = $param['lds']['item_list_total_price'][$key] + $net_expesne;
                $EntData['rate'] = Currencies::where('code', $request->grn_currency)->value('rate');
                $EntData['other_currency_type'] = $basec;
                $EntData['other_amount'] = $param['lds']['item_list_total_price'][$key] + $net_expesne;
                $EntData['dc'] = 'd';
                $EntData['narration'] = 'Qty ' . $param['lds']['quantity'][$key] . ' LDS purchase has been done';
                EntryItems::create($EntData);
            }

            $item_total += $param['lds']['item_list_total_price'][$key];

        }//end foreach
        $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
        if (isset($request->import_type) && $request->import_type == 'none_official') {
            $vendorType = Vendor::where('id', $request->agent)->value('vendor_type');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            //agent entry item
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $agent;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency)->value('id');
            $EntData['other_amount'] = $request->carrier_charges_rate;
            $EntData['dc'] = 'c';
            $EntData['narration'] = 'Commission paid to agent when LDS purchase has been done';
            EntryItems::create($EntData);
            //freight charges currency
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->freight_charges_amount;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency)->value('id');
            $EntData['other_amount'] = $request->freight_charges_rate;
            $EntData['narration'] = 'Freight Charges paid when LDS purchase has been done';
            EntryItems::create($EntData);
            //miscleneous charges
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->misc_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->misc_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->misc_charges_currency)->value('id');
            $EntData['other_amount'] = $request->misc_charges_rate;
            $EntData['narration'] = 'Miscellaneous Charges paid when LDS purchase has been done';
            EntryItems::create($EntData);
        }
        if (isset($request->import_type) && $request->import_type == 'official') {
            //agent entry
            $vendorType = Vendor::where('id', $request->agent_id)->value('vendor_type');
            $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $agent;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount_official;
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->carrier_charges_rate_offcial;
            $EntData['narration'] = 'Agent Charges Has been paid';
            EntryItems::create($EntData);
            //taxes and duties paid to agent
            $EntData['dc'] = 'c';
            $EntData['amount'] = $request->off_imp_grand_total_duty + $request->off_imp_grand_total_tax + $request->off_imp_grand_total_misc;
            $EntData['other_currency_type'] = 1;
            $EntData['rate'] = 1;
            $EntData['other_amount'] = $request->off_duty_total + $request->off_imp_total_tax + $request->off_imp_other;
            $EntData['narration'] = 'Charges Has been paid';
            EntryItems::create($EntData);
            //freight charges entry
            $EntData['ledger_id'] = $supID;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->freight_charges_amount_offcial;
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->freight_charges_rate_official;
            $EntData['narration'] = 'Freight Charges Has been paid ';
            EntryItems::create($EntData);
        }
        //supplier entry


        $EntData['grnID'] = $grnID;
        $EntData['ledger_id'] = $supID;
        $EntData['entry_type_id'] = 7;
        $EntData['entry_id'] = $entID;
        $EntData['voucher_date'] = date('Y-m-d');
        $EntData['amount'] = $item_total;
        $EntData['rate'] = Currencies::where('code', $request->grn_currency)->value('rate');
        $basec = 1;
        if ($baseCurrency != null) {
            $basec = $baseCurrency->id;
        }

        $EntData['other_currency_type'] = $basec;
        $EntData['other_amount'] = $item_total;
        $EntData['dc'] = 'c';
        $EntData['narration'] = 'Qty ' . $param['lds']['quantity'][$key] . ' LDS purchase has been done';
        EntryItems::create($EntData);

        \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
            var_dump($query->bindings);
            var_dump($query->time);
            Log::info($query->sql, ",", $query->bindings, ",", $query->time);
            dd("x");
        });

    }

    static function PackagingEntry($request, $grnID)
    {
        $param = $request->all();
        $totalQty = 0;
        //create new entry
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['grnID'] = $grnID;
        $Ddata['other_currency_type'] = 1;
        $Entry = Entries::create($Ddata);
        $entID = $Entry->id;
        $detail = $param['packaging']['item_id'];
        $item_total = 0;
        $net_expesne = 0;
        foreach ($detail as $key => $value) {
            $itemList = ItemsList::where('id', $param['packaging']['item_id'][$key])->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);
            $baseCurrency = Currencies::where('code', $itemList->currency)->first('id');
            $curRate = Currencies::where('code', $itemList->currency)->value('rate');
            //get stock ledger wiht item short code
            $itemSubCategory = ItemsList::where('id', $itemList->id)->first(['item_subcategory', 'item_category']);
            $group = Groups::where('parent_id', 17)->where('parent_type', $itemList->item_category)->first('id');
            $Stock_ledger = Ledgers::where(['parent_type' => $itemList->item_subcategory, 'group_id' => $group->id])->first('id');
            $Stock_ledger = $Stock_ledger->id;

            $totalQty += $param['packaging']['qty'][$key];
            // set formula formula
            if (isset($request->total_expense)) {
                $total_expense = $request->total_expense;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['packaging']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;

            }
            if (isset($request->total_expense_official)) {
                $total_expense = $request->total_expense_official;
                $total_amount = $request->total_grn_amount - $total_expense;
                $grand_total = $param['packaging']['item_list_total_price'][$key];
                $percentage = $total_expense / $total_amount;
                $net_expesne = $grand_total * $percentage;

            }
            //stock entry item wise
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $Stock_ledger;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['rate'] = $curRate;
            $EntData['amount'] = $param['packaging']['item_list_total_price'][$key] + $net_expesne;
            $EntData['other_currency_type'] = $baseCurrency->id;
            $EntData['other_amount'] = $param['packaging']['item_list_total_price'][$key] + $net_expesne;
            $EntData['dc'] = 'd';
            $EntData['narration'] = 'Qty:' . $param['packaging']['qty'][$key] . ' Packaging purchase has been done';
            EntryItems::create($EntData);
            $item_total += $param['packaging']['item_list_total_price'][$key];
        }//end foreach
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
            'currence_type' => $baseCurrency->id
        ));
        $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
        if (isset($request->import_type) && $request->import_type == 'none_official') {
            $vendorType = Vendor::where('id', $request->agent)->value('vendor_type');
            $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            //agent entry item
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $agent;
            $EntData['entry_type_id'] = 7;
            $EntData['entry_id'] = $entID;
            $EntData['voucher_date'] = date('Y-m-d');
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency)->value('id');
            $EntData['other_amount'] = $request->carrier_charges_rate;
            $EntData['dc'] = 'c';
            $EntData['narration'] = 'Commission paid to agent when Packaging purchase has been done';
            EntryItems::create($EntData);
            //freight chagres
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->freight_charges_rate;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency)->value('id');
            $EntData['other_amount'] = $request->freight_charges_rate;
            $EntData['narration'] = 'Freight Charges paid when Packaging purchase has been done';
            EntryItems::create($EntData);
            //miscleneous charges
            $EntData['ledger_id'] = $supID;
            $EntData['amount'] = $request->misc_charges_rate_amount;
            $EntData['rate'] = Currencies::where('code', $request->misc_charges_currency)->value('rate');
            $EntData['other_currency_type'] = Currencies::where('code', $request->misc_charges_currency)->value('id');
            $EntData['other_amount'] = $request->misc_charges_rate;
            $EntData['narration'] = 'Miscellaneous Charges paid when Packaging purchase has been done';
            EntryItems::create($EntData);
        }
        if (isset($request->import_type) && $request->import_type == 'official') {
            //agent entry
            $vendorType = Vendor::where('id', $request->agent_id)->value('vendor_type');
            if ($vendorType == 1) {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->value('id');
            } else {
                $agent = Ledgers::where('parent_type', $request->agent_id)->where('group_id', Config::get('constants.acounts_Agents_local'))->value('id');
            }
            $EntData['grnID'] = $grnID;
            $EntData['ledger_id'] = $agent;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->carrier_charges_rate_amount_official;
            $EntData['other_currency_type'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->carrier_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->carrier_charges_rate_offcial;
            $EntData['narration'] = 'Agent Charges Has been paid';
            EntryItems::create($EntData);
            //taxes and duties paid to agent
            $EntData['dc'] = 'c';
            $EntData['amount'] = $request->off_imp_grand_total_duty + $request->off_imp_grand_total_tax + $request->off_imp_grand_total_misc;
            $EntData['other_currency_type'] = 1;
            $EntData['rate'] = 1;
            $EntData['other_amount'] = $request->off_duty_total + $request->off_imp_total_tax + $request->off_imp_other;
            $EntData['narration'] = 'Charges Has been paid';
            EntryItems::create($EntData);
            //freight charges entry
            $EntData['ledger_id'] = $supID;
            $EntData['dc'] = 'c';
            $EntData['currence_type'] = $baseCurrency->id;
            $EntData['amount'] = $request->freight_charges_amount_offcial;
            $EntData['other_currency_type'] = Currencies::where('code', $request->freight_charges_currency_official)->first('id')->id;
            $EntData['rate'] = Currencies::where('code', $request->freight_charges_currency_official)->first('rate')->rate;
            $EntData['other_amount'] = $request->freight_charges_rate_official;
            $EntData['narration'] = 'Freight Charges Has been paid ';
            EntryItems::create($EntData);
        }
        //vendor entry
        $EntData['ledger_id'] = $supID;
        $EntData['dc'] = 'c';
        $EntData['amount'] = $item_total;
        $EntData['rate'] = $curRate;
        $EntData['other_currency_type'] = $baseCurrency->id;
        $EntData['other_amount'] = $item_total;
        $EntData['narration'] = 'Qty:' . $totalQty . ' Packaging purchase has been done';
        EntryItems::create($EntData);
    }

    //======================================================After soten entry=====================================================
    static function afterStone_entry($request, $id)
    {
        $param = $request->all();


        //reverse entry karigar to in process
        //create new entry
        $Ddata['entry_type_id'] = 7;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['currence_type'] = 1;
        $Ddata['other_currency_type'] = 1;
        $Entry = Entries::create($Ddata);
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
        ));
        $entID = $Entry->id;

        $grnDetails = GrnDetail::where('id', $id)->first();

        $uID = $id . '101';
        if ($grnDetails->gold_type == 'swiss') {
            $curRate = Currencies::where('id', 5)->first(['rate']);
            $currID = 5;
        } else {
            $curRate = Currencies::where('id', 4)->first(['rate']);
            $currID = 4;
        }


        $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_supplier_local'))->first('id');
        if ($supID == null) {
            $supID = Ledgers::where('parent_type', $request->supplier_id)->where('group_id', Config::get('constants.acounts_karigar_local'))->first('id');
        }


        $karigar_id = Ledgers::where('parent_type', $grnDetails->shift_to_karigar)->where('group_id', Config::get('constants.acounts_karigar_local'))->first('id');
        $laker_id_ledger = Ledgers::where('parent_type', $param['af_st_laker_karigar_id'])->first('id');
        //        dd($laker_id_ledger);
        //$EntData['grnID']=$request->main_grn_id;
        $EntData['ledger_id'] = $karigar_id->id;
        $EntData['entry_type_id'] = 7;
        $EntData['entry_id'] = $entID;
        $EntData['voucher_date'] = date('Y-m-d');
        $EntData['amount'] = ($grnDetails->payable_pure_weight) * ($curRate->rate);
        $EntData['rate'] = $curRate->rate;
        $EntData['currence_type'] = 1;
        $EntData['other_currency_type'] = $currID;
        $EntData['other_amount'] = $grnDetails->payable_pure_weight;
        $EntData['narration'] = 'Shift Goods Karigar to vendor';
        $EntData['dc'] = 'c';
        EntryItems::create($EntData);

        $EntData['other_currency_type'] = 1;
        $EntData['rate'] = 1;
        $EntData['dc'] = 'c';
        $EntData['amount'] = ($grnDetails->total_other_charges) + ($grnDetails->grand_total_price) + ($param['jewelry']['total_making'][$uID]);
        $EntData['other_amount'] = ($grnDetails->total_other_charges) + ($grnDetails->grand_total_price) + ($param['jewelry']['total_making'][$uID]);
        $EntData['other_currency_type'] = 1;
        EntryItems::create($EntData);


        //        $EntData['ledger_id'] = $karigar_id->id;
//        $EntData['amount'] = $param['jewelry']['total_making'][$uID];
//        $EntData['rate'] = 1;
//        $EntData['currence_type'] = 1;
//        $EntData['other_currency_type'] = 1;
//        $EntData['other_amount'] = $param['jewelry']['total_making'][$uID];
//        $EntData['dc'] = 'c';
//        EntryItems::create($EntData);
//        $EntData['ledger_id'] = $stock_ledger->id;
//        $EntData['dc'] = 'd';
//        EntryItems::create($EntData);


        //  acording To BA Ikram Total making add in other Charges so

        //stock entry
        $itemList = ItemsList::where('id', $grnDetails->item_id)->first(['id', 'item_category', 'item_subcategory', 'currency', 'uom']);
        $group = Groups::where('parent_id', 17)->where('parent_type', $itemList->item_category)->first('id');
        $stock_ledger = Ledgers::where(['parent_type' => $itemList->item_subcategory, 'group_id' => $group->id])->first('id');

        $EntData['ledger_id'] = $stock_ledger->id;
        $EntData['amount'] = ($grnDetails->payable_pure_weight) * ($curRate->rate);
        $EntData['rate'] = $curRate->rate;
        $EntData['other_amount'] = $grnDetails->payable_pure_weight;
        $EntData['dc'] = 'd';
        $EntData['other_currency_type'] = $currID;
        EntryItems::create($EntData);
        $EntData['amount'] = ($grnDetails->total_other_charges) + ($grnDetails->grand_total_price) + ($param['jewelry']['total_making'][$uID]);
        $EntData['other_amount'] = ($grnDetails->total_other_charges) + ($grnDetails->grand_total_price) + ($param['jewelry']['total_making'][$uID]);
        $EntData['rate'] = 1;
        $EntData['other_currency_type'] = 1;
        EntryItems::create($EntData);

        //difference entry
//        if (!empty($param['jewelry']['pure_wt_of_diff_balance'][$uID])) {
//            $EntData['ledger_id'] = $karigar_id->id;
//            $EntData['amount'] = abs(($param['jewelry']['pure_wt_of_diff_balance'][$uID]) * ($curRate->rate));
//            $EntData['rate'] = $curRate->rate;
//            $EntData['currence_type'] = 1;
//            $EntData['other_currency_type'] = $currID;
//            $EntData['other_amount'] = abs($param['jewelry']['pure_wt_of_diff_balance'][$uID]);
//            $EntData['dc'] = (($param['jewelry']['pure_wt_of_diff_balance'][$uID] < 0) ? 'd' : 'c');
//            EntryItems::create($EntData);
//            $EntData['ledger_id'] = $stock_ledger->id;
//            $EntData['dc'] = (($param['jewelry']['pure_wt_of_diff_balance'][$uID] < 0) ? 'c' : 'd');
//            EntryItems::create($EntData);
//        }

        //LAKER KARIGAR ENTERY

        $EntData['ledger_id'] = $laker_id_ledger->id;
        $EntData['amount'] = $param['af_st_laker'];
        $EntData['rate'] = 1;
        $EntData['currence_type'] = 1;
        $EntData['other_currency_type'] = 1;
        $EntData['other_amount'] = $param['af_st_laker'];
        $EntData['dc'] = 'c';
        EntryItems::create($EntData);
        $EntData['ledger_id'] = $stock_ledger->id;
        $EntData['dc'] = 'd';
        EntryItems::create($EntData);
        //

        $jewelry = $param['jewelry']['item_category'];
        foreach ($jewelry as $jew => $value) {
            $gems = $param['jewelry']['gems']['gems_type'];
            foreach ($gems as $gm => $value) {
                //                 dd($param['jewelry']['gems']['grn_currency'][$gm]);

                if (!isset($param['jewelry']['gems']['own_gems'][$gm])) {

                    $EntData['ledger_id'] = $karigar_id->id;
                    $EntData['amount'] = $param['jewelry']['gems']['pkr_rate'][$gm] ?: '';
                    $EntData['rate'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('rate');
                    $EntData['currence_type'] = 1;
                    $EntData['other_currency_type'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('id');
                    $EntData['other_amount'] = (($param['jewelry']['gems']['grn_currency'][$gm] == 'USD') ? $param['jewelry']['gems']['dollar_rate'][$gm] : $param['jewelry']['gems']['pkr_rate'][$gm]);
                    $EntData['dc'] = 'c';
                    EntryItems::create($EntData);
                    $EntData['ledger_id'] = $stock_ledger->id;
                    $EntData['dc'] = 'd';
                    EntryItems::create($EntData);
                } else {

                    $currency = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->first(['id', 'rate']);

                    if ($param['jewelry']['gems']['type'][$gm] == 0) {
                        $total_inventory = Total_Inventory::where('lds_packet_no', $param['jewelry']['gems']['gems_name'][$gm])->first();
                        $ldspacket = LdsPackets::where('id', $total_inventory->lds_packet_no)->first();
                        $Group = Groups::where(['parent_id' => 84])->where(['parent_type' => $ldspacket['packet_category']])->first();
                        $cr_ledger = Ledgers::where(['parent_type' => $ldspacket->id, 'group_id' => $Group->id])->first('id');
                        $cr_ledger = $cr_ledger->id;

                        $entData['amount'] = ($param['jewelry']['gems']['pkr_rate'][$gm]);
                        $entData['currence_type'] = 1;
                        $entData['other_currency_type'] = $currency['id'];
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('rate');
                        if ($currency['id'] == 1) {
                            $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm];
                        } else {
                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                        }
                        //                        $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                        $entData['ledger_id'] = $supID;
                        $entData['dc'] = 'c';
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('rate');
                        //                    dd($entData);
                        EntryItems::create($entData);
                        $entData['ledger_id'] = $cr_ledger;
                        $entData['dc'] = 'd';
                        EntryItems::create($entData);

                    } else if ($param['jewelry']['gems']['type'][$gm] == 1) {

                        $tag = Tagging_detail::with('itemShortName')->where('tag_number', $param['jewelry']['gems']['gems_name'][$gm])->get();

                        $inventory = InventoryStock::where('tag_no', $param['jewelry']['gems']['gems_name'][$gm])->first();

                        $entData['amount'] = ($param['jewelry']['gems']['pkr_rate'][$gm]);
                        $entData['currence_type'] = 1;
                        $entData['other_currency_type'] = $currency['id'];
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('rate');
                        if ($currency['id'] == 1) {
                            $entData['other_amount'] = $param['jewelry']['gems']['pkr_rate'][$gm];
                        } else {
                            $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];
                        }
                        //                        $entData['other_amount'] = $param['jewelry']['gems']['dollar_rate'][$gm];


                        $entData['ledger_id'] = $supID;
                        $entData['dc'] = 'c';
                        $entData['rate'] = Currencies::where('code', $param['jewelry']['gems']['grn_currency'][$gm])->value('rate');
                        EntryItems::create($entData);

                        $entData['ledger_id'] = $inventory->ledger_id;
                        $entData['dc'] = 'd';
                        EntryItems::create($entData);


                    }
                }


            }//in foreach
        }//end foreach

    }


}