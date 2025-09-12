<?php

use Hashids\Hashids;
use App\Models\Admin\Metal;
use App\Models\Admin\Purity;
use App\Models\Admin\Weight;
use App\Models\Purchase\Grn;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Quality;
use App\Models\HRM\Employees;
use App\Models\ImpureHistory;
use App\Models\Admin\ItemsList;
use App\Models\Admin\Locations;
use App\Models\Admin\Currencies;
use App\Models\Purchase\GrnDetail;
use App\Models\Purchase\Tagg_Gems;
use App\Models\Admin\ItemsCategory;
use App\Models\Admin\Vendor\Vendor;
use App\Models\Purchase\Tag_number;
use App\Models\Purchase\Impure_gems;
use App\Models\Admin\ItemSubCategory;
use App\Models\Purchase\RoutingMeatl;
use Illuminate\Support\Facades\Config;
use App\Models\Purchase\Tagging_detail;
use App\Models\Routing_calculation_gem;
use App\Models\Routing_Calculation_metals;
use App\Models\Purchase\Impure_temp_tagging;
use App\Models\Purchase\Stock_invertory_history;

function hashId($id)
{
    return $id;
    // $hashids = new Hashids();
    // return $hashids->encode($id, 1, 9);
}

function amountFormat($amount, $decimalPoint = 3)
{
    if ($amount == round($amount)) {
        //no decimal, go ahead and truncate.
        return number_format($amount, 0);
    } else {
        return number_format($amount, $decimalPoint);
    }
}

function decimalPoint()
{
    return 4;
}

function hashIdDecode($id)
{
    return $id;

    $hashids = new Hashids();
    return $hashids->decode($id)[0];

}

function tag_ldsType_process($lds_type = 0)
{
    if ($lds_type == 1) {
        $res = $res = Config::get('constants.in_process_stock.LDS.tag_lds.diamond');
    } else if ($lds_type == 2) {
        $res = $res = Config::get('constants.in_process_stock.LDS.tag_lds.stone');
    } else if ($lds_type == 3) {
        $res = $res = Config::get('constants.in_process_stock.LDS.tag_lds.beads');
    } else {
        $res = 0;
    }
    return $res;
}

function tag_ldsType_stock($lds_type = 0)
{
    if ($lds_type == 1) {
        $res = $res = Config::get('constants.Stock_For_Sale.LDS.tag_lds.diamond.tag_diamond_Stock');
    } else if ($lds_type == 2) {
        $res = $res = Config::get('constants.Stock_For_Sale.LDS.tag_lds.stone.tag_stone_Stock');
    } else if ($lds_type == 3) {
        $res = Config::get('constants.Stock_For_Sale.LDS.tag_lds.beads.tag_beads_stokc');
    } else {
        $res = 0;
    }
    return $res;
}

function excel_header()
{
    $html = '<tr>
                    <td colspan="6" width="33.33%" style="text-align: left;"><h4 style="margin-bottom: 10px;margin-top: 5px;font-size: 20px;">HANIF JEWELLERY & WATCHES<span style="font-size: 12px;"> (HERITAGE PAR EXCELLENCE SINCE 1978)</span></h4>
                        <p style="margin-bottom: 2px;font-size: 12px;margin-top: 2px;">98 B2 M.M ALAM Road, Gulberg 3, Lahore, Pakistan</p>
                        <p style="margin-bottom: 2px;font-size: 12px;margin-top: 2px;">Corporate Office: +92-42-35757533 | 34 | 35</p>
                        <p style="margin-bottom: 2px;font-size: 12px;margin-top: 2px;">Email: info@hanifjewellers.com</p>
                    </td>
                    <td width="33.33%" style="text-align: right;">
                        <img src="' . url("public/uploads/h-hanif-80-2.png") . '">
                    </td>
                </tr>';
    return $html;
}

//expenses list
function tax_groups()
{
    $list = '';
    $array = Config::get('constants.tax_groups');
    foreach ($array as $key => $val) {
        $list .= '<option value="' . $key . '">' . $val . '</option>';
    }
    return $list;
}

