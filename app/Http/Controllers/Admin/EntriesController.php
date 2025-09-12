<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CoreAccounts;
use App\Helpers\Currency;
use App\Helpers\GroupsTree;
use App\Models\Admin\Currencies;
use App\Models\Admin\LedgerCurrencies;
use App\Models\Admin\EntryItems;
use App\Models\Admin\EntryTypes;
use App\Models\Admin\Groups;
use App\Models\Admin\Settings;
use Dompdf\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\Ledgers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\Admin\Entries;
use App\Models\Admin\Branches;
use App\Models\HRM\Employees;
use App\Models\Admin\Departments;
use App\Models\Admin\LcBankModel;
use App\Models\Admin\PerformaStockModel;
use App\Models\Admin\DutyModel;
use App\Helper\Helpers;

use Auth;
use function Sodium\compare;
use Validator;
use PDF;
use Config;

class EntriesController extends Controller
{
    /**
     * Display a listing of Entrie.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $EntryTypes = EntryTypes::all()->getDictionary();
        $Entries = Entries::OrderBy('created_at', 'desc')->get();
        $DefaultCurrency = Currencies::getDefaultCurrencty();

        return view('admin.entries.index', compact('Entries', 'EntryTypes', 'DefaultCurrency'));
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $EntryTypes = EntryTypes::all()->getDictionary();
        $Entries = Entries::with('entry_items')->OrderBy('created_at', 'desc')->get();


        return DataTables()->of($Entries)
            ->addColumn('voucher_date', function ($Entries) {
                if ($Entries->voucher_date)
                    return date('d-m-Y', strtotime($Entries->voucher_date));
                else
                    return 'N/A';
            })
            ->addColumn('dr_total', function ($Entries) {

                return Helpers::debit_amount_sum($Entries->id);

            })->addColumn('cr_total', function ($Entries) {
                return Helpers::credit_amount_sum($Entries->id);

            })
            ->addColumn('voucher_month', function ($Entries) {
                if ($Entries->voucher_date)
                    return date('m', strtotime($Entries->voucher_date));
                else
                    return 'N/A';
            })
            ->addColumn('entry_type', function ($Entries) {
                $EntryTypes = EntryTypes::all()->getDictionary();
                $text = '<p>'
                    .
                    $EntryTypes[$Entries->entry_type_id]->code
                    . '</p>';
                if ($EntryTypes[$Entries->entry_type_id])
                    return $text;
                else
                    return 'N/A';


            })->addColumn('action', function ($Entries) {
                // if ($Entries->status == 1) {
                //     $action_column .= '<form method="post" action="entries/inactive/' . $Entries->id . '" >';
                //     $action_column .= csrf_field();
                //     $action_column .= '<input type="hidden" value=' . $Entries->id . ' name="name" >';
                //     $action_column .= '<input   value="Inactivate"  class="btn btn-xs mb-1 btn-warning" type="submit"  onclick="return confirm(\'Are you sure to inactivate this record? \')"> </form>';
    
                // } else {
                //     $action_column .= '<form method="post" action="entries/activate/' . $Entries->id . '" >';
                //     $action_column .= csrf_field();
                //     $action_column .= '<input type="hidden" value=' . $Entries->id . ' name="name" >';
                //     $action_column .= '<input   value="Activate"  class="btn btn-xs mb-1 btn-primary" type="submit"  onclick="return confirm(\'Are you sure you want to activate this record? \')"> </form>';
    
                // }
                /*$action_column .= '<form method="post" action="entries/destroy/'.$Entries['id'].'" >';
                $action_column .= '<input type="hidden" value="DELETE" name="_methode" >';
                $action_column .= '<input type="hidden" value='.$Entries['id'].' name="name" >';
                $action_column .=  csrf_field();
                $action_column .= '<input   value="Delete"  class="btn btn-xs btn-danger" type="submit"  onclick="return confirm(\'Are you sure you want to delete this record? \')"> </form>';*/
                $action_column = '<a href="entries/' . $Entries->id . '/entry" class="btn btn-xs btn-info">View</a></br>';//                    $action_column .= '<a href="entries/'.$Entries->id.'/edit" class="btn btn-xs btn-info">Edit</a></br>';
                ;
                return $action_column;
            })->rawColumns(['voucher_date', 'voucher_month', 'entry_type', 'action'])
            ->toJson();
    }

    /**
     * Show Entry View from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function entry($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Entrie = Entries::findOrFail($id);
        $EntryType = EntryTypes::findOrFail($Entrie->entry_type_id);
        $EntryTypes = EntryTypes::all();
        $Employee = null;
        $Branch = Branches::find($Entrie->branch_id);
        $Department = Departments::find($Entrie->department_id);
        $DefaultCurrency = Currencies::getDefaultCurrencty();
        $Entry_items = EntryItems::with('currenciesA')->where(['entry_id' => $Entrie->id])->OrderBy('dc', 'asc')->get();

        $Ledgers = Ledgers::whereIn(
            'id',
            EntryItems::where(['entry_id' => $Entrie->id])->pluck('ledger_id')->toArray()
        )->get()->getDictionary();

        switch ($Entrie['entry_type_id']) {
            case '1':
                return view('admin.entries.voucher.journal_voucher.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '2':
                // This is Cash Receipt Voucher
                return view('admin.entries.voucher.cash_voucher.cash_receipt.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '3':
                // This is Cash Payment Voucher
                return view('admin.entries.voucher.cash_voucher.cash_payment.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '4':
                // This is Bank Receipt Voucher
                return view('admin.entries.voucher.bank_voucher.bank_receipt.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '5':
                // This is Bank Payment Voucher
                return view('admin.entries.voucher.bank_voucher.bank_payment.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '6':
                // This is Bank Payment Voucher
                return view('admin.entries.voucher.lc_voucher.lc_payment.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '7':
                // This is Bank Payment Voucher
                //return view('admin.entries.voucher.lc_receipt.lc_receipt.entry', compact('Entrie','id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return view('admin.entries.voucher.journal_voucher.entry', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;

            default:
                return view('admin.entries.voucher.journal_voucher.entry', compact('Entrie', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
        }

    }

    /**
     * Show Entry from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Entrie = Entries::findOrFail($id);

        return view('admin.entries.view', compact('Entrie'));
    }

    /**
     * Show the form for creating new Entrie.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.entries.create');
    }

    public function downloadPDF($id)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Entrie = Entries::findOrFail($id);

        $EntryType = EntryTypes::findOrFail($Entrie->entry_type_id);

        $EntryTypes = EntryTypes::all();
        $Branch = Branches::find($Entrie->branch_id);
        //        $Employee = Employees::find($Entrie->employee_id);
        $Employee = Employees::where('user_id', $Entrie->employee_id)->first();
        $Department = Departments::find($Entrie->department_id);
        $DefaultCurrency = Currencies::getDefaultCurrencty();

        //dd( $Suppliers);
        // Get Entry Items Associated with this Entry
        $Entry_items = EntryItems::where(['entry_id' => $Entrie->id])->OrderBy('id', 'asc')->get();
        $Ledgers = Ledgers::whereIn(
            'id',
            EntryItems::where(['entry_id' => $Entrie->id])->pluck('ledger_id')->toArray()
        )->get()->getDictionary();

        switch ($Entrie['entry_type_id']) {
            case '1':
                // This is journal voucher
                $pdf = PDF::loadView('admin.entries.voucher.journal_voucher.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '2':
                // This is Cash Receipt Voucher
                $pdf = PDF::loadView('admin.entries.voucher.cash_voucher.cash_receipt.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '3':
                // This is Cash Payment Voucher
                $pdf = PDF::loadView('admin.entries.voucher.cash_voucher.cash_payment.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '4':
                // This is Bank Receipt Voucher
                $pdf = PDF::loadView('admin.entries.voucher.bank_voucher.bank_receipt.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '5':
                // This is Bank Payment Voucher
                $pdf = PDF::loadView('admin.entries.voucher.bank_voucher.bank_payment.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '6':
                // This is Bank Payment Voucher
                $pdf = PDF::loadView('admin.entries.voucher.lc_voucher.lc_payment.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;
            case '7':
                // This is Bank Payment Voucher
                $pdf = PDF::loadView('admin.entries.voucher.lc_receipt.lc_receipt.newpdf_invoice', compact('Entrie', 'id', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                return $pdf->stream('invoice.pdf');
                break;

            default:
                return view('admin.entries.voucher.journal_voucher.entry', compact('Entrie', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
        }
        //        $digit = new \NumberFormatter( 'en_US', \NumberFormatter::SPELLOUT );
//
//
////        $pdf = PDF::loadView('admin.saleseinvoice.pdf_saleseinvoice',compact( 'SalesInvoiceMode','Products','Services','digit'));
//        $pdf = PDF::loadView('admin.entries.voucher.journal_voucher.newpdf_invoice',compact( 'Entrie','id','EntryType','EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
//        return $pdf->stream('invoice.pdf');

    }

    /**
     * Store a newly created Entrie in storage.
     *
     * @param \App\Http\Requests\Admin\StoreEntriesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEntriesRequest $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        Entries::create([
            'name' => $request['name'],
            'shortcode' => $request['shortcode'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'status' => 0,
        ]);

        //        flash('Record has been created successfully.')->success()->important();

        return redirect()->route('admin.entries.index');
    }


    /**
     * Show the form for editing Entrie.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }


        $VoucherData = Entries::findOrFail($id)->toArray();
        $VoucherData['entry_items'] = array();
        $VoucherData['ledger_array'] = array();

        // Fetch Entry Items and insert into voucher Data Array
        $EntryItems = EntryItems::where(['entry_id' => $VoucherData['id']])->OrderBy('id', 'asc')->get()->toArray();

        if (count($EntryItems)) {
            $counter = 1;
            $ledger_ids = array();
            foreach ($EntryItems as $EntryItem) {
                $ledger_ids[] = $EntryItem['ledger_id'];
                $VoucherData['entry_items']['counter'][$counter] = $counter;
                $VoucherData['entry_items']['ledger_id'][$counter] = $EntryItem['ledger_id'];
                $VoucherData['entry_items']['narration'][$counter] = $EntryItem['narration'];
                if ($EntryItem['dc'] == 'd') {
                    $VoucherData['entry_items']['dr_amount'][$counter] = $EntryItem['amount'];
                    $VoucherData['entry_items']['cr_amount'][$counter] = 0;
                } else {
                    $VoucherData['entry_items']['dr_amount'][$counter] = 0;
                    $VoucherData['entry_items']['cr_amount'][$counter] = $EntryItem['amount'];
                }
                $counter++;
            }
            // Get Ledgers for Entries
            $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
        }
        //dd($VoucherData);

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
//        $Departments = Departments::pluckActiveOnly();
//        $Departments->prepend('Select a Department', '');

        switch ($VoucherData['suppliers_id']) {
            case '1':
                // This is journal voucher
                return view('admin.entries.voucher.journal_voucher.edit', compact('Employees', 'Branches', 'VoucherData'));
                break;
            case '2':
                // This is Cash Receipt Voucher
                return view('admin.entries.voucher.cash_voucher.cash_receipt.edit', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
                break;
            case '3':
                // This is Cash Payment Voucher
                return view('admin.entries.voucher.cash_voucher.cash_payment.edit', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
                break;
            case '4':
                // This is Cash Receipt Voucher
                return view('admin.entries.voucher.bank_voucher.bank_receipt.edit', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
                break;
            case '5':
                // This is Cash Payment Voucher
                return view('admin.entries.voucher.bank_voucher.bank_payment.edit', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
                break;
            case '6':
                // This is Bank Payment Voucher
                return view('admin.entries.voucher.lc_voucher.lc_payment.entry', compact('Entrie', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            case '7':
                // This is Bank Payment Voucher
                return view('admin.entries.voucher.lc_receipt.lc_receipt.entry', compact('Entrie', 'EntryType', 'EntryTypes', 'Branch', 'Employee', 'Department', 'Entry_items', 'Ledgers', 'DefaultCurrency'));
                break;
            default:
                return view('admin.entries.voucher.journal_voucher.edit', compact('Employees', 'Branches', 'VoucherData'));
                break;
        }
    }

    /**
     * Update Entrie in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateEntrieRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $response = CoreAccounts::updateEntry($request->all(), $id);

        if ($response['status']) {
            //            flash('Record has been updated successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }


    /**
     * Remove Entrie from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Entrie = Entries::findOrFail($id);
        $Entrie->delete();

        //        flash('Record has been deleted successfully.')->success()->important();

        return redirect()->route('admin.entries.index');
    }

    /**
     * Activate Entrie from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $Entrie = Entries::findOrFail($id);
        $Entrie->update(['status' => 1]);
        EntryItems::where(['entry_id' => $Entrie->id])->update(['status' => 1]);

        //        flash('Record has been activated successfully.')->success()->important();

        return redirect()->route('admin.entries.index')->with('message', 'Record has been activated successfully.');
    }

    /**
     * Inactivate Entrie from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $Entrie = Entries::findOrFail($id);
        $Entrie->update(['status' => 0]);
        EntryItems::where(['entry_id' => $Entrie->id])->update(['status' => 0]);

        //        flash('Record has been inactivated successfully.')->success()->important();

        return redirect()->route('admin.entries.index')->with('message', 'Record has been inactivated successfully.');
    }

    /**
     * Create Journal Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */
    public function gjv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
                // dd( $VoucherData);
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'suppliers_id' => '',
                'currence_type' => '',
                'other_currency_type' => '',
                'rate' => '',
                'measurement' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'narration' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
