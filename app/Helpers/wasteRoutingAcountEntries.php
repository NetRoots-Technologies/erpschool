<?php

namespace App\Helpers;

use App\Models\Admin\MixItem_list;
use App\Models\Admin\StockItemsModel;
use App\Models\Admin\Currencies;
use App\Models\Admin\Companies;
use App\Models\Admin\Entries;
use App\Models\Admin\TaxSetting;
use App\Models\Admin\Vendor\Vendor;
use App\Models\Routing_calculation_gem;
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

class wasteRoutingAcountEntries
{

    public $cr = 0, $dr = 0;


    static function tagging($request, $Entry, $routing_cal_id, $type = 11)
    {
        $Entry = $Entry->id;

        $narration = " impure shift to tagging";
        $dr_parent_id = 17;
        $catageri = 1;
        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);
        $dr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $drgroup_id->id);
        $dr_ledger = $dr_ledger->id;

        $cr_ledger = Config::get('constants.ledger_id.waste_gold');
        /// start ////
        try {
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
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            //            // other metal case
//            if ($request->sum_metal) {
//                $entData['ledger_id'] = $dr_ledger;
//                $entData['voucher_date'] = date('Y-m-d');
//                $entData['entry_type_id'] = $type;
//                $entData['entry_id'] = $Entry;
//                $entData['amount'] = $request->sum_metal;
//                $entData['currence_type'] = 1;
//                $entData['other_amount'] = $request->sum_metal;
//                $entData['other_currency_type'] = 1;
//                $entData['job_id'] = $routing_cal_id;
//                $entData['dc'] = 'd';
//                $entData['narration'] = $narration;
//                EntryItems::create($entData);
//                $entData['ledger_id'] = $cr_ledger;
//                $entData['dc'] = 'c';
//                EntryItems::create($entData);
//            }
//
            // other metals
            if ($request['total_metal']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request['total_metal'];
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['total_metal'];
                $entData['other_currency_type'] = 1;
                //                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }


            // doller
            $curRate = Currencies::where('id', 2)->first('rate');
            $ratedoler = $curRate->rate;
            if ($request->grand_total_dollar) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_dollar) * ($ratedoler);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_dollar;
                $entData['other_currency_type'] = 2;
                $entData['rate'] = $ratedoler;
                $entData['dc'] = 'd';
                $entData['job_id'] = $routing_cal_id;
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // pkr

            if ($request->grand_total_pkr) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['amount'] = $request->grand_total_pkr;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_pkr;
                //                $entData['other_amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
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


    static function addintagging_shift_to_process($request, $routing_cal_id, $Entry, $tag, $type = 11)
    {

        $Entry = $Entry->id;
        $narration = " impure shift to  add in tagging";
        $dr_parent_id = 17;
        $stck_parent_id = 18;
        $catageri = 1;
        $reposnse = "";
        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);

        $dr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $drgroup_id->id);
        $dr_ledger = $dr_ledger->id;
        $cr_ledger = Config::get('constants.ledger_id.waste_gold');

        $group_stockForSale = wasteRoutingAcountEntries::getgroup($stck_parent_id, $catageri);
        $cr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $group_stockForSale->id);
        $cr_ledger = $cr_ledger->id;
        $grn_detail = GrnDetail::find($tag->grn_id);
        if ($grn_detail->gold_type == 'swiss') {
            $gold_type = 5;
        } else {
            $gold_type = 4;
        }

        try {
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
                $entData['job_id'] = $routing_cal_id;
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

    static function addintagging($request, $Entry, $routing_cal_id, $type = 11)
    {
        $Entry = $Entry->id;
        $narration = " impure shift to tagging";
        $dr_parent_id = 17;
        $catageri = 1;
        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);
        $dr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $drgroup_id->id);
        $dr_ledger = $dr_ledger->id;
        $cr_ledger = Config::get('constants.ledger_id.waste_gold');
        /// start ////
        try {
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
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = 'tagging on impure gold';
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }

            // other metal case
            if ($request->sum_metal) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request->sum_metal;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->sum_metal;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }

            // doller
            $curRate = Currencies::where('id', 2)->first('rate');
            $ratedoler = $curRate->rate;
            if ($request->grand_total_dollar) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_dollar) * ($ratedoler);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_dollar;
                $entData['other_currency_type'] = 2;
                $entData['rate'] = $ratedoler;
                $entData['dc'] = 'd';
                $entData['job_id'] = $routing_cal_id;
                $entData['narration'] = 'tagging on impure dollar';
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }

            // pkr
            if ($request->grand_total_pkr) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['amount'] = $request->grand_total_pkr;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_pkr;
                //                $entData['other_amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
                $entData['rate'] = 1;
                $entData['dc'] = 'd';
                $entData['narration'] = 'tagging on impure pkr';
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

    static function mix($request, $Entry, $routing_cal_id, $type = 11)
    {

        $Entry = $Entry->id;
        $narration = " impure shift to mix";
        $dr_parent_id = 17;
        $catageri = 1;
        //        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);
//        $dr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $drgroup_id->id);
//        dd($request->sub_category_id);
        $dr_ledger = $request->ledger_id;
        $cr_ledger = Config::get('constants.ledger_id.waste_gold');


        /// start ////
        try {
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
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // other metals

            if ($request->sum_metal) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request->sum_metal;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->sum_metal;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }


            // doller
            $curRate = Currencies::where('id', 2)->first('rate');
            $ratedoler = $curRate->rate;

            if ($request->grand_total_dollar) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_dollar) * ($ratedoler);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_dollar;
                $entData['other_currency_type'] = 2;
                $entData['rate'] = $ratedoler;
                $entData['dc'] = 'd';
                $entData['job_id'] = $routing_cal_id;
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // pkr
            if ($request->grand_total_pkr) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['amount'] = $request->grand_total_pkr;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_pkr;
                //                $entData['other_amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
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

    static function impure($request, $Entry, $type = 11)
    {

        $Entry = $Entry->id;
        $narration = " shift to impure ";
        $dr_parent_id = 17;
        $catageri = 1;

        $cr_ledger = Config::get('constants.ledger_id.waste_gold');

        $dr_ledger = Config::get('constants.Impure_Purchase_Profit_loss');


        /// start ////
//        try {
        // pure wait
        $curRate = Currencies::where('id', 5)->first('rate');
        $rategold = $curRate->rate;
        if ($request['payable_pure_weight']) {
            $entData['ledger_id'] = $dr_ledger;
            $entData['voucher_date'] = date('Y-m-d');
            $entData['entry_type_id'] = $type;
            $entData['entry_id'] = $Entry;
            $entData['amount'] = ($request['payable_pure_weight']) * ($rategold);
            $entData['currence_type'] = 1;
            $entData['other_amount'] = $request['payable_pure_weight'];
            $entData['other_currency_type'] = 5;
            $entData['rate'] = $rategold;
            $entData['dc'] = 'd';
            $entData['narration'] = $narration;
            EntryItems::create($entData);
            $entData['ledger_id'] = $cr_ledger;
            $entData['dc'] = 'c';
            EntryItems::create($entData);
        }


        // other metals


        if ($request['total_metal']) {
            $entData['ledger_id'] = $dr_ledger;
            $entData['voucher_date'] = date('Y-m-d');
            $entData['entry_type_id'] = $type;
            $entData['entry_id'] = $Entry;
            $entData['amount'] = $request['total_metal'];
            $entData['currence_type'] = 1;
            $entData['other_amount'] = $request['total_metal'];
            $entData['other_currency_type'] = 1;
            //                $entData['job_id'] = $routing_cal_id;
            $entData['dc'] = 'd';
            $entData['narration'] = $narration;
            EntryItems::create($entData);
            $entData['ledger_id'] = $cr_ledger;
            $entData['dc'] = 'c';
            EntryItems::create($entData);
        }


        // doller
        $curRate = Currencies::where('id', 2)->first('rate');
        $ratedoler = $curRate->rate;

        if ($request['grand_total_dollar']) {
            $entData['ledger_id'] = $dr_ledger;
            $entData['voucher_date'] = date('Y-m-d');
            $entData['entry_type_id'] = $type;
            $entData['entry_id'] = $Entry;
            $entData['amount'] = ($request['grand_total_dollar']) * ($ratedoler);
            $entData['currence_type'] = 1;
            $entData['other_amount'] = $request['grand_total_dollar'];
            $entData['other_currency_type'] = 2;
            $entData['rate'] = $ratedoler;
            $entData['dc'] = 'd';
            $entData['narration'] = $narration;
            EntryItems::create($entData);
            // $entData['ledger_id']=132;
            $entData['ledger_id'] = $cr_ledger;
            $entData['dc'] = 'c';
            EntryItems::create($entData);
        }

        // pkr
        if ($request['totalPkr']) {
            $entData['ledger_id'] = $dr_ledger;
            $entData['voucher_date'] = date('Y-m-d');
            $entData['entry_type_id'] = $type;
            $entData['entry_id'] = $Entry;
            $entData['amount'] = $request['totalPkr'];
            //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
            $entData['currence_type'] = 1;
            $entData['other_amount'] = $request['totalPkr'];
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
        //        } catch (\Exception $e) {
//            $reposnse = "Error";
//        }
        return $reposnse;
    }

    static function vendor($request, $Entry, $routing_cal_id, $type = 11)
    {
        $reposnse = "";
        $requests = $request;
        $Entry = $Entry->id;
        foreach ($requests as $request) {
            $vendors_id = $request->kariger_shift_to;

            $vendorType = Vendor::where('id', $vendors_id)->value('vendor_type');
            if ($vendorType) {
                if ($vendorType == 1) {
                    $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_supplier_local'));
                } else {
                    $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_karigar_local'));
                }

                $vendor_ledger = $vendor->id;

                $narration = " impure shift to vendor";
                $dr_parent_id = 17;
                $catageri = 1;

                $dr_ledger = $vendor_ledger;
                $cr_ledger = Config::get('constants.ledger_id.waste_gold');
                /// start ////
                try {
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
                        $entData['job_id'] = $routing_cal_id;
                        $entData['dc'] = 'd';
                        $entData['narration'] = $narration;
                        EntryItems::create($entData);
                        $entData['ledger_id'] = $cr_ledger;
                        $entData['dc'] = 'c';
                        EntryItems::create($entData);
                    }
                    // other metals
                    if ($request->sum_metal) {
                        $entData['ledger_id'] = $dr_ledger;
                        $entData['voucher_date'] = date('Y-m-d');
                        $entData['entry_type_id'] = $type;
                        $entData['entry_id'] = $Entry;
                        $entData['amount'] = $request->sum_metal;
                        $entData['currence_type'] = 1;
                        $entData['other_amount'] = $request->sum_metal;
                        $entData['other_currency_type'] = 1;
                        $entData['job_id'] = $routing_cal_id;
                        $entData['dc'] = 'd';
                        $entData['narration'] = $narration;
                        EntryItems::create($entData);
                        $entData['ledger_id'] = $cr_ledger;
                        $entData['dc'] = 'c';
                        EntryItems::create($entData);
                    }
                    // doller
                    $curRate = Currencies::where('id', 2)->first('rate');
                    $ratedoler = $curRate->rate;
                    if ($request->grand_total_dollar) {
                        $entData['ledger_id'] = $dr_ledger;
                        $entData['voucher_date'] = date('Y-m-d');
                        $entData['entry_type_id'] = $type;
                        $entData['entry_id'] = $Entry;
                        $entData['amount'] = ($request->grand_total_dollar) * ($ratedoler);
                        $entData['currence_type'] = 1;
                        $entData['other_amount'] = $request->grand_total_dollar;
                        $entData['other_currency_type'] = 2;
                        $entData['rate'] = $ratedoler;
                        $entData['dc'] = 'd';
                        $entData['job_id'] = $routing_cal_id;
                        $entData['narration'] = $narration;
                        EntryItems::create($entData);
                        // $entData['ledger_id']=132;
                        $entData['ledger_id'] = $cr_ledger;
                        $entData['dc'] = 'c';
                        EntryItems::create($entData);
                    }
                    // pkr
                    if ($request->grand_total_pkr) {
                        $entData['ledger_id'] = $dr_ledger;
                        $entData['voucher_date'] = date('Y-m-d');
                        $entData['entry_type_id'] = $type;
                        $entData['entry_id'] = $Entry;
                        $entData['amount'] = $request->grand_total_pkr;
                        $entData['currence_type'] = 1;
                        $entData['other_amount'] = $request->grand_total_pkr;
                        $entData['other_currency_type'] = 1;
                        $entData['job_id'] = $routing_cal_id;
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
            }


        }
        return $reposnse;
    }

    static function laker($request, $Entry, $routing_cal_id, $type = 11)
    {

        $vendors_id = $request[0]->kariger_shift_to;

        $vendorType = Vendor::where('id', $vendors_id)->value('vendor_type');
        if ($vendorType == 1) {
            $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_supplier_local'));
        } else {
            $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_karigar_local'));
        }
        if ($vendor) {
            $vendor_ledger = $vendor->id;
        }

        $Entry = $Entry->id;
        $narration = "waste   shift to lds";
        $dr_parent_id = 17;
        $catageri = 1;
        $request = $request[0];
        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);



        $dr_ledger = wasteRoutingAcountEntries::getledger($request->item_category, $drgroup_id->id);

        if (!$dr_ledger) {
            $dr_ledger = wasteRoutingAcountEntries::getledger($request->sub_category_id, $drgroup_id->id);
        }

        $dr_ledger = $dr_ledger->id;
        $cr_ledger = Config::get('constants.ledger_id.waste_gold');
        /// start ////
        try {
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
                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // other metals
            if ($request['sum_metal']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request['sum_metal'];
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['sum_metal'];
                $entData['other_currency_type'] = 1;
                //                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }

            // doller
            $curRate = Currencies::where('id', 2)->first('rate');
            $ratedoler = $curRate->rate;
            if ($request->grand_total_dollar) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_dollar) * ($ratedoler);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_dollar;
                $entData['other_currency_type'] = 2;
                $entData['rate'] = $ratedoler;
                $entData['dc'] = 'd';
                $entData['job_id'] = $routing_cal_id;
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // pkr
            if ($request->grand_total_pkr) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                //                $entData['amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['amount'] = $request->grand_total_pkr;
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_pkr;
                //                $entData['other_amount'] = $request->grand_total_pkr + $request->total_making_charges;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
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

    static function lakerrecive($request, $impData, $Entry, $routing_cal_id, $type = 11)
    {
        $vendors_id = $request['vendor_id'];
        $vendorType = Vendor::where('id', $vendors_id)->value('vendor_type');
        if ($vendorType == 1) {
            $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_supplier_local'));
        } else {
            $vendor = wasteRoutingAcountEntries::getledger($vendors_id, Config::get('constants.acounts_karigar_local'));
        }
        $vendor_ledger = $vendor->id;
        $Entry = $Entry->id;
        $narration = " recive  to laker job";
        $dr_parent_id = 17;
        $catageri = 1;
        $drgroup_id = wasteRoutingAcountEntries::getgroup($dr_parent_id, $catageri);
        $cr_ledger = wasteRoutingAcountEntries::getledger($impData->sub_category_id, $drgroup_id->id);
        $cr_ledger = $cr_ledger->id;
        $dr_ledger = $vendor_ledger;
        /// start ////
        try {
            // payable
            $curRate = Currencies::where('id', 5)->first('rate');
            $rategold = $curRate->rate;
            if ($request['cal_Pure_Weight']) {
                $entData['ledger_id'] = $cr_ledger; // vender credit case of reciveable always reciable in laker
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request['cal_Pure_Weight']) * ($rategold);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['cal_Pure_Weight'];
                $entData['other_currency_type'] = 5;
                $entData['rate'] = $rategold;
                //                $entData['job_id'] = $routing_cal_id;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                $entData['ledger_id'] = $dr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            // laker charges
            if ($request['lakerCharges']) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = $request['lakerCharges'];
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request['lakerCharges'];
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
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
        return $reposnse;
    }

    static function lds($request, $Entry, $routing_cal_id, $type = 11)
    {
        $Entry = $Entry->id;
        $narration = " impure recive refine and shift to inventory pure gold";
        $catageri = 2;
        $cr_ledger = Config::get('constants.ledger_id.waste_gold');
        $branch_id = auth()->user()->branch_id;

        $Routing_calculation_gem = Routing_calculation_gem::find($request->item_id);
        $item_name = ItemsList::find($Routing_calculation_gem->item_id);

        $stock_itemsID = Groups::where(['parent_id' => Config::get('constants.acounts_sale_pack')])->where(['parent_type' => $item_name->item_subcategory])->value('id');

        $LdsPackets = LdsPackets::where('item_id', $item_name->id)->first();
        // $dr_ledger = Ledgers::where('parent_type', $LdsPackets->id)->where('group_id', $stock_itemsID)->value('id');

        if (!$LdsPackets) {
            $cr_ledger1 = 0;

            switch ($item_name->item_subcategory) {
                case 1:
                    $cr_ledger1 = 5; // Tagged Diamonds (Sale Stock)
                    break;
                case 2:
                    $cr_ledger1 = 7; //Tagged Stones (Sales Stock)
                    break;
                case 3:
                    $cr_ledger1 = 9; //Tagged Beads (Sales Stock)
                    break;
            }

            $dr_ledger = 0;

            switch ($cr_ledger1) {
                case 5:
                    $dr_ledger = 3;  //Tagged Diamonds (In Process)
                    break;
                case 7:
                    $dr_ledger = 6; //Tagged Stones (In Process)
                    break;
                case 9:
                    $dr_ledger = 8; // Tagged Beads (In Process)
                    break;
            }
        } else {
            $dr_ledger = Ledgers::where('parent_type', $LdsPackets->id)->where('group_id', $stock_itemsID)->value('id');

        }
        $curRate = Currencies::where('id', 2)->first('rate');
        $rate = $curRate->rate;
        try {

            if ($request->grand_total_pkr != 0) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_pkr);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_pkr;
                $entData['other_currency_type'] = 1;
                $entData['job_id'] = $routing_cal_id;
                $entData['rate'] = $rate;
                $entData['dc'] = 'd';
                $entData['narration'] = $narration;
                EntryItems::create($entData);
                // $entData['ledger_id']=132;
                $entData['ledger_id'] = $cr_ledger;
                $entData['dc'] = 'c';
                EntryItems::create($entData);
            }
            if ($request->grand_total_dollar != 0) {
                $entData['ledger_id'] = $dr_ledger;
                $entData['voucher_date'] = date('Y-m-d');
                $entData['entry_type_id'] = $type;
                $entData['entry_id'] = $Entry;
                $entData['amount'] = ($request->grand_total_dollar) * ($rate);
                $entData['currence_type'] = 1;
                $entData['other_amount'] = $request->grand_total_dollar;
                $entData['other_currency_type'] = 2;
                $entData['job_id'] = $routing_cal_id;
                $entData['rate'] = $rate;
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

    static function melting($request, $Entry, $routing_cal_id, $type = 11)
    {

    }

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