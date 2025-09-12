<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GeneralSettingsHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\FeeSection;
use App\Services\FeeSectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FeeSectionController extends Controller
{
    public function __construct(FeeSectionService $feeSectionService)
    {
        $this->FeeSectionService = $feeSectionService;
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
        return view('fee.fee_sections.index');
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
        $feeSections = GeneralSettingsHelper::getSetting('print_section');
        $branches = Branch::where('status', 1)->get();
        return view('fee.fee_sections.create', compact('feeSections', 'branches'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->FeeSectionService->store($request);

            return redirect()->route('admin.fee-sections.index')->with('success', 'Fee Section created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Fee Section');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeSection = FeeSection::find($id);
        $feeSections = GeneralSettingsHelper::getSetting('print_section');
        $branches = Branch::where('status', 1)->get();
        return view('fee.fee_sections.edit', compact('feeSections', 'branches', 'feeSection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->FeeSectionService->update($request, $id);

            return redirect()->route('admin.fee-sections.index')->with('success', 'Fee Section created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while update the Fee Section');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->FeeSectionService->destroy($id);

            return redirect()->route('admin.fee-sections.index')->with('success', 'Fee Section deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the Fee Section');
        }

    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->FeeSectionService->getdata();
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $employee = $this->FeeSectionService->changeStatus($request);

    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');

        foreach ($ids as $id) {
            $feeSection = FeeSection::find($id);
            if ($feeSection) {
                $feeSection->delete();
            }
        }
        return response()->json(['message', 'Bulk action completed successfully']);
    }
}