function genrate_parcel_no($id)
{
    $data_return = "";
    $parcel_value = 001;
    if ($id == 1) {
        $prefix_parcel = "P-J-";
    } elseif ($id == 2) {
        $prefix_parcel = "P-G-";
    } elseif ($id == 3) {
        $prefix_parcel = "P-W-";
    } elseif ($id == 4) {
        $prefix_parcel = "P-A-";
    } elseif ($id == 5) {
        $prefix_parcel = "P-LD-";
    } elseif ($id == 6) {
        $prefix_parcel = "P-PK-";
    }
    $data = GrnDetail::where('item_category', $id)->where('parcel_number', '!=', null)->latest('id')->first();
    if ($data != null) {
        $parcel_number = $data->parcel_number;
        $arr1 = explode('-', trim($parcel_number));
        if (count($arr1) != 3) {
            $parcel_value = str_pad($parcel_value, 3, '0', STR_PAD_LEFT);
            $parcel_value = $prefix_parcel . $parcel_value;
            $data_return = $parcel_value;
        } else {
            $prefix_parcel = $arr1[0];
            $prefix_parcel = $prefix_parcel . '-' . $arr1[1] . '-';
            $parcel_value = (int) $arr1[2];
            $parcel_value = $parcel_value + 1;
            $parcel_value = str_pad($parcel_value, 3, '0', STR_PAD_LEFT);
            $parcel_value = $prefix_parcel . $parcel_value;
            $data_return = $parcel_value;
        }
    } else {
        $parcel_value = str_pad($parcel_value, 3, '0', STR_PAD_LEFT);
        $parcel_value = $prefix_parcel . $parcel_value;
        $data_return = $parcel_value;
    }
    return $data_return;
}

function genrate_tag($id, $grn_type)
{
    $prefix1 = "T";
    $value = 1;
    $item = ItemsList::where('id', $id)->first();
    $item_shoortcode = $item->short_code;
    $tagBook = Tag_number::where('item_category', $grn_type)
        ->latest('id')
        ->first();

    if ($tagBook) {
        $tagiing_id = $tagBook->tagging_id;
        $arr1 = explode('-', trim($item_shoortcode));
        if ($arr1) {
            $prefix = $arr1[0];
        }
        $tagid = $tagBook['tag_number'];
        if ($tagid) {
            $arr = explode('-', trim($tagid));
            $last_value = (int) $arr[2];
            $value = $last_value + 1;
        }
        $tag = $prefix1 . "-" . $prefix . "-" . $value;
        return $tag;
    } else {
        $arr1 = explode('-', trim($item_shoortcode));
        if ($arr1) {
            $prefix = $arr1[0];
        }
        $tag = $prefix1 . "-" . $prefix . "-" . $value;
        return $tag;
    }

    if ($tagBook) {
        $tagiing_id = $tagBook->tagging_id;
        $arr1 = explode('-', trim($item_shoortcode));
        if ($arr1) {
            $prefix = $arr1[0];
        }
        $tagid = $tagBook['tag_number'];
        if ($tagid) {
            $arr = explode('-', trim($tagid));
            $last_value = (int) $arr[2];
            $value = $last_value + 1;
        }
        $tag = $prefix1 . "-" . $prefix . "-" . $value;
        return $tag;
    } else {
        $arr1 = explode('-', trim($item_shoortcode));
        if ($arr1) {
            $prefix = $arr1[0];
        }
        $tag = $prefix1 . "-" . $prefix . "-" . $value;
        return $tag;
    }
}

function qualitesOfProduct($id = null)
{
    $list = '<option>Select Option</option>';
    $array = Quality::get(['id', 'name']);
    foreach ($array as $key => $val) {
        $selected = null;
        if ($id == $val->id)
            $selected = 'selected';
        $list .= '<option ' . $selected . ' value="' . $val->id . '">' . $val->name . '</option>';
    }
    return $list;
}

function fetchItemsBysubCategoryId($id, $selectedOption = null)
{
    $list = '<option>Select Option</option>';
    $result = ItemsList::where('item_subcategory', $id)->get(['id', 'short_code', 'item_type', 'rate_caret', 'currency']);
    foreach ($result as $item) {
        $selected = null;
        if ($selectedOption == $item->id)
            $selected = 'selected';
        $list .= '<option ' . $selected . '  data-ratePerCrt="' . $item->rate_caret . '" data-curncy="' . $item->currency . '" value="' . $item->id . '">' . $item->short_code . '</option>';
    }
    return $list;
}


