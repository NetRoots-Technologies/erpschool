<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account\Ledger;
use App\Models\Admin\Vendor;
use Config;
use DataTables;
use App\Helpers\GroupsTree;
use App\Helper\GroupsTree as GroupTrees;
use App\Helpers\LedgersTree;
use App\Models\Admin\Groups;
use Illuminate\Http\Request;
use App\Helpers\CoreAccounts;
use App\Models\Account\Group;
use App\Models\Admin\Ledgers;
use App\Models\Admin\Session;
use App\Models\Fee\StudentFee;
use App\Models\Admin\Companies;
use App\Models\Admin\Currencies;
use App\Models\Admin\AccountTypes;
use App\Models\Fee\PaidStudentFee;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Models\Admin\LedgerCurrencies;
use Exception;
use App\Models\AccountType;


class LedgersController extends Controller
{
    ////////////////////
    /**
     * Display a listing of Ledger.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        if (!Gate::allows('Ledgers-list')) {
            return abort(503);
        }
        /* Create list of parent groups */

        $parentGroups = new LedgersTree();
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $Ledgers = $parentGroups->ledgerList;

        $DefaultCurrency = Currencies::getDefaultCurrencty();
        $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::OrderBy('id', 'asc')->get()->toArray()), old('group_id'));


        return view('admin.ledgers.index', compact('Ledgers', 'DefaultCurrency', 'Groups'));
    }

    /**
     * Show the form for creating new Ledger.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Ledgers::with('groups')->whereNotIn('group_id', [11, 21]);

        if ($request->group) {
            $data = $data->where('group_id', $request->group);
        }

        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('groups', function ($row) {
                if (isset($row->groups)) {
                    return $row->groups->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['groups'])
            ->make(true);
    }

    public function getDataincome(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Ledgers::where('group_id', 21)->with('groups');

        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('groups', function ($row) {
                if (isset($row->groups)) {
                    return $row->groups->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['groups'])
            ->make(true);
    }

    public function getDatareciable(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Ledgers::where('group_id', 11)->with('groups');
        $data = $data->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('groups', function ($row) {
                if (isset($row->groups)) {
                    return $row->groups->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['groups'])
            ->make(true);
    }

    public function income()
    {
if (!Gate::allows('students')) {
            return abort(503);
        }

        return view('admin.ledgers.income_index');

    }

    public function receivable()
    {
if (!Gate::allows('students')) {
            return abort(503);
        }

        return view('admin.ledgers.ledger_recevieable');

    }

    public function create()
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }

        $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::OrderBy('id', 'asc')->get()->toArray()), old('group_id'));
        $currencies = Currencies::all();
        return view('admin.ledgers.create', compact('Groups', 'currencies'));
    }

    /**
     * Store a newly created Ledger in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
           
            $response = CoreAccounts::createLedger($request->all());
            if ($response['status']) {
                return redirect()->route('admin.ledgers.index');
            }
            $request->flash();

            return redirect()->back()
                ->with("error", $response['error'])
                ->withInput();
        } catch (Exception $e) {
            return redirect()->back()
                ->with("error", $e->getMessage())
                ->withInput();
        }

    }


    /**
     * Show the form for editing Ledger.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
        $Ledger = Ledgers::findOrFail($id);
        $LedgerCurrencies = LedgerCurrencies::where('ledger_id', $id)->get();
        $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::OrderBy('id', 'asc')->get()->toArray()), $Ledger->group_id);

        return view('admin.ledgers.edit', compact('Ledger', 'Groups', 'LedgerCurrencies'));
    }

    public function ledgerChild(Request $request)
    {


        if (!Gate::allows('students')) {
            return abort(503);
        }
        $group = Groups::where('id', $request->group_id)->first();
        $Ledgers = Ledgers::where('group_id', $request->group_id)->get();
        $DefaultCurrency = Currencies::getDefaultCurrencty();
        // dd($Ledgers);

        return view('admin.ledgers.detail', compact('Ledgers', 'DefaultCurrency', 'group'));
    }

    /**
     * Update Ledger in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
        $response = CoreAccounts::updateLedger($request->all(), $id);
        if ($response['status']) {
            // flash('Record has been updated successfully.')->success()->important();
            return redirect()->route('admin.ledgers.index');
        } else {
            $request->flash();
            return redirect()->back()
                ->withErrors($response['error'])
                ->withInput();

            //echo 'Here I am'; exit;
        }
        $Ledger = Ledgers::findOrFail($id);

        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        // Get selected group
        $Group = Groups::findOrFail($data['group_id']);
        // Get Default Company
        $Companie = Companies::findOrFail(Config::get('constants.accounts_company_id'));

        $data['group_number'] = $Group->number;
        $data['account_type_id'] = $Group->account_type_id;
        $data['code'] = CoreAccounts::generateLedgerNumber($Companie->id, $Group->code, $Ledger->id);

        $Ledger->update($data);

        flash('Record has been updated successfully.')->success()->important();

        return redirect()->route('admin.ledgers.index');
    }


    /**
     * Remove Ledger from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if (!Gate::allows('Ledgers-delete')) {
            return abort(503);
        }
        $Ledger = Ledgers::findOrFail($id);
        $Ledger->delete();
        $groups = Groups::all();


        return redirect()->route('admin.ledgers.index')->with('successMessage', 'ledger');
    }

    /**
     * Delete all selected Ledger at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if ($request->input('ids')) {
            $entries = Ledgers::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function get_ledger_tree($id)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = '';
        $Groups = Groups::where('parent_id', $id)->get();
        if ($Groups->isEmpty()) {
            $Ledgers = Ledgers::where('group_id', $id)->get();
            if ($Ledgers->isNotEmpty()) {
                $data .= '<div class="accordion-inner"><ul class="nav nav-list">';
                foreach ($Ledgers as $ledger) {
                    $ledgerCurrency = LedgerCurrencies::where('ledger_id', $ledger->id)->get();
                    $innData = '';
                    foreach ($ledgerCurrency as $ledgerCurr) {
                        $currency_code = Currencies::where('id', $ledgerCurr->currency_id)->first(['code', 'decimal_fixed_point']);
                        $endDate = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
                        $innData .= '<span class="badge bg-green">' . $currency_code->code . ' ' . CoreAccounts::dr_cr_balance(CoreAccounts::opening_balance($endDate, 1, $ledger->id, $ledgerCurr->currency_id), $currency_code->decimal_fixed_point) . '</span> ';
                    }
                    $innData = rtrim($innData, ', ');
                    $data .= '<li><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i> ' . ucfirst($ledger->name) . ' <span style="float: right">' . $innData . '</span></a></li>';
                }
                $data .= '</ul></div>';
            }
        } else {
            foreach ($Groups as $Group) {
                $data .= '
                <div class="accordion-item">

                    <div class="accordion-heading">
                        <a class="accordion-button"
                        aria-expanded="true"
                        data-parent="#equipamento1-1"
                        data-toggle="collapse"
                        href="javascript:void(0)"
                        data-id="' . $Group->id . '"
                        onclick="get_group_ledger(this, ' . $Group->id . ', ' . $Group->level . ');"
                        
                        data-value="' . $Group->code . '"
                        data-level="' . $Group->level . '">' . $Group->code . '-' . $Group->name . '</a>

                    </div>
                    <div class="accordion-body collapse" id="' . $Group->code . '"></div>
                </div>';
            }

        }
        return response()->json(['data' => $data]);
    }


    public function ledger_tree()
    {
        /* Create list of parent groups */
