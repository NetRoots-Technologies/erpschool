<?php

namespace App\Helpers;

use App\Models\Admin\Groups;
use App\Models\Admin\ItemsCategory;
use App\Models\Admin\ItemsList;
use App\Models\Admin\ItemSubCategory;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Purity;
use App\Models\Admin\Vendor\Vendor;
use App\Models\HRM\CardIssuance;
use App\Models\HRM\JobApplication;
use App\Models\HRM\Employees;
use App\Models\HRM\Holidays;
use App\Models\Purchase\Grn;
use App\Models\Purchase\Tagg_Gems;
use App\Models\Purchase\Total_Inventory;
use App\Models\Student\AcademicSession;
use App\TagLifeCycle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use Config;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Float_;

/**
 * Class to store the entire group tree
 */
class UserHelper
{
    /**
     * Initializer
     */
    private $_user;


    function UserHelper(User $user)
    {
        $this->_user = $user;
    }

    public static function getUserData()
    {
        return Auth::user();
    }

    public static function getPermationAutharity($permation)
    {
        if (Gate::allows($permation)) {
            return true;
        }
        return false;
    }

    public static function getUserRole()
    {
        return Auth::user()->roles->first()->name;
    }

    public static function getUserId()
    {
        return Auth::user()->id;
    }

    public static function getUserName()
    {
        return Auth::user()->Employees->first_name . ' ' . Auth::user()->Employees->last_name;
    }

    public static function getUserEmail()
    {
        return Auth::user()->Employees->official_email;
    }

    public static function getUserJobTitle()
    {
        if (Auth::user()->Employees->jobtitle) {
            return Auth::user()->Employees->jobtitle->name;
        } else {
            return 'N/A';
        }
    }

    public static function getUserDepartment()
    {
        if (isset(Auth::user()->Employees->department->name)) {
            return Auth::user()->Employees->department->name;
        }
    }

    //    public static function getAllChildren(){
    //        if(self::getUserRole()=='administrator'){
    //            dd('adadad');
    //        }
    //    }

    public static function trainingEditPermission()
    {
        return Gate::check('training_and_production_edit') ? '1' : '0';
    }

    public static function getAllParent()
    {
        return Auth::user()->Employees->first_name . '' . Auth::user()->Employees->last_name;
    }

    public static function isAdmin()
    {
        if (self::getUserRole() == 'administrator') {
            return true;
        } else {
            return false;
        }
    }

    public static function isCeo()
    {
        // if(self::getUserRole()=='CEO'){
        //     return true;
        // }else{
        //     return false;
        // }
        if (Auth::user()->can('leave_requests_status_change')) {
            return true;
        } else {
            return false;
        }
    }

    public static function isHod()
    {
        // if(self::getUserRole()=='HOD'){
        //     return true;
        // }else{
        //     return false;
        // }
        if (Auth::user()->can('leave_requests_status_change')) {
            return true;
        } else {
            return false;
        }
    }

    public static function isLm()
    {
        // if(self::getUserRole()=='Line Manager'){
        //     return true;
        // }else{
        //     return false;
        // }
        if (Auth::user()->can('leave_requests_status_change')) {
            return true;
        } else {
            return false;
        }
    }

    public static function isAlm()
    {
        if (self::getUserRole() == 'Assistant Line Manager') {
            return true;
        } else {
            return false;
        }
    }

    public static function isHr()
    {

        // if(self::getUserRole()=='Human Resource'){
        //     return true;
        // }else{
        //     return false;
        // }
        if (Auth::user()->can('leave_requests_status_change')) {
            return true;
        } else {
            return false;
        }
    }

    public static function isAuthorize()
    {
        return (self::isAdmin() || self::isHr() || self::isCeo());
    }

    public static function isHrById($id)
    {
        return self::getRoleByUserId($id) == 'Human Resource';
    }

    public static function isHodyId($id)
    {
        if (self::getRoleByUserId($id) == 'HOD') {
            return true;
        }
    }

    public static function isCeoyId($id)
    {
        if (self::getRoleByUserId($id) == 'CEO') {
            return true;
        }
    }