function inventory_history($old, $new, $description = " new Entery")
{

    $Stock_invertory_history = new Stock_invertory_history();
    if ($old) {
        $Stock_invertory_history->item_id = $old->item_id;
        $Stock_invertory_history->short_code = $old->short_code;
        $Stock_invertory_history->sub_category_id = $old->sub_category_id;
        $Stock_invertory_history->grn_detail_id = $old->grn_detail_id;
        $Stock_invertory_history->old_lds_packet_no = $old->lds_packet_no;
        $Stock_invertory_history->old_tag_no = $old->tag_no;
        $Stock_invertory_history->old_branch_id = $old->branch_id;
        $Stock_invertory_history->old_tag_details_id = $old->tag_details_id;
        $Stock_invertory_history->old_location_id = $old->location_id;
        $Stock_invertory_history->old_net_total_wet = $old->net_total_wet;
        $Stock_invertory_history->old_net_available_wet = $old->net_available_wet;
        $Stock_invertory_history->old_g_total_weight = $old->g_total_weight;
        $Stock_invertory_history->old_g_available_weight = $old->g_available_weight;
        $Stock_invertory_history->old_g_issue_weight = $old->g_issue_weight;
        $Stock_invertory_history->old_g_sold_weight = $old->g_sold_weight;
        $Stock_invertory_history->old_total_dia_weight = $old->total_dia_weight;
        $Stock_invertory_history->old_available_dia_weight = $old->available_dia_weight;
        $Stock_invertory_history->old_issue_dia_weight = $old->issue_dia_weight;
        $Stock_invertory_history->old_sold_dia_weight = $old->sold_dia_weight;
        $Stock_invertory_history->old_total_bed_weight = $old->total_bed_weight;
        $Stock_invertory_history->old_available_bed_weight = $old->available_bed_weight;
        $Stock_invertory_history->old_issue_bed_weight = $old->issue_bed_weight;
        $Stock_invertory_history->old_sold_bed_weight = $old->sold_bed_weight;
        $Stock_invertory_history->old_total_st_weight = $old->total_st_weight;
        $Stock_invertory_history->old_available_st_weight = $old->available_st_weight;
        $Stock_invertory_history->old_issue_st_weight = $old->issue_st_weight;
        $Stock_invertory_history->old_sold_st_weight = $old->sold_st_weight;
        $Stock_invertory_history->old_total_quantity = $old->total_quantity;
        $Stock_invertory_history->old_available_quantity = $old->available_quantity;
        $Stock_invertory_history->old_issue_quantity = $old->issue_quantity;
        $Stock_invertory_history->old_sold_quantity = $old->sold_quantity;
        $Stock_invertory_history->old_total_pices = $old->total_pices;
        $Stock_invertory_history->old_available_pices = $old->available_pices;
        $Stock_invertory_history->old_ledger_id = $old->ledger_id;
        $Stock_invertory_history->old_issue_pices = $old->issue_pices;
        $Stock_invertory_history->old_sold_pices = $old->sold_pices;
        $Stock_invertory_history->old_total_pure_wet = $old->total_pure_wet;
        $Stock_invertory_history->old_available_pure_wet = $old->available_pure_wet;
        $Stock_invertory_history->old_issue_pure_wet = $old->issue_pure_wet;
        $Stock_invertory_history->old_sold_pure_wet = $old->sold_pure_wet;
        $Stock_invertory_history->old_total_pkr = $old->total_pkr;
        $Stock_invertory_history->old_available_total_pkr = $old->available_total_pkr;
        $Stock_invertory_history->old_total_dolar = $old->total_dolar;
        $Stock_invertory_history->old_available_dolar = $old->available_dolar;
        $Stock_invertory_history->old_total_other_curreency = $old->total_other_curreency;
        $Stock_invertory_history->old_available_other_curreency = $old->available_other_curreency;
        $Stock_invertory_history->old_expence = $old->expence;
        $Stock_invertory_history->old_unit_price = $old->unit_price;
        $Stock_invertory_history->old_inventory_status = $old->inventory_status ?? 0;
        $Stock_invertory_history->old_inventory_process_status = $old->inventory_process_status ?? 0;
        $Stock_invertory_history->old_type = $old->type ?? 0;
        $Stock_invertory_history->old_now_status = $old->now_status ?? 0;
        $Stock_invertory_history->old_status = $old->status;
        $Stock_invertory_history->process_vendor_id = $old->process_vendor_id;
        $Stock_invertory_history->old_description = $old->description;
        $Stock_invertory_history->new_description = $description;
    } elseif ($new) {
        $Stock_invertory_history->item_id = $new->item_id ?? NULL;
        $Stock_invertory_history->short_code = $new->short_code ?? NULL;
        $Stock_invertory_history->sub_category_id = $new->sub_category_id ?? NULL;
        $Stock_invertory_history->grn_detail_id = $new->grn_detail_id ?? NULL;


        $Stock_invertory_history->new_lds_packet_no = $new->lds_packet_no ?? NULL;
        $Stock_invertory_history->new_tag_no = $new->tag_no ?? NULL;
        $Stock_invertory_history->new_branch_id = $new->branch_id ?? NULL;
        $Stock_invertory_history->new_tag_details_id = $new->tag_details_id ?? NULL;
        $Stock_invertory_history->new_location_id = $new->location_id ?? NULL;
        $Stock_invertory_history->new_net_total_wet = $new->net_total_wet ?? NULL;
        $Stock_invertory_history->new_net_available_wet = $new->net_available_wet ?? NULL;
        $Stock_invertory_history->new_g_total_weight = $new->g_total_weight ?? NULL;
        $Stock_invertory_history->new_g_available_weight = $new->g_available_weight ?? NULL;
        $Stock_invertory_history->new_g_issue_weight = $new->g_issue_weight ?? NULL;
        $Stock_invertory_history->new_g_sold_weight = $new->g_sold_weight ?? NULL;
        $Stock_invertory_history->new_total_dia_weight = $new->total_dia_weight ?? NULL;
        $Stock_invertory_history->new_available_dia_weight = $new->available_dia_weight ?? NULL;
        $Stock_invertory_history->new_issue_dia_weight = $new->issue_dia_weight ?? NULL;
        $Stock_invertory_history->new_sold_dia_weight = $new->sold_dia_weight ?? NULL;
        $Stock_invertory_history->new_total_bed_weight = $new->total_bed_weight ?? NULL;
        $Stock_invertory_history->new_available_bed_weight = $new->available_bed_weight ?? NULL;
        $Stock_invertory_history->new_issue_bed_weight = $new->issue_bed_weight ?? NULL;
        $Stock_invertory_history->new_sold_bed_weight = $new->sold_bed_weight ?? NULL;
        $Stock_invertory_history->new_total_st_weight = $new->total_st_weight ?? NULL;
        $Stock_invertory_history->new_available_st_weight = $new->available_st_weight ?? NULL;
        $Stock_invertory_history->new_issue_st_weight = $new->issue_st_weight ?? NULL;
        $Stock_invertory_history->new_sold_st_weight = $new->sold_st_weight ?? NULL;
        $Stock_invertory_history->new_total_quantity = $new->total_quantity ?? NULL;
        $Stock_invertory_history->new_available_quantity = $new->available_quantity ?? NULL;
        $Stock_invertory_history->new_issue_quantity = $new->issue_quantity ?? NULL;
        $Stock_invertory_history->new_sold_quantity = $new->sold_quantity ?? NULL;
        $Stock_invertory_history->new_total_pices = $new->total_pices ?? NULL;
        $Stock_invertory_history->new_available_pices = $new->available_pices ?? NULL;
        $Stock_invertory_history->new_ledger_id = $new->ledger_id ?? NULL;
        $Stock_invertory_history->new_issue_pices = $new->issue_pices ?? NULL;
        $Stock_invertory_history->new_sold_pices = $new->sold_pices ?? NULL;
        $Stock_invertory_history->new_total_pure_wet = $new->total_pure_wet ?? NULL;
        $Stock_invertory_history->new_available_pure_wet = $new->available_pure_wet ?? NULL;
        $Stock_invertory_history->new_issue_pure_wet = $new->issue_pure_wet ?? NULL;
        $Stock_invertory_history->new_sold_pure_wet = $new->sold_pure_wet ?? NULL;
        $Stock_invertory_history->new_total_pkr = $new->total_pkr ?? NULL;
        $Stock_invertory_history->new_available_total_pkr = $new->available_total_pkr ?? NULL;
        $Stock_invertory_history->new_total_dolar = $new->total_dolar ?? NULL;
        $Stock_invertory_history->new_available_dolar = $new->available_dolar ?? NULL;
        $Stock_invertory_history->new_total_other_curreency = $new->total_other_curreency ?? NULL;
        $Stock_invertory_history->new_available_other_curreency = $new->available_other_curreency ?? NULL;
        $Stock_invertory_history->new_expence = $new->expence ?? NULL;
        $Stock_invertory_history->new_unit_price = $new->unit_price ?? NULL;
        $Stock_invertory_history->new_inventory_status = $new->inventory_status ?? 0 ?? NULL;
        $Stock_invertory_history->new_inventory_process_status = $new->inventory_process_status ?? 0 ?? NULL;
        $Stock_invertory_history->new_type = $new->type ?? NULL;
        $Stock_invertory_history->new_now_status = $new->now_status ?? 0 ?? NULL;
        $Stock_invertory_history->process_vendor_id = $new->process_vendor_id ?? 0 ?? NULL;
        $Stock_invertory_history->new_status = $new->status ?? NULL;
        $Stock_invertory_history->new_description = $description ?? NULL;
    }
    $Stock_invertory_history->save();
    //    dd($Stock_invertory_history);

}