if (!Gate::allows('students')) {
            return abort(503);
        }
        $Groups2 = Groups::where('parent_id', 0)->get();
        $AccountTypes = AccountType::getActiveListDropdown(true);
        $Groups = GroupTrees::buildOptions2(GroupTrees::buildTree(Group::OrderBy('name', 'asc')->get()->toArray()), old('parent_id'));

        $type = $request->type ?? 0;
        $parentGroups = new LedgersTree();
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $Ledgers = $parentGroups->ledgerList;




        $AccountTypes = AccountType::all()->getDictionary();

        $parentGroups = new GroupsTree();
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toListView($parentGroups, -1);
        $Grouping = $parentGroups->groupListView;

        return view('admin.ledgers.ledger_tree', compact('Groups2', 'Groups', 'Ledgers', 'type', 'Grouping'));
    }

    public function fee_collection_index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $students = PaidStudentFee::with('student', 'sessions')->get();
        $sessions = Session::orderby('id', 'DESC')->get();

        return view('admin.ledgers.fee_collection_index', compact('students', 'sessions'));
    }

    public function get_data_fee_collection(Request $request)
    {

if (!Gate::allows('students')) {
            return abort(503);
        }
        if (isset($request->session)) {
            if ($request->session) {
                $session = $request->session;
                $students = StudentFee::where('session_id', $session)->pluck('student_id');


                //                $data = PaidStudentFee::whereHas('sessions', function ($query) use ($session) {
//                    $query->where('id', $session);
//                })->with('course', 'student', 'student_fee.session');
//
                $data = PaidStudentFee::whereIN('student_id', $students)->with('course', 'student', 'student_fee.session');


            }
        } else {
            $data = PaidStudentFee::with('course', 'student', 'sessions', 'student_fee.session');
        }
        if ($request->student_id) {
            $data = $data->where('student_id', $request->student_id);
        }
        if ($request->payment_source) {
            $data = $data->where('source', $request->payment_source);
        }
        if ($request->payment_type) {
            $data = $data->where('type', $request->payment_type);
        }
        if (isset($request->date) && isset($request->date_end)) {
            if ($request->date && $request->date_end) {
                $data = $data->whereBetween('paid_date', [
                    date('Y-m-d', strtotime($request->date)),
                    date('Y-m-d', strtotime($request->date_end))
                ]);
            }
        }
        $data = $data->where('paid_status', 'paid')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('sessions', function ($row) {

                if (isset($row->student_fee->session)) {
                    $btn = $row->student_fee->session->title;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;
            })->addColumn('student_name', function ($row) {
                if (isset($row->student)) {
                    $btn = $row->student->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }
                return $btn;


            })->addColumn('installement_amount', function ($row) {
                if (isset($row->installement_amount)) {
                    if ($row->type != "discount")
                        $btn = $row->installement_amount;
                    return $btn;
                } else {
                    $btn = 0;
                    return $btn;
                }
                return $btn;
            })->addColumn('instalment', function ($row) {
                $btn = 0;
                if (isset($row->installement_amount)) {
                    if ($row->type == "installment")
                        $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }

                return $btn;

            })->addColumn('advance', function ($row) {
                $btn = 0;
                if (isset($row->installement_amount)) {

                    if ($row->type == "advance")
                        $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;

            })->addColumn('discount', function ($row) {
                $btn = 0;
                if ($row->type == "discount") {
                    if ($row->type == "discount")
                        $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;

            })->addColumn('bank', function ($row) {
                $btn = 0;
                if ($row->source == "bank") {
                    $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;
            })->addColumn('jazz_cash', function ($row) {
                $btn = 0;
                if ($row->source == "jazzcash") {
                    $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;
            })->addColumn('cash', function ($row) {
                $btn = 0;
                if ($row->source == "cash") {
                    $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;
            })->addColumn('easypaisa', function ($row) {
                $btn = 0;
                if ($row->source == "easypaisa") {
                    $btn = $row->installement_amount;
                } else {
                    $btn = 0;
                }
                return $btn;
            })
            ->rawColumns(['easypaisa', 'installement_amount', 'sessions', 'student_name', 'total_paid_fee', 'discount', 'Cash', 'jazz_cash', 'bank'])
            ->make(true);
    }

    public function getGeneralLedger()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $coa = Group::where('level', 4)->get();
        return view('admin.accounts.reports.general_reports.general-ledger', compact('coa'));
    }

    public function generalLedgerResult(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Ledger::where('code',$request['coa'])->get();
    }

    public function getSubsidiaryLedger()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $vendor = Vendor::get();
        return view('admin.accounts.reports.general_reports.subsidary-ledger', compact('vendor'));
    }

}