    public static function getRoleByUserId($id)
    {
        return User::find($id)->roles->first()->name;
    }

    public static function checkLMById($id)
    {
        return Employees::where('user_id', $id)->pluck('report_to')->first() == self::getUserId();
    }

    public static function getFirstParent($id)
    {
        return Employees::where('user_id', $id)->pluck('report_to')->first();
    }

    public static function isAccountsDept()
    {
        return Employees::getEmployeeByUserId(self::getUserId())->department->id == '3' ? true : false;
    }

    public static function isHrDept()
    {
        return Employees::getEmployeeByUserId(self::getUserId())->department->id == '4' ? true : false;
    }

    public static function roundAmount($amount, $round = 3)
    {
        return round($amount, $round);
    }

    public static function cartToGram($ct)
    {
        return self::roundAmount($ct / 5, 3);
    }

    public static function dateFromate($date)
    {
        $date = date_create($date);
        return date_format($date, "Y/m/d");
    }

    public static function clearanceCheck()
    {

        if ((Auth::user()->Employees->department->name == "Accounts, Finance & Taxation") || (Auth::user()->Employees->department->name == "Life, Training & Certification") || (Auth::user()->Employees->department->name == "Administration") || (Auth::user()->Employees->department->name == "Information Technology") || (Auth::user()->Employees->department->name == "Scheme Department")) {
            return true;
        } else {
            return false;
        }
    }

    public static function getHodById($id)
    {
        $report_to = UserHelper::getFirstParent($id);
        $sup_role = self::getRoleByUserId($report_to);
        if ($sup_role == 'Line Manager') {
            // get hod of line manager
            $report_to = UserHelper::getFirstParent($report_to);
            return $report_to;
        } elseif ($sup_role == 'HOD') {
            return $report_to;
        } else {
            return false;
        }
    }

    public static function getAllParentsIds($user_id)
    {
        $res = array();
        $report_to = $user_id;
        do {

            $report_to = self::getFirstParent($report_to);
            if ($report_to) {
                array_push($res, $report_to);
            }
        } while (isset($report_to));

        return $res;
    }

    public static function getAllHolidays($year)
    {

        $holidays = Holidays::whereYear('holiday_date', $year)->get();

        $holiday_dates = array();

        foreach ($holidays as $key => $val) {

            if ($val['holiday_date'] != $val['holiday_date_to']) {
                $totla_dates = self::daysBetweenDates($val['holiday_date'], $val['holiday_date_to']);
                foreach ($totla_dates as $val) {
                    array_push($holiday_dates, $val);
                }
            } else {
                array_push($holiday_dates, $val['holiday_date']);
            }
        }

        $holiday_dates = array_unique($holiday_dates);

        return $holiday_dates;
    }

    public static function daysBetweenDates($from_date, $to_date)
    {
        $all_dates = array();
        $date = $from_date;

        while ($date != $to_date) {
            array_push($all_dates, $date);
            $date = new \DateTime($date);

            $date->modify('+1 day');
            $date = $date->format('Y-m-d');
        }
        array_push($all_dates, $to_date);
        return $all_dates;
    }

    public function get_name($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->first();

        return $data;
    }

    public static function get_name_st($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->first();

        return $data;
    }