//        $Employees = Employees::pluckActiveOnly();
//        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Currencies
        $Currencies = Currencies::pluckActiveOnly();
        $Currencies->prepend('Select a Currency', '');

        return view('admin.entries.voucher.journal_voucher.create', compact('Branches', 'VoucherData', 'Currencies'));
    }

    /**
     * Journal Voucher Items Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function gjv_search(Request $request)
    {
        if (isset($request['item']) && $request['item']) {
            $ledgers = Ledgers::where(['status' => 1])
                ->where('name', 'LIKE', "%{$request['item']}%")
                ->orwhere('number', 'LIKE', "%{$request['item']}%")
                ->OrderBy('name', 'asc')->get();
            // dd($ledgers[0]['group_id']);


            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /**
     * Store a newly created Journal Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function gjv_store(Request $request)
    {

        $response = CoreAccounts::createEntry($request->all());

        if ($response['status']) {
            //flash('Record has been created successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }


    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Cash Vouchers Starts -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    /**
     * Create Cash Receipt Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */
    public function crv_create()
    {
        //        if (!Gate::allows('entries_manage')) {
//            return abort(503);
//        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => ''
            );
        }

        // Get All Employees
//        $Employees = Employees::pluckActiveOnly();
//        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.cash_voucher.cash_receipt.create', compact('Branches', 'Departments', 'VoucherData'));
    }

    /**
     * Store a newly created Cash Receipt Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function crv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //$response = CoreAccounts::createEntry($request->all());
        $this->validate($request, [
            'amount.*' => 'required|integer',
            'currency_id.*' => 'required|integer',
            'trans_acc_from.*' => 'required|min:1'
        ]);

        DB::beginTransaction();
        try {
            $count = count($request->currency_id);
            $data['number'] = $request->number;
            $data['entry_type_id'] = 2;
            $data['voucher_date'] = $request->voucher_date;
            $data['created_by'] = Auth::user()->id;
            $data['updated_by'] = Auth::user()->id;
            $data['employee_id'] = Auth::user()->id;
            $data['branch_id'] = Auth::user()->branch_id;
            $data['status'] = 0;
            $data['currence_type'] = 1;
            $data['other_currency_type'] = 1;
            $entry = Entries::create($data);
            $entry->update(array(
                'number' => CoreAccounts::generateNumber($entry->id),
            ));
            // insert per entry item
            if ($entry->id > 0) {
                for ($i = 0; $i < $count; $i++) {
                    if ($request->amount[$i] > 0 && $request->currency_id[$i] > 0) {
                        $array = array(
                            array(
                                'entry_type_id' => 2,
                                'entry_id' => $entry->id,
                                'ledger_id' => $request->trans_acc_from[$i],
                                'voucher_date' => $request->voucher_date,
                                'amount' => $request->amount[$i],
                                'other_amount' => $request->amount[$i],
                                'dc' => 'c',
                                'currence_type' => $request->currency_id[$i],
                                'other_currency_type' => $request->currency_id[$i],
                                'narration' => $request->narration[$i]
                            ),
                            array(
                                'entry_type_id' => 2,
                                'entry_id' => $entry->id,
                                'ledger_id' => $request->trans_acc_to,
                                'voucher_date' => $request->voucher_date,
                                'amount' => $request->amount[$i],
                                'other_amount' => $request->amount[$i],
                                'dc' => 'd',
                                'currence_type' => $request->currency_idd[$i],
                                'other_currency_type' => $request->currency_id[$i],
                                'narration' => $request->narration[$i]
                            ),
                        );
                        EntryItems::insert($array);
                        $entry_items_fetch = EntryItems::where('entry_id', $entry->id)->get();


                        foreach ($entry_items_fetch as $item_fetch) {
                            if ($item_fetch->dc == "d") {
                                $ledger = Ledgers::where('id', $item_fetch->ledger_id)->first();
                                $vendor_id = $ledger->parent_type;
                                CoreAccounts::_insert_report_item($entry, $item_fetch, $vendor_id);
                            }
                        }
                        DB::commit();
                    }
                }

            }
            return redirect()->back()->with('message', 'Cash Receipt Voucher Created Successfully...');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'You added new items, follow next step!');
        }
        //        if($response){
//        return redirect()->back()->with('message', 'Cash Receipt Voucher Created Successfully...');
////        if($response['status']) {
////            //flash('Record has been created successfully.')->success()->important();
////            //return redirect()->back()->with('message', 'IT WORKS!');
////        }
//         }else {
//            $request->flash();
//            return redirect()->back()
//                ->withErrors($response['error'])
//                ->withInput();
//        }
    }

    /**
     * All Items except Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function crv_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            //            $Setting = Settings::findOrFail(Config::get('constants.accounts_cash_banks_head_setting_id'));
//            $parentGroups = new GroupsTree();
//            $parentGroups->current_id = -1;
//            $parentGroups->build($Setting->description);
            //$parentGroups->toListArray($parentGroups, -1);
            $ledgers = Ledgers::where(['status' => 1])
                //->whereNotIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /**
     * Create Cash Payment Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */
    public function cpv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => ''
            );
        }

        // Get All Employees
