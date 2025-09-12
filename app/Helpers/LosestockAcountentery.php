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

class LosestockAcountentery
{

    public $cr = 0, $dr = 0, $narration = " Losestock  shift to tagging";

    // shift to tagging
    static function tagging($request, $Entry, $type = 10)
    {


        /// start ////
        try {
            $Entry = $Entry->id;
            $item = ItemsList::where('short_code', $request->item_short_code)->first();
            $cr_ledger = Ledgers::where('group_id', 143)->where('parent_type', $item->id)->first();
            $narration = " Losestock  shift to tagging";
            $cr_ledger = $cr_ledger->id;
            $dr_parent_id = 17;
            $catageri = 1;
            $drgroup_id = LosestockAcountentery::getgroup($dr_parent_id, $catageri);
            $dr_ledger = LosestockAcountentery::getledger(5, $drgroup_id->id);

            $dr_ledger = $dr_ledger->id;
            // pure wait
            $curRate = Currencies::where('id', 5)->first('rate');
            $rategold = $curRate->rate;
            if ($request->pure_weight) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->pure_weight) * ($rategold);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->pure_weight;
                $entData['other_currency_type'] = 5;
                $entData['rate'] = $rategold;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }

            if ($request->total_making_charges) {
                $EntItem['entry_id'] = $Entry;
                $EntItem['voucher_date'] = date('Y-m-d');
                $EntItem['amount'] = $request->price;
                $EntItem['other_amount'] = $request->price;
                $EntItem['rate'] = 1;
                $EntItem['entry_type_id'] = $type;
                $EntItem['currence_type'] = 1;
                $EntItem['other_currency_type'] = 1;
                $EntItem['narration'] = $narration;
                $EntItem['ledger_id'] = $dr_ledger;
                $EntItem['dc'] = 'd';
                EntryItems::insert($EntItem);
                $EntItem['dc'] = 'c';
                $EntItem['ledger_id'] = $cr_ledger;
                EntryItems::insert($EntItem);
            }