    public function get_row_with_sum($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->where('tagging_id', $id)
            ->select([
                'id',
                DB::raw('sum(qty) as qty,sum(total_pieces) as total_pieces,
        sum(total_gross_wt) as total_gross_wt')
            ])->groupBy('tagging_id')->get();
        return $data;
    }


    public function get_name_dcs_order($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public function get_name_supplier($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->where('supplier_type', 2)->first();

        return $data;
    }

    public function get_multipal_rows($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->get();

        return $data;
    }

    public function get_multipal_rows_from_inventory($table_name, $colum_name, $id)
    {
        $data = DB::table($table_name)->where($colum_name, $id)->get();
        return $data;
    }

    public function formulaLdsPacketsOthercharges($totalGrnAmount, $totalExpence, $itemListPrice)
    {
        $GrnAmntLessExp = $totalGrnAmount - $totalExpence;
        if ($GrnAmntLessExp && $totalExpence) {
            $totalAvg = $totalExpence / $GrnAmntLessExp;
            $totalothercharges = $totalAvg * $itemListPrice;
        } else
            $totalothercharges = null;
        return $totalothercharges;
    }

    public function purity()
    {
        return Purity::pluck('purity', 'purity');
    }

    public function purities()
    {
        return Purity::pluck("purity", "id")->toArray();
    }

    public function vendorDropdown($id = null)
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


    public function get_roundof_currency($Currencycode, $ammount)
    { //value type is Puregold, Pkr, Doller

        if ($Currencycode == "USD") {
            if (is_float($ammount)) {

                $string = sprintf($ammount);
                $count = 0;
                while (true) {
                    if ($string[$count] == ".") {
                        $count += 3;
                        $substring = substr($string, 0, $count);
                        $convertedfloat = floatval($substring);
                        $value = number_format($convertedfloat, 2);
                        break;
                    }
                    $count += 1;
                }
            } else {
                return $ammount;
            }
        } else if ($Currencycode == "G") {
            if (is_float($ammount)) {
                $string = sprintf($ammount);
                $count = 0;
                while (true) {
                    if ($string[$count] == ".") {
                        $count += 4;
                        $substring = substr($string, 0, $count);
                        $convertedfloat = floatval($substring);
                        $value = number_format($convertedfloat, 3);
                        break;
                    }
                    $count += 1;
                }
            } else {
                return $ammount;
            }
        } else if ($Currencycode == "PKR") {
            $value = round($ammount);
        }

        return $value;
    }


    public static function category_ID_to_Name($id)
    {
        $name = ItemsCategory::find($id)->item_category;
        return $name;
    }

    public static function subcategory_ID_to_Name($id)
    {
        $name = ItemSubCategory::find($id)->item_subcategory;
        return $name;
    }


    public function takeOf_grossWeight_from_totalInventory($id, $gross_weight, $pure_weight, $making)
    {
        $total_inv = Total_Inventory::find($id);
        $total_inv_weight = floatval($total_inv->g_total_weight);
        $total_inv_pure_weight = floatval($total_inv->total_pure_wet);

        $gross_weight = floatval($gross_weight);
        $pure_weight = floatval($pure_weight);

        $new_gross_weight = ($total_inv_weight - $gross_weight);
        $new_pure_weight = ($total_inv_pure_weight - $pure_weight);

        $total_inv->g_total_weight = $new_gross_weight;
        $total_inv->total_pure_wet = $new_pure_weight;

        $total_inv->making = $total_inv->making - $making;

        $total_inv->save();
    }


    public static function itemIDToName($id)
    {
        return ItemsList::find($id)->item_type;
    }


    public static function mixLossStockImpureView($shift_type, $tag)
    {
        $item_name = '';
        $tag_no = '-';
        $vendor_name = '-';
        $gross_wt = '-';
        $shift_to = '-';


        if ($shift_type == 'tagging') {
            $item_name = $tag['item_name'];
            $tag_no = $tag['tagging_detail']['tag_number'];
            $gross_wt = $tag['gross_weight'];
            $shift_to = $tag['shift_to'];
        } elseif ($shift_type == 'vendor') {
            $item_name = UserHelper::itemIDToName($tag['total_inventory']['item_id']);
            $vendor_name = $tag['vendor']['name'];
            $gross_wt = $tag['gross_wt'];
            $shift_to = $tag["shift_to"];
        } elseif ($shift_type == 'melting') {
            $item_name = $tag['item_name'];
            $gross_wt = $tag['gross_weight_g'];
            $shift_to = $tag['shift_to'];
        } elseif ($shift_type == 'add_in_tag') {
            // dd($tag);
            $UserHelper = new UserHelper;
            $tag_detail = $UserHelper->get_name('tagging_details', 'id', $tag['taggin_detail_id']);
            $tag = $UserHelper->get_name('tagging', 'id', $tag['tagging_id']);
            $item_name = $tag->item_name;
            $gross_wt = $tag_detail->jewelery_gross_weight;
            $shift_to = 'add_in_tag';
            $tag_no = $tag_detail->tag_number;
        }

        return ["item_name" => $item_name, "tag_no" => $tag_no, "vendor_name" => $vendor_name, "gross_wt" => $gross_wt, "shift_to" => $shift_to];
    }

    //------------------------------- truncate DB
    public function empty_table()
    {
        $tables = [
            "routing_calculations",
            "routing_calculation_gems",
            "grns",
            "grn_details",
            "entries",
            "entry_items",
            "tagging",
            "tagging_details",
            "tag_gems",
            "tag_metals",
            "tag_numbers",
            "tag_picture",
            "add_in_tags",
            "impure_temp_taggings",
            "good_issuance",
            "good_issuance_detail",
            "good_receipt",
            "good_receipt_detail",
            "impure_gems",
            "impure_history",
            "impure_purchase",
            "impure_purchase_items",
            'routing_meatls',
            'metal_grns',
            "mix_loss_stock_shift_vendors",
            "routing_mix_table_data",
            "mixes",
            "total_inventory",
            "inventory_stock",
            "stock_invertory_history",
            "trash_gems",
            "grn_gems",
            "waste_gold_temp_taggings",
            "waste_gold_history",
            "waste_gold_routing_calculations",
            "routing_calculation_metals",

        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        return "Successfully truncate specific tables";
    }


    public static function item_category()
    {
        $item_category = ItemsCategory::all();
        return $item_category;
    }

    public static function get_tag_gems_wt($tag_id)
    {
        //-----------array format 0 => diamond , 1 => stone , 2 => beads

        $tag_gems = Tagg_Gems::where("tag_detail_id", $tag_id)
            ->selectRaw("sum(gems_weight_gram)")
            ->groupBy("gems_type")
            ->pluck("sum(gems_weight_gram)");

        $gems_value = [];

        for ($i = 0; $i < 3; $i++) {

            if (isset($tag_gems[$i])) {
                array_push($gems_value, $tag_gems[$i]);
            } else {
                array_push($gems_value, 0);
            }
        }


        return $gems_value;
    }


    public static function create_tag_From_mix_save_to_lifeCycle($tag, $description = null, $tagmergehistory_id = null)
    {
        $job = [1, 2];
        for ($i = 0; $i < 2; $i++) {
            TagLifeCycle::create([
                "job" => $job[$i],
                "tag_no" => $tag,
                "job_pending" => $job[$i],
                "tag_routing_info" => $description,
                "user_id" => Auth::id(),
                "tag_merge_histories_id" => $tagmergehistory_id ?? 0
            ]);
        }
    }

    public static function create_tag_life_cycle($tag_no, $job, $job_pending, $good_issuance_job = Null, $tag_routing_info = Null)
    {
        TagLifeCycle::create([
            "job" => $job,
            "tag_no" => $tag_no,
            "job_pending" => $job_pending,
            "good_issuance_job" => $good_issuance_job,
            "tag_routing_info" => $tag_routing_info,
            "user_id" => Auth::id()
        ]);
    }




    //    public static function getDatesBetween($start_date, $end_date)
//    {
//        $start_date = Carbon::parse($start_date);
//        $end_date = Carbon::parse($end_date);
//
//        $dates = [];
//
//        while ($start_date <= $end_date) {
//            $dates[] = $start_date->toDateString();
//            $start_date->addDay();
//        }
//
//        return $dates;
//    }

    public static function getDatesBetween($start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        $dates = [];

        while ($start_date <= $end_date) {
            if ($start_date->dayOfWeek !== Carbon::SATURDAY && $start_date->dayOfWeek !== Carbon::SUNDAY) {
                $dates[] = $start_date->toDateString();
            }

            $start_date->addDay();
        }

        return $dates;
    }


    // Helper file (e.g., app/Helpers/DayHelper.php)

    public static function getDayName($dayNumber)
    {
        switch ($dayNumber) {
            case 1:
                return 'Monday';
            case 2:
                return 'Tuesday';
            case 3:
                return 'Wednesday';
            case 4:
                return 'Thursday';
            case 5:
                return 'Friday';
            case 6:
                return 'Saturday';
            case 7:
                return 'Sunday';
            default:
                return 'Invalid Day';
        }
    }


    public static function getReligions()
    {
        return [
            "African Traditional & Diasporic",
            "Agnostic",
            "Atheist",
            "Baha'i",
            "Buddhism",
            "Cao Dai",
            "Chinese traditional religion",
            "Christianity",
            "Hinduism",
            "Islam",
            "Jainism",
            "Juche",
            "Judaism",
            "Neo-Paganism",
            "Nonreligious",
            "Rastafarianism",
            "Secular",
            "Shinto",
            "Sikhism",
            "Spiritism",
            "Tenrikyo",
            "Unitarian-Universalism",
            "Zoroastrianism",
            "primal-indigenous",
            "Other"
        ];
    }

    public static function getNationalities()
    {
        return [
            'Afghan',
            'Albanian',
            'Algerian',
            'American',
            'Andorran',
            'Angolan',
            'Antiguans',
            'Argentinean',
            'Armenian',
            'Australian',
            'Austrian',
            'Azerbaijani',
            'Bahamian',
            'Bahraini',
            'Bangladeshi',
            'Barbadian',
            'Barbudans',
            'Batswana',
            'Belarusian',
            'Belgian',
            'Belizean',
            'Beninese',
            'Bhutanese',
            'Bolivian',
            'Bosnian',
            'Brazilian',
            'British',
            'Bruneian',
            'Bulgarian',
            'Burkinabe',
            'Burmese',
            'Burundian',
            'Cambodian',
            'Cameroonian',
            'Canadian',
            'Cape Verdean',
            'Central African',
            'Chadian',
            'Chilean',
            'Chinese',
            'Colombian',
            'Comoran',
            'Congolese',
            'Costa Rican',
            'Croatian',
            'Cuban',
            'Cypriot',
            'Czech',
            'Danish',
            'Djibouti',
            'Dominican',
            'Dutch',
            'East Timorese',
            'Ecuadorean',
            'Egyptian',
            'Emirian',
            'Equatorial Guinean',
            'Eritrean',
            'Estonian',
            'Ethiopian',
            'Fijian',
            'Filipino',
            'Finnish',
            'French',
            'Gabonese',
            'Gambian',
            'Georgian',
            'German',
            'Ghanaian',
            'Greek',
            'Grenadian',
            'Guatemalan',
            'Guinea-Bissauan',
            'Guinean',
            'Guyanese',
            'Haitian',
            'Herzegovinian',
            'Honduran',
            'Hungarian',
            'Icelander',
            'Indian',
            'Indonesian',
            'Iranian',
            'Iraqi',
            'Irish',
            'Israeli',
            'Italian',
            'Ivorian',
            'Jamaican',
            'Japanese',
            'Jordanian',
            'Kazakhstani',
            'Kenyan',
            'Kittian and Nevisian',
            'Kuwaiti',
            'Kyrgyz',
            'Laotian',
            'Latvian',
            'Lebanese',
            'Liberian',
            'Libyan',
            'Liechtensteiner',
            'Lithuanian',
            'Luxembourger',
            'Macedonian',
            'Malagasy',
            'Malawian',
            'Malaysian',
            'Maldivan',
            'Malian',
            'Maltese',
            'Marshallese',
            'Mauritanian',
            'Mauritian',
            'Mexican',
            'Micronesian',
            'Moldovan',
            'Monacan',
            'Mongolian',
            'Moroccan',
            'Mosotho',
            'Motswana',
            'Mozambican',
            'Namibian',
            'Nauruan',
            'Nepalese',
            'Netherlander',
            'New Zealander',
            'Ni-Vanuatu',
            'Nicaraguan',
            'Nigerian',
            'Nigerien',
            'North Korean',
            'Northern Irish',
            'Norwegian',
            'Omani',
            'Pakistani',
            'Palauan',
            'Panamanian',
            'Papua New Guinean',
            'Paraguayan',
            'Peruvian',
            'Polish',
            'Portuguese',
            'Qatari',
            'Romanian',
            'Russian',
            'Rwandan',
            'Saint Lucian',
            'Salvadoran',
            'Samoan',
            'San Marinese',
            'Sao Tomean',
            'Saudi',
            'Scottish',
            'Senegalese',
            'Serbian',
            'Seychellois',
            'Sierra Leonean',
            'Singaporean',
            'Slovakian',
            'Slovenian',
            'Solomon Islander',
            'Somali',
            'South African',
            'South Korean',
            'Spanish',
            'Sri Lankan',
            'Sudanese',
            'Surinamer',
            'Swazi',
            'Swedish',
            'Swiss',
            'Syrian',
            'Taiwanese',
            'Tajik',
            'Tanzanian',
            'Thai',
            'Togolese',
            'Tongan',
            'Trinidadian or Tobagonian',
            'Tunisian',
            'Turkish',
            'Tuvaluan',
            'Ugandan',
            'Ukrainian',
            'Uruguayan',
            'Uzbekistani',
            'Venezuelan',
            'Vietnamese',
            'Welsh',
            'Yemenite',
            'Zambian',
            'Zimbabwean'
        ];
    }

    public static function getPakistaniBanks()
    {
        return [
            "Al Baraka Bank (Pakistan) Limited",
            "Allied Bank Limited (ABL)",
            "Askari Bank",
            "Bank Alfalah Limited (BAFL)",
            "Bank Al-Habib Limited (BAHL)",
            "BankIslami Pakistan Limited",
            "Bank Makramah Limited (BML)",
            "Bank of Punjab (BOP)",
            "Bank of Khyber",
            "Deutsche Bank AG (Deutsche Bank Pakistan) Germany",
            "Citi Bank N.A (CitiBank N.A Pakistan) United States",
            "Industrial and Commercial Bank of China Limited (ICBC Pakistan) China",
            "Bank of China (Bank of China Pakistan Branch) China",
            "The Hong Kong and Shanghai Bank (HSBC Bank Pakistan) United Kingdom",
            "The Bank of Tokyo-Mitsubishi UFJ (MUFG Bank Pakistan) Japan",
            "Allied Aitebar Islamic Banking",
            "Meezan Bank Limited",
            "Soneri Mustaqeem Islamic Bank",
            "Dubai Islamic Bank",
            "Al Baraka Bank",
            "Bank Alfalah Islamic",
            "BankIslami Pakistan Limited",
            "Askari Bank Ltd",
            "MCB Islamic Banking",
            "UBL Islamic Banking",
            "HBL Islamic Banking",
            "National Bank of Pakistan",
            "Bank Al Habib Islamic Banking",
            "Bank of Punjab Islamic Banking",
            "Faysal Bank (Islamic)",
            "HabibMetro (Sirat Islamic Banking)",
            "Silk Bank (Emaan Islamic Banking)",
            "Bank Of Khyber (Islamic Window)"
        ];

    }

    public static function getBloodGroups()
    {
        return [
            "A+",
            "A-",
            "B+",
            "B-",
            "AB+",
            "AB-",
            "O+",
            "O-",
        ];
    }

    public static function session_name()
    {
        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];


        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }

        return $sessions;
    }

    public static function classTerms()
    {
        return [
            '1' => '1 Term',
            '2' => '2 Term',
            '3' => '3 Term',
            '4' => '4 Term',
            '5' => '5 Term',
            '6' => '6 Term',
            '7' => '7 Term',
            '8' => '8 Term',
            '9' => '9 Term',
            '10' => '10 Term',
            '11' => '11 Term',
            '12' => '12 Term',
        ];
    }

    public static function feeFactor()
    {
        return [
            '1.00' => '12',
            '1.20' => '10',
            '2.00' => '6',
            '2.40' => '4',
        ];
    }


}
