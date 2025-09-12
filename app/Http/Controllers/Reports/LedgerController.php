<?php

namespace App\Http\Controllers\Reports;

use App\Models\Group;
use Groups;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin\LedgerCurrencies;
use Illuminate\Http\Request;
use App\Helpers\CoreAccounts;
use App\Http\Controllers\Controller;
use App\Models\Admin\Currencies;
use App\Models\Admin\Entries;
use App\Models\Admin\EntryItems;
use App\Models\Admin\EntryTypes;
use App\Models\Purchase\RoutingCalculations;
use App\Models\Admin\Ledgers;
use DB;
use Config;

class LedgerController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $currencies = Currencies::pluck('id', 'code');

        $ledger = Ledgers::all();
        return view('admin.ledger_reports.index', compact('currencies', 'ledger'));
    }

    public function basic_ledger_report()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (!Gate::allows('ledgers_manage')) {
            return abort(503);
        }
        $currencies = Currencies::pluck('id', 'code');
        return view('admin.ledger_reports.basic_ledger_reports', compact('currencies'));
    }

    public function get_ledger_rep(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $start_date = date('Y-m-d', strtotime($request->start_range));
        $end_date = date('Y-m-d', strtotime($request->end_range));

        $balance = 0.00;
        $ob = 0;
        $ledgerID = $request->leadger_id;
        $Entrie = EntryItems::where(['ledger_id' => $ledgerID])->whereBetween('voucher_date', [$start_date, $end_date])->get();
        $array = array();
        $currency = Currencies::where('id', $request->currency)->first();

        //        $currency = Currencies::where('id', $request->currency)->pluck('rate');
//        $decimal = Currencies::where('id', $request->currency)->first('decimal_fixed_point');


        //fetch opening balance as on
        $opening_balance = CoreAccounts::combine_ob($start_date, $currency->rate, $ledgerID, $request->currency);

        $ob_str = '<td colspan="6" align="right">Opening Balance AS on ' . $start_date . '</td>
            <td align="right">' . CoreAccounts::dr_cr_balance($opening_balance, $currency->decimal_fixed_point) . '</td>';

        foreach ($Entrie as $Ent) {

            $dr = 0.00;
            $cr = 0.00;
            $vn = Entries::where('id', $Ent->entry_id)->pluck('number');
            $vt = EntryTypes::where('id', $Ent->entry_type_id)->pluck('name');

            $baseCurrRate = Currencies::where('id', $Ent->currence_type)->pluck('rate');

            if ($Ent->dc == 'd') {

                //                $dr = floatval($Ent->amount) * floatval($baseCurrRate[0]) / $currency->rate;
                $dr = floatval($Ent->amount);

            } else {

                //                $cr = floatval($Ent->amount) * floatval($baseCurrRate[0]) / $currency->rate;
                $cr = floatval($Ent->amount);

            }

            $ob += ($dr) - ($cr);
            $balance = $opening_balance + $ob;
            $balance = number_format($balance, $currency->decimal_fixed_point, '.', '');
            if (count($vt) > 0) {
                if (isset($vt[0]) && isset($vn[0])) {
                    $array[] = array(
                        'voucher_date' => $Ent->voucher_date,
                        'number' => $vn[0],
                        'vt' => $vt[0],
                        'narration' => $Ent->narration,
                        'dr_amount' => number_format($dr, $currency->decimal_fixed_point),
                        'cr_amount' => number_format($cr, $currency->decimal_fixed_point),
                        'balance' => CoreAccounts::dr_cr_balance($balance, $currency->decimal_fixed_point)
                    );
                } else {
                    $array[] = array(
                        'voucher_date' => $Ent->voucher_date,
                        'number' => '',
                        'vt' => '',
                        'narration' => $Ent->narration,
                        'dr_amount' => number_format($dr, $currency->decimal_fixed_point),
                        'cr_amount' => number_format($cr, $currency->decimal_fixed_point),
                        'balance' => CoreAccounts::dr_cr_balance($balance, $currency->decimal_fixed_point)
                    );
                }
            } else {
                $array[] = array(
                    'voucher_date' => $Ent->voucher_date,
                    'number' => '',
                    'vt' => '',
                    'narration' => $Ent->narration,
                    'dr_amount' => number_format($dr, $currency->decimal_fixed_point),
                    'cr_amount' => number_format($cr, $currency->decimal_fixed_point),
                    'balance' => CoreAccounts::dr_cr_balance($balance, $currency->decimal_fixed_point)
                );
            }

        }


        return response()->json(['data' => $array, 'ob' => $ob_str]);
    }

    public function get_basic_ledger_report(Request $request)
    {
        if (!Gate::allows('ledgers_manage')) {
            return abort(503);
        }
        $curRate = Currencies::where('id', 1)->first(['rate', 'decimal_fixed_point']);
        $date_range = explode('-', $request->date_range);
        $start_date = date('Y-m-d', strtotime($date_range[0]));
        $end_date = date('Y-m-d', strtotime($date_range[1]));
        $balance = 0;
        $type = "";
        if (isset($request->type)) {
            $type = $request->type;
        }
        $opening_bal_array = array();
        $closing_bal_array = array();
        $ledgerID = $request->leadger_id;
        $ledger_name = Ledgers::where('id', $ledgerID)->first('name');
        //fetch all opening balance against ledger id

        if ($request->currency > 0) {
            $ledgerCurrency = LedgerCurrencies::where('ledger_id', $ledgerID)->where('currency_id', $request->currency)->get();
        } else {
            $ledgerCurrency = LedgerCurrencies::where('ledger_id', $ledgerID)->get();
        }

        foreach ($ledgerCurrency as $ledgerCurr) {
            $curRate = Currencies::where('id', $ledgerCurr->currency_id)->first(['rate', 'decimal_fixed_point']);
            $ob_amount = CoreAccounts::opening_balance($start_date, $curRate->rate, $ledgerID, $ledgerCurr->currency_id);
            $endDate = date('Y-m-d', strtotime('+1 day', strtotime($end_date)));
            $cb_amount = CoreAccounts::opening_balance($endDate, $curRate->rate, $ledgerID, $ledgerCurr->currency_id);
            $currency_code = Currencies::where('id', $ledgerCurr->currency_id)->value('code');
            $opening_bal_array[] = array('as_on_date' => $start_date, 'currency' => $currency_code, 'amount' => CoreAccounts::dr_cr_balance($ob_amount, $curRate->decimal_fixed_point));
            $closing_bal_array[] = array('as_on_date' => $end_date, 'currency' => $currency_code, 'amount' => CoreAccounts::dr_cr_balance($cb_amount, $curRate->decimal_fixed_point));
        }

        if (isset($request->currency) && $request->currency > 0) {
            $Entrie = EntryItems::where(['ledger_id' => $ledgerID])->where('other_currency_type', $request->currency)->whereBetween('voucher_date', [$start_date, $end_date])->get();
        } else {
            $Entrie = EntryItems::where(['ledger_id' => $ledgerID])->whereBetween('voucher_date', [$start_date, $end_date])->get();
        }
        $array = array();
        $currency = Currencies::where('id', $request->currency)->pluck('rate');
        foreach ($Entrie as $Ent) {
            $dr = '0.00';
            $cr = '0.00';
            $decimal =
                $vn = Entries::where('id', $Ent->entry_id)->pluck('number');
            $vt = EntryTypes::where('id', $Ent->entry_type_id)->pluck('name');
            $baseCurr = Currencies::where('id', $Ent->other_currency_type)->first(['code', 'decimal_fixed_point']);
            if ($Ent->dc == 'd') {
                $dr = ($Ent->other_amount);
            } else if ($Ent->dc == 'c') {
                $cr = ($Ent->other_amount);

            }
            $array[] = array(
                'voucher_date' => $Ent->voucher_date,
                'number' => $vn[0],
                'vt' => $vt[0],
                'narration' => $Ent->narration,
                'dr_amount' => number_format($dr, $curRate->decimal_fixed_point),
                'cr_amount' => number_format($cr, $baseCurr->decimal_fixed_point),
                'currency' => $baseCurr->code
            );
        }
        $content = '';

        if ($type == 'print') {
            return view('admin.ledger_reports.print_basic_ledger_report', compact('array', 'opening_bal_array', 'closing_bal_array', 'ledger_name', 'start_date', 'end_date'));
        } elseif ($type == 'excel') {
            return self::excel_basic_ledgerReport($array, $opening_bal_array, $closing_bal_array);
        } else {
            return response()->json(['data' => $array, 'ob' => $opening_bal_array, 'closing_balance' => $closing_bal_array]);
        }

    }

    //convert basic ledger report to excel
    public
        function excel_basic_ledgerReport(
        $array,
        $opening_array,
        $closing_array
    ) {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
        header('Content-disposition: attachment; filename=' . rand() . '.xls');
        $data = '';
        $string = "";
        $data .= '<style>
                table{width: 100%;}
                td,th {
                    border: 0.1pt solid #ccc;
                }
                </style>';
        $data .= excel_header();
        $data .= '<tr></tr>';
        $data .= '<tr><th colspan="6" align="center">Basic Ledger Report</th></tr>';
        $data .= '<tr>
            <th width="10%" align="center">Date</th>
            <th width="5%" align="center">VN</th>
            <th width="12%" align="center">Voucher Type</th>
            <th width="40%">Descriptions</th>
            <th width="5%" align="center">Currency </th>
            <th width="10%" align="center">Debit </th>
            <th width="10%" align="center">Credit </th>
        </tr>';
        foreach ($opening_array as $ob) {
            $data .= '<tr>
            <td colspan="6" align="right">Opening Balance As on in ' . $ob['currency'] . ' ' . $ob['as_on_date'] . '</td>
            <td style="text-align: right">' . $ob['amount'] . '</td>
        </tr>';
        }
        foreach ($array as $arr) {
            $data .= '<tr>
                    <td>' . $arr['voucher_date'] . '</td>
                    <td>' . $arr['number'] . '</td>
                    <td>' . $arr['vt'] . '</td>
                    <td style="text-align: left">' . $arr['narration'] . '</td>
                    <td>' . $arr['currency'] . '</td>
                    <td>' . $arr['dr_amount'] . '</td>
                    <td style="text-align: right">' . $arr['cr_amount'] . '</td>
                </tr>';
        }
        foreach ($closing_array as $cb) {
            $data .= '<tr>
            <th colspan="6" align="right">Opening Balance As on in ' . $cb['currency'] . '</th>
            <th style="text-align: right">' . $cb['amount'] . '</th>
        </tr>';
        }
        echo '<body style="border: 0.1pt solid #ccc"><table>' . $data . '</table></body>';
    }

    public
        function impure_accounts_report(
        Request $request
    ) {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $job_id = RoutingCalculations::get();
        $data = $request->all();
        if (!empty($data['job_id'])) {
            $routing_id = $data['job_id'];
        } else {
            $routing_id = 0;
        }
        return view('admin.account_reports.Impure_reports.index', compact('job_id', 'routing_id'));

    }

    public
        function impure_accounts_report_view(
        $id
    ) {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ledger = Config::get('constants.Impure_Purchase_Profit_loss');
        $paid_amnt = RoutingCalculations::find($id);
        $entriesItem = EntryItems::where('ledger_id', $ledger)->where('job_id', $id)->get();
        $credit_amnt = EntryItems::where('ledger_id', $ledger)->where('job_id', $id)->where('dc', 'c')->select(['id', DB::raw('sum(amount) as amount')])->first();
        $debit_amnt = EntryItems::where('ledger_id', $ledger)->where('job_id', $id)->where('dc', 'd')->select(['id', DB::raw('sum(amount) as amount')])->first();
        // dd($credit_amnt);
        return view('admin.account_reports.Impure_reports.view', compact('paid_amnt', 'entriesItem', 'debit_amnt', 'credit_amnt'));
    }




    public function coaListing()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // \Illuminate\Support\Facades\DB::table('groups')->truncate();
        // \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = Group::where('level', 4)->with(['groupData', 'parent'])->get();
        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('name', fn($row) => $row->name ?? 'N/A')

            ->addColumn('detail_type', fn($row) => $row->groupData->name ?? 'N/A')

            ->addColumn('type', fn($row) => $row->parent->name ?? 'N/A')
            ->addColumn('code', fn($row) => $row->code ?? 'N/A')

            ->addColumn('status', function ($row) {
                return '
                <div class="form-check form-switch">
                    <input class="form-check-input start-50 translate-middle-x ms-0 shadow-none" value="' . $row->status . '" data-id="' . $row->id . '" type="checkbox" role="switch" id="status-switch"' . ($row->status ? 'checked' : '') . ' >
                </div>';
            })

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" action="' . route("admin.groups.destroy", $row->id) . '" id="vendor-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary edit-btn me-2 btn-sm text-white vendor_edit">Edit</a>';
                $btn .= '<button type="submit" class="btn btn-danger delete-op btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                return $btn;
            })

            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $group = Group::findOrFail($id);

        $request->validate([
            'status' => 'required|boolean',
        ]);

        $group->status = $request['status'];
        $group->save();

        return response()->json([
            'message' => 'Group status updated successfully.',
            'status' => $group->status,
        ]);
    }

}