//        $Employees = Employees::pluckActiveOnly();
//        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');
        return view('admin.entries.voucher.cash_voucher.cash_payment.create', compact('VoucherData'));
    }

    /**
     * Store a newly created Cash Payment Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cpv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //$response = CoreAccounts::createEntry($request->all());
        $this->validate($request, [
            'amount.*' => 'required|integer',
            'narration.*' => 'required'
        ]);
        $response = CoreAccounts::createPVEntry($request->all());

        //        if($response['status']) {
//            flash('Record has been created successfully.')->success()->important();
//            //return redirect()->route('admin.entries.index');
//            return view('admin.entries.index');
//        } else {
//            $request->flash();
//            return redirect()->back()
//                ->withErrors($response['error'])
//                ->withInput();
//        }
//        return view('admin.entries.index');
        return redirect()->back()->with('message', 'Cash Payment Voucher Created Successfully...');
    }

    /**
     * All Items except Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cpv_search(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            //            $Setting = Settings::findOrFail(Config::get('constants.accounts_cash_banks_head_setting_id'));
//            $parentGroups = new GroupsTree();
//            $parentGroups->current_id = -1;
//            $parentGroups->build($Setting->description);
//            $parentGroups->toListArray($parentGroups, -1);

            $ledgers = Ledgers::where(['status' => 1])
                //->whereNotIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Cash Vouchers Ends -----------------------------------
     * ----------------------------------------------------------------------------------------
     */


    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Banks Vouchers Starts -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    /**
     * Create Bank Receipt Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */
    public function brv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'narration' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.bank_voucher.bank_receipt.create', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
    }

    /**
     * Store a newly created Cash Receipt Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function brv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $response = CoreAccounts::createEntry($request->all());

        if ($response['status']) {
            //            flash('Record has been created successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }

    /**
     * All Items except Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function brv_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            $Setting = Settings::findOrFail(Config::get('constants.accounts_cash_banks_head_setting_id'));
            $parentGroups = new GroupsTree();
            $parentGroups->current_id = -1;
            $parentGroups->build($Setting->description);
            $parentGroups->toListArray($parentGroups, -1);

            $ledgers = Ledgers::where(['status' => 1])
                //->whereNotIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /**
     * Create LC Payment Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */

    public function lcpv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
                //echo '<pre>';print_r($VoucherData);echo '</pre>';exit;
            } else {
                $VoucherData['ledger_array'] = array();
            }

        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'lc_duties' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');
        // Get All Duties
        $DutyModel = DutyModel::pluckActiveOnly();
        $DutyModel->prepend('Select a Duty', '');
        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.lc_voucher.lc_payment.create', compact('DutyModel', 'Employees', 'Branches', 'Departments', 'VoucherData'));
    }


    /**
     * Store a newly created LC Payment Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function lcpv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $response = CoreAccounts::createLcEntry($request->all());
        if ($response['status']) {
            //            flash('Record has been created successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }

    /**
     * All Items except LC Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function lcpv_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            $ledgers = LcBankModel::selectRaw('lcbank.*,c_invoice.ci_no as invoice_no,c_invoice.id as cid')
                ->join('c_invoice', 'c_invoice.lcno', '=', 'lcbank.id')
                ->where('lcbank.lc_no', 'LIKE', "%{$request['item']}%")
                ->get();
            //
            // echo '<pre>';print_r($ledgers);echo'</pre>';exit;
//            $ledgers = LcBankModel::where(['status' => 1])
//                //->whereIn('group_id', $parentGroups->groupListIDs)
//                ->where(function ($query) {
//                    global $request;
//                    $query
//                        //->where('name', 'LIKE', "%{$request['item']}%")
//                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
//                })->OrderBy('name','asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    //                    $prefix = Ledgers::getAllParent($ledger->group_id);
//                    if($prefix == '0'){
//                        $text_ledger = '('. $ledger->groups['name'] .')';
//                    }else{
//                        $text_ledger = $prefix;
//                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $ledger->lc_no . ' - ' . $ledger->invoice_no,
                        'id' => $ledger->cid,
                    );
                }
            }
            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- LC Vouchers Ends -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    /**
     * Create LC receipit Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */

    public function lrp_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
                echo '<pre>';
                print_r($VoucherData);
                echo '</pre>';
                exit;
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'lc_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'narration' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');
        $LcBankModel = LcBankModel::where('lc_status', '3')->get();
        $LcBankModelOptions = array();
        foreach ($LcBankModel as $agreement) {
            if ($agreement->transaction_type == '1') {
                $tra_type = "LC";
            } else {
                $tra_type = "TT";
            }
            $LcBankModelOptions[$agreement->id] = $tra_type . " - " . $agreement->lc_no . ' -  $.' . $agreement->lc_amt;
        }

        array_unshift($LcBankModelOptions, "Select a Supplier");
        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.lc_voucher.lc_receipt.create', compact('Employees', 'Branches', 'Departments', 'VoucherData', 'LcBankModelOptions'));
    }


    /**
     * Store a newly created LC Payment Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function lrp_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $response = CoreAccounts::createLcInventory($request->all());
        if ($response['status']) {
            //            flash('Record has been created successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }

    /**
     * All Items except LC Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function lrp_search($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ledger = Ledgers::where('id', $id)->first();
        $lc_name = $ledger->name;
        $lc_name1 = explode("-", $lc_name);
        $lc_name1 = trim($lc_name1[1]);
        $LcBankModel = LcBankModel::where('lc_no', $lc_name1)->first();
        $lc_id = $LcBankModel->id;
        $results = PerformaStockModel::where('lc_no', $lc_id)->get();
        return view('admin.entries.voucher.lc_voucher.lc_receipt.productlist', compact('results'));
    }

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- LC receipt Vouchers Ends -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    /**
     * Create Cash Payment Voucher Entry
     *
     * @return \Illuminate\Http\Response
     */
    public function bpv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'narration' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.bank_voucher.bank_payment.create', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
    }

    /**
     * Store a newly created Cash Payment Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bpv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $response = CoreAccounts::createEntry($request->all());

        if ($response['status']) {
            //            flash('Record has been created successfully.')->success()->important();
            return redirect()->route('admin.entries.index');
        } else {
            //            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();
        }
    }

    /**
     * All Items except Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bpv_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            //            $Setting = Settings::findOrFail(Config::get('constants.accounts_cash_banks_head_setting_id'));
//            $parentGroups = new GroupsTree();
//            $parentGroups->current_id = -1;
//            $parentGroups->build($Setting->description);
//            $parentGroups->toListArray($parentGroups, -1);

            $ledgers = Ledgers::where(['status' => 1])
                //->whereNotIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Cash Vouchers Ends -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Gold Vouchers Starts -----------------------------------
     * ----------------------------------------------------------------------------------------
     */

    public function grv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => ''
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.gold_voucher.gold_receipt.create', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
    }

    /**
     * Store a newly created Cash Receipt Voucher in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function grv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //$response = CoreAccounts::createEntry($request->all());
        $this->validate($request, [
            'amount.*' => 'required|integer',
            'currency_id.*' => 'required|integer',
            'trans_acc_from.*' => 'required|min:1'
        ]);
        DB::beginTransaction();
        try {
            $count = count($request->currency_id);
            $data['number'] = $request->number;
            $data['entry_type_id'] = 2;
            $data['voucher_date'] = $request->voucher_date;
            $data['created_by'] = Auth::user()->id;
            $data['updated_by'] = Auth::user()->id;
            $data['employee_id'] = Auth::user()->id;
            $data['branch_id'] = Auth::user()->branch_id;
            $data['status'] = 0;
            $data['currence_type'] = 1;
            $data['other_currency_type'] = 1;
            $entry = Entries::create($data);
            $entry->update(array(
                'number' => CoreAccounts::generateNumber($entry->id),
            ));
            // insert per entry item
            if ($entry->id > 0) {
                for ($i = 0; $i < $count; $i++) {
                    if ($request->amount[$i] > 0 && $request->currency_id[$i] > 0) {
                        $array = array(
                            array(
                                'entry_type_id' => 2,
                                'entry_id' => $entry->id,
                                'ledger_id' => $request->trans_acc_from[$i],
                                'voucher_date' => $request->voucher_date,
                                'amount' => $request->amount[$i],
                                'other_amount' => $request->amount[$i],
                                'dc' => 'c',
                                'currence_type' => $request->currency_id[$i],
                                'other_currency_type' => $request->currency_id[$i],
                                'narration' => $request->narration[$i]
                            ),
                            array(
                                'entry_type_id' => 2,
                                'entry_id' => $entry->id,
                                'ledger_id' => $request->trans_acc_to,
                                'voucher_date' => $request->voucher_date,
                                'amount' => $request->amount[$i],
                                'other_amount' => $request->amount[$i],
                                'dc' => 'd',
                                'currence_type' => $request->currency_idd[$i],
                                'other_currency_type' => $request->currency_id[$i],
                                'narration' => $request->narration[$i]
                            ),
                        );
                        EntryItems::insert($array);
                        $entry_items_fetch = EntryItems::where('entry_id', $entry->id)->get();
                        foreach ($entry_items_fetch as $item_fetch) {
                            if ($item_fetch->dc == "d") {
                                $ledger = Ledgers::where('id', $item_fetch->ledger_id)->first();
                                $vendor_id = $ledger->parent_type;
                                CoreAccounts::_insert_report_item($entry, $item_fetch, $vendor_id);

                            }
                        }

                        DB::commit();
                    }
                }

            }
            return redirect()->back()->with('message', 'Cash Receipt Voucher Created Successfully...');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'You added new items, follow next step!');
        }
        //        if($response){
//        return redirect()->back()->with('message', 'Cash Receipt Voucher Created Successfully...');
////        if($response['status']) {
////            //flash('Record has been created successfully.')->success()->important();
////            //return redirect()->back()->with('message', 'IT WORKS!');
////        }
//         }else {
//            $request->flash();
//            return redirect()->back()
//                ->withErrors($response['error'])
//                ->withInput();
//        }
    }

    /**
     * All Items except Bank & Cash Search Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function grv_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            //            $Setting = Settings::findOrFail(Config::get('constants.accounts_cash_banks_head_setting_id'));
//            $parentGroups = new GroupsTree();
//            $parentGroups->current_id = -1;
//            $parentGroups->build($Setting->description);
            //$parentGroups->toListArray($parentGroups, -1);
            $ledgers = Ledgers::where(['status' => 1])
                //->whereNotIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix = Ledgers::getAllParent($ledger->group_id);
                    if ($prefix == '0') {
                        $text_ledger = '(' . $ledger->groups['name'] . ')';
                    } else {
                        $text_ledger = $prefix;
                    }
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $text_ledger . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /*
     * ----------------------------------------------------------------------------------------
     * ------------------------------- Gold Vouchers Ends -----------------------------------
     * ----------------------------------------------------------------------------------------
     */


    /**
     * Cash Ledgers Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cash_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            $Group = CoreAccounts::getConfigGroup(Config::get('constants.account_cash_inHand'))['group'];
            $parentGroups = new GroupsTree();
            $parentGroups->current_id = -1;
            $parentGroups->build($Group->id);
            $parentGroups->toListArray($parentGroups, -1);
            $ledgers = Ledgers::where(['status' => 1])
                ->whereIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            //dd($ledgers);
            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $result[] = array(
                        'text' => $ledger->number . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /**
     * Banks Ledger Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bank_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            $Group = CoreAccounts::getConfigGroup(Config::get('constants.account_bank_balance'))['group'];
            //$Group = CoreAccounts::getConfigGroup(Config::get('constants.assets_current_cash_balance'));
            $parentGroups = new GroupsTree();
            $parentGroups->current_id = -1;
            $parentGroups->build($Group->id);
            $parentGroups->toListArray($parentGroups, -1);

            $ledgers = Ledgers::where(['status' => 1])
                ->whereIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $prefix_group = Ledgers::getParent($ledger->group_id);
                    $result[] = array(
                        //'text' => $ledger->number . ' - ' . $ledger->name,
                        'text' => $prefix_group . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    /**
     * Cash & Banks Ledger Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cashbank_search(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request['item']) && $request['item']) {
            $Group = CoreAccounts::getConfigGroup(Config::get('constants.assets_current_cash_balance'))['group'];
            $parentGroups = new GroupsTree();
            $parentGroups->current_id = -1;
            $parentGroups->build($Group->id);
            $parentGroups->toListArray($parentGroups, -1);

            $ledgers = Ledgers::where(['status' => 1])
                ->whereIn('group_id', $parentGroups->groupListIDs)
                ->where(function ($query) {
                    global $request;
                    $query->where('name', 'LIKE', "%{$request['item']}%")
                        ->orwhere('number', 'LIKE', "%{$request['item']}%");
                })->OrderBy('name', 'asc')->get();

            $result = array();
            if ($ledgers->count()) {
                foreach ($ledgers as $ledger) {
                    $result[] = array(
                        'text' => $ledger->number . ' - ' . $ledger->name,
                        'id' => $ledger->id,
                    );
                }
            }

            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    public function get_invList($ledger_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $groupID = Ledgers::where('id', $ledger_id)->value('group_id');
        $invList = [];
        if ($groupID == 25) {
            $invList = EntryItems::where(['dc' => 'c', 'ledger_id' => $ledger_id])->where('grnID', '!=', 0)->groupBy('grnID')->pluck('grnID');
        }
        return response()->json(['data' => $invList]);
    }

    public function get_voucher_balance($ledgerID)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Currencies = LedgerCurrencies::where('ledger_id', $ledgerID)->get();
        $Invoices = EntryItems::where('ledger_id', $ledgerID)->whereNotNull('grnID')->groupBy('grnID')->get('grnID');
        $ob = 0;
        foreach ($Currencies as $currency) {
            if ($currency->balance_type == 'd') {
                $ob = $currency->amount;
            }
            if ($currency->balance_type == 'c') {
                $ob = -($currency->amount);
            }
            $dr = EntryItems::where(['ledger_id' => $ledgerID, 'other_currency_type' => $currency->currency_id, 'dc' => 'd'])->get()->sum('other_amount');
            $cr = EntryItems::where(['ledger_id' => $ledgerID, 'other_currency_type' => $currency->currency_id, 'dc' => 'c'])->get()->sum('other_amount');
            $currency_symbol = Currencies::where('id', $currency->currency_id)->value('code');
            $balance = $ob + $dr - $cr;
            $balance = CoreAccounts::dr_cr_balance($balance);
            $array[] = array($currency_symbol . ' ' . $balance);
        }
        return response()->json(['data' => $array, 'invList' => $Invoices]);
    }

    public function gpv_create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $VoucherData = Session::get('_old_input');
        if (is_array($VoucherData) && !empty($VoucherData)) {
            // Fetch Ledger IDs to create Ledger Objects
            $ledger_ids = array();
            if (isset($VoucherData['entry_items']) && count($VoucherData['entry_items'])) {
                $entry_items = $VoucherData['entry_items'];
                foreach ($entry_items['counter'] as $key => $val) {
                    if (isset($entry_items['ledger_id'][$val]) && $entry_items['ledger_id'][$val]) {
                        $ledger_ids[] = $entry_items['ledger_id'][$val];
                    } else {
                        $VoucherData['entry_items']['ledger_id'][$val] = '';
                    }
                }
            }
            if (count($ledger_ids)) {
                $VoucherData['ledger_array'] = Ledgers::whereIn('id', $ledger_ids)->get()->getDictionary();
            } else {
                $VoucherData['ledger_array'] = array();
            }
        } else {
            $VoucherData = array(
                'number' => '',
                'cheque_no' => '',
                'cheque_date' => '',
                'invoice_no' => '',
                'invoice_date' => '',
                'cdr_no' => '',
                'cdr_date' => '',
                'bdr_no' => '',
                'bdr_date' => '',
                'bank_name' => '',
                'bank_branch' => '',
                'drawn_date' => '',
                'voucher_date' => '',
                'entry_type_id' => '',
                'branch_id' => '',
                'employee_id' => '',
                'department_id' => '',
                'remarks' => '',
                'narration' => '',
                'dr_total' => '',
                'cr_total' => '',
                'diff_total' => '',
                'entry_items' => array(
                    'counter' => array(),
                    'ledger_id' => array(),
                    'dr_amount' => array(),
                    'cr_amount' => array(),
                    'narration' => array(),
                ),
                'ledger_array' => array(),
            );
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        return view('admin.entries.voucher.gold_voucher.gold_payment.create', compact('Employees', 'Branches', 'Departments', 'VoucherData'));
    }

    public function gpv_store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $param = $request->all();
        $data['number'] = $request->number;
        $data['entry_type_id'] = 1;
        $data['voucher_date'] = $request->voucher_date;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['employee_id'] = Auth::user()->id;
        $data['branch_id'] = Auth::user()->branch_id;
        $data['status'] = 0;
        $data['currence_type'] = 3;
        $data['other_currency_type'] = 3;
        $entry = Entries::create($data);
        $entry->update(array(
            'number' => CoreAccounts::generateNumber($entry->id),
        ));
        $total_row = count($param['gold_weight']);
        $curRate = Currencies::where('id', 3)->first('rate');
        //entry items
        for ($i = 0; $i < $total_row; $i++) {


            //           trans_acc_from


            if (isset($param['gold_weight'][$i])) {
                $EntData['ledger_id'] = $param['ledger_id'][$i];
                $EntData['entry_type_id'] = 1;
                $EntData['entry_id'] = $entry->id;
                $EntData['voucher_date'] = $request->voucher_date;
                $EntData['amount'] = $param['gold_weight'][$i];
                $EntData['other_amount'] = $param['gold_weight'][$i];
                $EntData['rate'] = $curRate->rate;
                $EntData['dc'] = 'c';
                $EntData['currence_type'] = 3;
                $EntData['other_currency_type'] = 3;
                $EntData['narration'] = $param['narration'][$i];
                EntryItems::insert($EntData);
                $farqa_ledger = Ledgers::where(['group_id' => 27, 'branch_id' => Auth::user()->branch_id])->first('id');
                if ($request->gold_type == 'swiss') {
                    $EntData['ledger_id'] = $request['trans_acc_from'];
                    $EntData['dc'] = 'd';
                    $EntData['amount'] = $param['gold_weight'][$i] + $param['farqa_weight'][$i];
                    $EntData['other_amount'] = $param['gold_weight'][$i] + $param['farqa_weight'][$i];
                    EntryItems::insert($EntData);
                    $EntData['ledger_id'] = $farqa_ledger->id;
                    $EntData['dc'] = 'c';
                    $EntData['amount'] = $param['farqa_weight'][$i];
                    $EntData['other_amount'] = $param['farqa_weight'][$i];
                    EntryItems::insert($EntData);
                }
                if ($request->gold_type == 'refine') {
                    $EntData['ledger_id'] = $request['trans_acc_from'];
                    $EntData['dc'] = 'd';
                    $EntData['amount'] = $param['gold_weight'][$i] - $param['farqa_weight'][$i];
                    $EntData['other_amount'] = $param['gold_weight'][$i] - $param['farqa_weight'][$i];
                    EntryItems::insert($EntData);
                    $EntData['ledger_id'] = $farqa_ledger->id;
                    $EntData['dc'] = 'd';
                    $EntData['amount'] = $param['farqa_weight'][$i];
                    $EntData['other_amount'] = $param['farqa_weight'][$i];
                    EntryItems::insert($EntData);
                }
                if ($request->gold_type == 'normal') {
                    $EntData['ledger_id'] = $request['trans_acc_from'];
                    $EntData['dc'] = 'd';
                    $EntData['amount'] = $param['gold_weight'][$i] - $param['farqa_weight'][$i];
                    $EntData['other_amount'] = $param['gold_weight'][$i] - $param['farqa_weight'][$i];
                    EntryItems::insert($EntData);
                }
            }
        }

        $entry_items_fetch = EntryItems::where('entry_id', $entry->id)->get();
        foreach ($entry_items_fetch as $item_fetch) {

            if ($item_fetch->dc == "d") {
                $ledger = Ledgers::where('id', $item_fetch->ledger_id)->first();
                $vendor_id = $ledger->parent_type;
                CoreAccounts::_insert_report_item($entry, $item_fetch, $vendor_id);
            }
        }
        $voucher_date = array();
        return redirect()->route('admin.voucher.gpv_create')->with('successMessage', 'Gold Voucher');
    }
}
