<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GeneralSettingsHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Models\Admin\FeeCategory;
use App\Models\Admin\FeeHead;
use App\Models\Admin\FeeStructure;
use App\Services\FeeStructureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FeeStructureController extends Controller
{
    protected $feeStructureService;
    public function __construct(FeeStructureService $feeStructureService)
    {
        $this->feeStructureService = $feeStructureService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('fee.fee_structure.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_structure.create', compact('companies', 'sessions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        $existingFeeStructure = FeeStructure::where([
//            'session_id' => $request->get('session_id'),
//            'company_id' => $request->get('company_id'),
//            'branch_id' => $request->get('branch_id'),
//            'class_id' => $request->get('class_id'),
//        ])->first();

        //        if ($existingFeeStructure) {
//            return redirect()->route('admin.fee-structure.index')->with('error', 'Fee Structure already exists.');
//        }

        $this->feeStructureService->store($request);

        return redirect()->route('admin.fee-structure.index')->with('success', 'Fee Structure created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeStructure = FeeStructure::with('feeStructureValue')->find($id);
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_structure.edit', compact('companies', 'sessions', 'feeStructure'));
    }

    /**
     * Update the specified resource in storage.
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    public function feeStructureData(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();

        $feeHeads = FeeHead::with('feeStructureVal')->where([
            'session_id' => $data['session_id'],
            'company_id' => $data['company_id'],
            'branch_id' => $data['branch_id'],
            'class_id' => $data['class_id']
        ])->orderBy('created_at', 'desc')->get();

        $feeStructureValue = GeneralSettingsHelper::getSetting('fee_structure_divide');
        $feeCategory = FeeCategory::where('session_id', $data['session_id'])->where('company_id', $data['company_id'])
            ->where('branch_id', $data['branch_id'])
            ->first();

        if ($feeCategory != null) {
            $discountPercent = (int) $feeCategory->fa_percent;
        } else {
            $discountPercent = 0;
        }

        return view('fee.fee_structure.data', compact('feeHeads', 'feeStructureValue', 'discountPercent'));
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->feeStructureService->getdata();

    }
}