            $reposnse = "done";
        } catch (\Exception $e) {
            $reposnse = "Error";
        }
        return $reposnse;
    }


    static function addintagging_shift_to_process($request, $Entry, $tag, $type = 10)
    {



        try {
            $Entry = $Entry->id;
            $narration = " impure shift to  add in tagging";
            $dr_parent_id = 17;
            $stck_parent_id = 18;
            $catageri = 1;
            $reposnse = "";
            $catageri = 1;
            $drgroup_id = LosestockAcountentery::getgroup($dr_parent_id, $catageri);
            $dr_ledger = LosestockAcountentery::getledger(5, $drgroup_id->id);
            $dr_ledger = $dr_ledger->id;
            $item = ItemsList::where('short_code', $tag->item_short_code)->first();
            $group_stockForSale = LosestockAcountentery::getgroup($stck_parent_id, $catageri);
            $cr_ledger = LosestockAcountentery::getledger($item->item_subcategory, $group_stockForSale->id);
            $cr_ledger = $cr_ledger->id;


            $grn_detail = GrnDetail::find($tag->grn_id);
            if ($grn_detail->gold_type == 'swiss') {
                $gold_type = 5;
            } else {
                $gold_type = 4;
            }
            if ($tag->pure_weight > 0) {
                $rate = Currencies::where('id', $gold_type)->value('rate');
                $EntItem['ledger_id'] = $dr_ledger;
                $EntItem['dc'] = 'd';
                $EntItem['other_currency_type'] = $gold_type;
                $EntItem['amount'] = (abs($tag->pure_weight)) * ($rate);
                $EntItem['other_amount'] = abs($tag->pure_weight);
                $EntItem['rate'] = $rate;
                $EntItem['entry_type_id'] = $type;
                $EntItem['entry_id'] = $Entry;

                EntryItems::insert($EntItem);
                $EntItem['dc'] = 'c';
                $EntItem['ledger_id'] = $cr_ledger;
                EntryItems::insert($EntItem);
            }

            if ($tag->grand_total_pkr) {
                $EntItem['entry_id'] = $Entry;
                $EntItem['voucher_date'] = date('Y-m-d');
                //                $EntItem['amount'] = ($tag->grand_total_pkr) + ($tag->total_making_charges);
                $EntItem['amount'] = $tag->grand_total_pkr;
                //                $EntItem['other_amount'] = ($tag->grand_total_pkr) + ($tag->total_making_charges);
                $EntItem['other_amount'] = $tag->grand_total_pkr;
                $EntItem['rate'] = 1;
                $EntItem['entry_type_id'] = $type;
                $EntItem['currence_type'] = 1;
                $EntItem['other_currency_type'] = 1;
                $EntItem['narration'] = 'Impure purchase add in tag';
                $EntItem['ledger_id'] = $dr_ledger;
                $EntItem['dc'] = 'd';
                EntryItems::insert($EntItem);
                $EntItem['dc'] = 'c';
                $EntItem['ledger_id'] = $cr_ledger;
                EntryItems::insert($EntItem);
            }


            if (isset($tag->grand_total_dollar) && $tag->grand_total_dollar > 0) {
                $rate = Currencies::where('id', 2)->value('rate');
                $EntItem['ledger_id'] = $cr_ledger;
                $EntItem['amount'] = ($tag->grand_total_dollar) * ($rate);
                $EntItem['other_amount'] = $tag->grand_total_dollar;
                $EntItem['rate'] = $rate;
                $EntItem['dc'] = 'c';
                $EntItem['currence_type'] = 1;
                $EntItem['other_currency_type'] = 2;
                EntryItems::insert($EntItem);
                $EntItem['dc'] = 'd';
                $EntItem['ledger_id'] = $dr_ledger;
                EntryItems::insert($EntItem);

            }

            $reposnse = "done";
        } catch (\Exception $e) {
            $reposnse = "Error";
        }

        return $reposnse;

    }

    static function addintagging($request, $Entry, $tag, $type = 12)
    {



        /// start ////
        try {
            $item_id = Total_Inventory::find($request['item_name'])->item_id;
            $item = ItemsList::find($item_id);

            $Entry = $Entry->id;
            $cr_ledger = Ledgers::where('group_id', 143)->where('parent_type', $item->id)->first();

            $narration = " Losestock  shift to add in tagging";
            $cr_ledger = $cr_ledger->id;
            $dr_parent_id = 17;
            $catageri = 1;

            $tagitem = ItemsList::where('short_code', $tag->item_short_code)->first();
            $drgroup_id = LosestockAcountentery::getgroup($dr_parent_id, $catageri);
            $dr_ledger = LosestockAcountentery::getledger($tagitem->item_subcategory, $drgroup_id->id);

            $dr_ledger = $dr_ledger->id;
            // pure wait
            $curRate = Currencies::where('id', 5)->first('rate');
            $rategold = $curRate->rate;

            if ($request['pure_wt']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request['pure_wt']) * ($rategold);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['pure_wt'];
                $entData['other_currency_type'] = 5;
                $entData['rate'] = $rategold;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            if ($request['making_charges']) {
                $EntItem['entry_id'] = $Entry;
                $EntItem['voucher_date'] = date('Y-m-d');
                $EntItem['amount'] = $request['price'];
                $EntItem['other_amount'] = $request['price'];
                $EntItem['rate'] = 1;
                $EntItem['entry_type_id'] = $type;
                $EntItem['currence_type'] = 1;
                $EntItem['other_currency_type'] = 1;
                $EntItem['narration'] = $narration;
                $EntItem['ledger_id'] = $dr_ledger;
                $EntItem['dc'] = 'd';
                EntryItems::insert($EntItem);
                $EntItem['dc'] = 'c';
                $EntItem['ledger_id'] = $cr_ledger;
                EntryItems::insert($EntItem);
            }
            $reposnse = "done";
        } catch (\Exception $e) {
            $reposnse = "Error";
        }
        return $reposnse;
    }


    static function impure($request, $Entry, $type = 12)
    {


        /// start ////
        try {
            $Entry = $Entry->id;
            $narration = " shift to impure ";
            $dr_parent_id = 17;
            $catageri = 1;
            $total_inventory = Total_Inventory::find($request['item_name']);
            $item_id = $total_inventory->item_id;
            $item = ItemsList::find($item_id);
            $cr_ledger = Ledgers::where('group_id', 143)->where('parent_type', $item->id)->first();


            $cr_ledger = $cr_ledger->id;

            $dr_ledger = Config::get('constants.Impure_Purchase_Profit_loss');
            // pure wait
            $curRate = Currencies::where('id', 5)->first('rate');
            $rategold = $curRate->rate;
            if ($request['pure_wt']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request['pure_wt']) * ($rategold);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['pure_wt'];
                $entData['other_currency_type'] = 5;
                $entData['rate'] = $rategold;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // pkr
            if ($request['making_charges']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request['price'];
                //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['price'];
                //                $entData['other_amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['other_currency_type'] = 1;
                $entData['rate'] = 1;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            $reposnse = "done";
        } catch (\Exception $e) {
            $reposnse = "Error";
        }
        return $reposnse;
    }

    static function vendor($request, $Entry, $type = 12)
    {

        $reposnse = "";
        $Entry = $Entry->id;
        $vendors_id = $request['vendor_id'];
        $vendorType = Vendor::where('id', $vendors_id)->value('vendor_type');
        if ($vendorType) {

            /// start ////
            try {
                if ($vendorType == 1) {
                    $vendor = LosestockAcountentery::getledger($vendors_id, Config::get('constants.acounts_supplier_local'));
                } else {
                    $vendor = LosestockAcountentery::getledger($vendors_id, Config::get('constants.acounts_karigar_local'));
                }

                $vendor_ledger = $vendor->id;
                $narration = " lose  shift to vendor";
                $dr_parent_id = 17;
                $catageri = 1;
                $dr_ledger = $vendor_ledger;
                $item_id = Total_Inventory::find($request['item_name'])->item_id;
                $item = ItemsList::where('id', $item_id)->first();
                $cr_ledger = Ledgers::where('group_id', 143)->where('parent_type', $item->id)->first();
                $narration = " Losestock  shift to tagging";
                $cr_ledger = $cr_ledger->id;
                // pure wait
                $curRate = Currencies::where('id', 5)->first('rate');
                $rategold = $curRate->rate;
                if ($request['pure_wt']) {
                    $entData['ledger_id'] = $dr_ledger;
                    $entData['voucher_date'] = date('Y-m-d');
                    $entData['entry_type_id'] = $type;
                    $entData['entry_id'] = $Entry;
                    $entData['amount'] = ($request['pure_wt']) * ($rategold);
                    $entData['currence_type'] = 1;
                    $entData['other_amount'] = $request['pure_wt'];
                    $entData['other_currency_type'] = 5;
                    $entData['rate'] = $rategold;
                    $entData['dc'] = 'd';
                    $entData['narration'] = $narration;
                    EntryItems::create($entData);
                    $entData['ledger_id'] = $cr_ledger;
                    $entData['dc'] = 'c';
                    EntryItems::create($entData);
                }
                // pkr
                if ($request['making_charges']) {
                    $entData['ledger_id'] = $dr_ledger;
                    $entData['voucher_date'] = date('Y-m-d');
                    $entData['entry_type_id'] = $type;
                    $entData['entry_id'] = $Entry;
                    $entData['amount'] = $request['price'];
                    $entData['currence_type'] = 1;
                    $entData['other_amount'] = $request['price'];
                    $entData['other_currency_type'] = 1;
                    $entData['rate'] = 1;
                    $entData['dc'] = 'd';
                    $entData['narration'] = $narration;
                    EntryItems::create($entData);
                    $entData['ledger_id'] = $cr_ledger;
                    $entData['dc'] = 'c';
                    EntryItems::create($entData);
                }
                $reposnse = "done";
            } catch (\Exception $e) {
                $reposnse = "Error";
            }
        }
        return $reposnse;
    }

    // global funcatiosn of loss stock helper


    static function getledger($parent_type = 0, $group_id = 0)
    {
        return Ledgers::where(['parent_type' => $parent_type, 'group_id' => $group_id])->first('id');
    }

    static function getgroup($parent_id = 0, $parent_type = 0)
    {
        return Groups::where('parent_id', $parent_id)->where('parent_type', $parent_type)->first('id');
    }

    static function currnecy_value($id = 0, $code = 0)
    {
        if ($id != 0) {
            return Currencies::where('id', $id)->first();

        } else if ($code != 0) {
            return Currencies::where('code', $code)->first();

        }
    }


    static function enterytype($type = 1, $currence_type = 1, $other_currency_type = 1)
    {
        $Ddata['entry_type_id'] = $type;
        $Ddata['voucher_date'] = date('Y-m-d');
        $Ddata['created_by'] = Auth::user()->id;
        $Ddata['updated_by'] = Auth::user()->id;
        $Ddata['employee_id'] = Auth::user()->id;
        $Ddata['branch_id'] = Auth::user()->branch_id;
        $Ddata['status'] = 0;
        $Ddata['currence_type'] = $currence_type;
        $Ddata['other_currency_type'] = $other_currency_type;
        $Entry = Entries::create($Ddata);
        $Entry->update(array(
            'number' => CoreAccounts::generateNumber($Entry->id),
        ));
        return $Entry;
    }
}