function qualitesOfProductById($id)
{
    $list = '<option>Select Option</option>';
    $array = Quality::get(['id', 'name']);
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option data-val ="' . $val->id . '"  data-id ="' . $id . '" value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function uomHelper($code = null)
{
    $array = Weight::where('status', 1)->get(['id', 'name', 'code']);
    $list = '<option>Select Option</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->code == $code) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->code . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function genrateGrnNoForPureGoldOrTrackNo($grn_type)
{
    $track_value = 1;
    $value = 1;
    $check = Grn::where('grn_type', $grn_type)->latest()->first();
    if ($check) {

        $track_id = $check->tracking;
        $arr1 = explode('-', trim($track_id));
        $prefix_track = $arr1[0];
        $prefix_track = $prefix_track . '-' . $arr1[1] . '-';
        $track_value = (int) $arr1[2];
        $track_value = $track_value + 1;
        $track_value = str_pad($track_value, 4, '0', STR_PAD_LEFT);
        $track_value = $prefix_track . $track_value;
        $grn_no = $check->grn_no;
        $arr = explode('-', trim($grn_no));
        $prefix = $arr[0];
        $value = (int) $arr[1];
        $value = $value + 1;
        $value = str_pad($value, 3, '0', STR_PAD_LEFT);
        $grn_number = $prefix . '-' . $value;
    } else {
        $prefix_track = "PG-T-";
        $prefix1 = "Pure";


        $value = str_pad($value, 3, '0', STR_PAD_LEFT);
        $grn_number = $prefix1 . '-' . $value;

        $track_value = str_pad($track_value, 4, '0', STR_PAD_LEFT);
        $track_value = $prefix_track . $track_value;
    }
    $genrateNo = [
        'grn_number' => $grn_number,
        'track_value' => $track_value
    ];
    return $genrateNo;
}

function genrateGrnNoOrTrackNo($grn_type)
{
    $track_value = 1;
    $value = 1;
    $check = Grn::where('grn_type', $grn_type)->latest()->first();
    if ($check) {

        $track_id = $check->tracking;
        $arr1 = explode('-', trim($track_id));
        $prefix_track = $arr1[0];
        $prefix_track = $prefix_track . '-' . $arr1[1] . '-';
        $track_value = (int) $arr1[2];
        $track_value = $track_value + 1;
        $track_value = str_pad($track_value, 4, '0', STR_PAD_LEFT);
        $track_value = $prefix_track . $track_value;
        $grn_no = $check->grn_no;
        $arr = explode('-', trim($grn_no));
        $prefix = $arr[0];
        $value = (int) $arr[1];
        $value = $value + 1;
        $value = str_pad($value, 3, '0', STR_PAD_LEFT);
        $grn_number = $prefix . '-' . $value;
    } else {
        $request['grn_type'] = $grn_type;
        if ($request['grn_type'] == 1) {
            $prefix_track = "J-T-";
            $prefix1 = "J";
        } elseif ($request['grn_type'] == 2) {
            $prefix_track = "PG-T-";
            $prefix1 = "Pure";
        } elseif ($request['grn_type'] == 3) {
            $prefix_track = "W-T-";
            $prefix1 = "Watch";
        } elseif ($request['grn_type'] == 4) {
            $prefix_track = "A-T-";
            $prefix1 = "Acc";
        } elseif ($request['grn_type'] == 5) {
            $prefix_track = "LDS-T-";
            $prefix1 = "LDS";
        } elseif ($request['grn_type'] == 6) {
            $prefix_track = "P-T-";
            $prefix1 = "Pack";
        }

        $value = str_pad($value, 3, '0', STR_PAD_LEFT);
        $grn_number = $prefix1 . '-' . $value;

        $track_value = str_pad($track_value, 4, '0', STR_PAD_LEFT);
        $track_value = $prefix_track . $track_value;
    }
    $genrateNo = [
        'grn_number' => $grn_number,
        'track_value' => $track_value
    ];
    return $genrateNo;
}

function priceBygrossWet($totalGrossWet, $totalPrice, $netGrossWet)
{
    $perGramPrice = $totalPrice / $totalGrossWet;
    $netPrice = $perGramPrice * $netGrossWet;
    return $netPrice;
}

function karigarNameById($id)
{
    return Vendor::where('id', $id)->value('name');
}

function refineGoldItems($id = null)
{
    $array = ItemsList::where('item_subcategory', 10)->get(['id', 'item_type']);

    $list = '<option>Select Item</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->item_type . '</option>';
    }
    return $list;
}

function employesDropdown($id = null)
{
    $array = Employees::where('status', 1)->get(['id', 'name']);

    $list = '<option>Select Option</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function karigarDropdown($id = null)
{
    $array = Vendor::where('status', 1)->where('vendor_type', 2)->orderBy('id', 'DESC')->get(['id', 'name', 'vendor_type']);
    // 'vendor_type'=>2
    $list = '<option>Select Option</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function vendorDropdown($id = null)
{
    $array = Vendor::where('status', 1)->orderBy('id', 'DESC')->get(['id', 'name', 'vendor_type']);
    // 'vendor_type'=>2
    $list = '<option>Select Option</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        if ($val->vendor_type == 2) {
            $vendor = '(Kg)';
        } else {
            $vendor = '(Sup)';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . ' ' . $vendor . '</option>';
    }
    return $list;
}

function mixCateGoryDropDown($id = null)
{
    $mix_items = ItemsList::with('totalinventoryStock')->where('item_subcategory', 4)->get(['id', 'item_type']);
    $list = '<option>Select Option</option>';
    foreach ($mix_items as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->item_type . '</option>';
    }
    return $list;
}

function supplierDropDown($id = null)
{
    $array = Vendor::where(['status' => 1, 'vendor_type' => 1])->orderBy('name', 'DESC')->get(['id', 'name']);
    $list = '<option>Select Option</option>';
    foreach ($array as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function dolorToPkr($d)
{
    $d_rate = 0;
    if ($d) {
        // cache define in service provider
        $d_rate = Cache::get('curntDolarRate');
        $d_rate = $d * $d_rate;
    }
    return $d_rate;
}

function innerOuterDropDown($type = null)
{
    $inner = '';
    $outer = '';
    if ($type == 'inner') {
        $inner = 'selected';
    } elseif ($type == 'outer') {
        $outer = 'selected';
    }
    $list = '<option value="">Select option</option>';
    $list .= '<option value="inner" ' . $inner . '  > Inner male </option>';
    $list .= '<option value="outer" ' . $outer . ' > Outer male</option>';
    return $list;
}

function ledgerForMixRoutingAccount($id = null)
{
    $ledgers = Ledgers::where('group_id', 143)->get(['id', 'name']);
    $ledgerArray = array(
        Config::get('constants.ledger_id.waste_gold'),
        Config::get('constants.ledger_id.taar_cap'),
        Config::get('constants.ledger_id.safty_chains'),
        Config::get('constants.ledger_id.locks'),
    );
    $list = '<option>Select Option</option>';
    foreach ($ledgers as $key => $val) {
        $selected = null;
        if (in_array($val->id, $ledgerArray)) {
            if ($val->id == $id) {
                $selected = 'selected';
            }
            $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
        }
    }
    return $list;
}

function mixledgerDropDown($id = null)
{
    $ledgers = Ledgers::where('group_id', 143)->get(['id', 'name']);
    $list = '<option>Select Option</option>';
    foreach ($ledgers as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function locationDropdown($id = null)
{
    $locations = Locations::OrderBy('created_at', 'desc')->get();
    $list = '<option>Select Option</option>';
    foreach ($locations as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        $list .= '<option  value="' . $val->id . '" ' . $selected . '>' . $val->name . '</option>';
    }
    return $list;
}

function impureHistory($tabId, $shiftFrom, $status, $history_type)
{
    // LakerHistory::where('table_id',$tabId)->delete();
    $data = new ImpureHistory;
    // $data -> table_type = $tableType;
    $data->table_id = $tabId;
    $data->history_type = $history_type;
    $data->recive_from = $shiftFrom;
    $data->now_status = $status;
    $data->save();
}

function asignGrnIdImpTempTble($impTId, $grnId)
{
    Impure_temp_tagging::where('id', $impTId)->update(array('grn_id' => $grnId));
}

function getItemSubCate($subId = null)
{
    $item_category = ItemSubCategory::where('id', $subId)->first();
    return '<option  value="' . $item_category->id . '">' . $item_category->item_subcategory . '</option>';
    ;
}

function getItemCateGoryViaSubCat($subId = null)
{
    $item_category = ItemSubCategory::where('id', $subId)->first();
    return '<option  value="' . $item_category->itemCategoryName->id . '">' . $item_category->itemCategoryName->item_category . '</option>';
    ;
}

function getLdsItemsBySubCateGory($item_subcategory, $id = null)
{

    $data = ItemsList::where('item_subcategory', $item_subcategory)->get(['id', 'short_code', 'item_type', 'weight', 'uom', 'currency']);
    $list = '<option>Select Option</option>';
    foreach ($data as $key => $val) {
        $selected = null;
        if ($val->id == $id) {
            $selected = 'selected';
        }
        if ($val->LdsPacketsDate) {
            $list .= '<option data-inventory="inv" data-location="' . $val->LdsPacketsDate->LdsPacketsLocation->name . '" data-packet="' . $val->LdsPacketsDate->packet_no . '"  data-wetRange="' . $val->weight . '" data-uom="' . $val->uom . '"  value="' . $val->id . '" ' . $selected . '>' . $val->short_code . '</option>';
        } else {
            $list .= '<option   data-wetRange="' . $val->weight . '" data-uom="' . $val->uom . '"  value="' . $val->id . '" ' . $selected . '>' . $val->short_code . '</option>';
        }
    }
    return $list;
}

function AssignTagging_from_helper($id = 0)
{
    $list = '<option value="0">Select option</option>';
    $taggers = Employees::get(['id', 'name']);
    foreach ($taggers as $tagger) {
        $list .= '<option ' . (($tagger->id == $id) ? 'selected' : '') . ' value="' . $tagger->id . '">' . $tagger->name . '</option>';
    }
    return $list;
}

function lds_ledger_from_helper($group_id = 0, $id = null)
{
    $opt = '<option value="0">Select option</option>';

    $result = Ledgers::whereIn('group_id', $group_id)->where(['branch_id' => Auth::user()->branch_id])->get(['id', 'name']);
    foreach ($result as $row) {
        $opt .= '<option ' . (($row->id == $id) ? 'selected' : '') . ' value="' . $row->id . '">' . $row->name . '</option>';
    }
    return $opt;
}

function purity_dropdown($purity = null)
{
    $opt = '<option value="0">Select option</option>';

    $result = Purity::get(['id', 'purity']);
    foreach ($result as $row) {
        $opt .= '<option ' . (($row->purity == $purity) ? 'selected' : '') . ' value="' . $row->purity . '">' . $row->purity . '</option>';
    }
    return $opt;
}

function gramToCarats($gram)
{
    return $gram * 5;
}

function impurGemsCalculation($tableData, $gemType)
{
    $filteredDdata = $tableData->filter(function ($data, $key) use ($gemType) {
        return $data->gems_type == $gemType;
    });
    if ($filteredDdata)
        return $filteredDdata->sum('gems_weight_gram');
    return Null;
}

function routsGrnTypesDropDown($id = null)
{
    $opt = '<option value="0">Select option</option>';
    $result = Config::get("admin.routing_grn_type");
    foreach ($result as $key => $value) {
        $opt .= '<option ' . ($key == $id ? 'selected' : '') . ' value="' . $key . '">' . $value . '</option>';
    }
    return $opt;
}

function otherMetalDropdown($id = null)
{
    $opt = '<option value="0">Select option</option>';
    $result = Metal::get(['id', 'name']);
    foreach ($result as $row) {
        $opt .= '<option ' . (($row->id == $id) ? 'selected' : '') . ' value="' . $row->id . '">' . $row->name . '</option>';
    }
    return $opt;
}

function otherMetalwastegoldDropdown($id = null)
{
    $Routing_Calculation_metals = Routing_Calculation_metals::with('metalname')->where('routing_id', $id)->get();


    $opt = '<option value="0">Select option</option>';
    $result = Metal::get(['id', 'name']);
    foreach ($Routing_Calculation_metals as $row) {
        $opt .= '<option data-metal_id="' . $row->metal_name . '"     value="' . $row->id . '">' . $row->metalname->name . '</option>';
    }
    return $opt;
}

function routingSelectedVendor($id = null)
{

    $opt = "";
    $vendorCount = count($id);
    $result = Vendor::whereIn('id', $id)->get(['id', 'name']);
    if ($vendorCount == 1) {
        foreach ($result as $row) {
            $opt = '<option selected  value="' . $row->id . '">' . $row->name . '</option>';
        }
    } else {
        $opt = '<option>Select option</option>';
        foreach ($result as $row) {
            $opt .= '<option  value="' . $row->id . '">' . $row->name . '</option>';
        }
    }
    //    dd($opt);
    return $opt;
}

function currencyDropdown($code = null)
{
    $opt = '<option value="0">Select option</option>';
    $result = Currencies::get();
    foreach ($result as $row) {
        $opt .= '<option ' . (($row->code == $code) ? 'selected' : '') . ' value="' . $row->code . '" data-rate="' . $row->rate . '" data-decimal="' . $row->decimal_fixed_point . '">' . $row->code . '</option>';
    }
    return $opt;
}

function getMixgems($idArray, $gemsFrom)
{
    Mix::whereIn('id', $id)->where('mix_type', $gemsFrom)->get('grn_id');
}

function getGemsTotalPriceCurncyWise($tableId, $curncyName, $columnName)
{

    return Impure_gems::where('impure_temp_tagging_id', $tableId)->where('gems_currency', $curncyName)->whereNull('own_gems')->sum($columnName);
}

function getGemsTotalPriceFromTagGemsCurncyWise($tableId, $curncyName, $columnName)
{
    return Tagg_Gems::where('tag_detail_id', $tableId)->where('gems_currency', $curncyName)->sum($columnName);
}

function getMetalTotalPrice($tableId, $columnName)
{
    return RoutingMeatl::where('impure_temp_tagging_id', $tableId)->where('metal_id', '!=', 3)->sum($columnName);
}

function getMetalTotalPriceWastGold($tableId, $columnName)
{
    return RoutingMeatl::where('waste_gold_temp_tagging_id', $tableId)->where('metal_id', '!=', 3)->sum($columnName);
}

function getOwnGemsCalculation($tableId, $curncyName, $whichGem, $columnName)
{
    if ($curncyName)
        return Impure_gems::where('impure_temp_tagging_id', $tableId)->where('own_gems', $whichGem)->where('gems_currency', $curncyName)->sum($columnName);
    else
        return Impure_gems::where('impure_temp_tagging_id', $tableId)->where('own_gems', $whichGem)->sum($columnName);
}

function sumMetalPkrFormData($requestData)
{
    $line_items_array = $requestData['metal']['metal_name'];
    $metaLToal = 0;
    foreach ($line_items_array as $key => $val) {
        if ($requestData['metal']['metal_name'][$key] != 3) {
            $metaLToal += $requestData['metal']['metal_amt'][$key];
        }
    }
    return $metaLToal;
}

function claculateWaistGems($id, $Columname)
{
    return Routing_calculation_gem::where('routing_cal_id', $id)->sum($Columname);
}

function claculateWaistMetal($id)
{
    return Routing_Calculation_metals::where('routing_id', $id)->where('metal_name', '!=', 3)->sum('metal_amt');
}
function apiResponce($status = 1, $method = '', $message = '', $data = [])
{
    $response = [
        'status' => $status,
        'method' => $method,
        'message' => $message,
        'data' => $data
    ];
    return $response;
}
