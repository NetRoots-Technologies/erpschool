<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Helpers\UserHelper;
use App\Models\Admin\Biling;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Admin\FeeFactor;
use App\Models\Admin\BilingData;
use App\Http\Controllers\Controller;
use App\Services\BillGenerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class BillGenerationController extends Controller
{
    protected $BillGenerationService;

    public function __construct(BillGenerationService $billGenerationService)
    {
        $this->BillGenerationService = $billGenerationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): view
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('fee.bill_genration.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): view
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();
        $feeFactors = UserHelper::feeFactor();

        return view('fee.bill_genration.create', compact('sessions', 'companies', 'feeFactors'));
    }

    /**
     * Store a newly created resource in storage.
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

            $data = $request->all();
            $existingBilling = Biling::where('charge_from', '<=', $data['charge_to'])
                ->where('charge_to', '>=', $data['charge_from'])
                ->exists();

            if ($existingBilling) {
                throw new Exception('A billing record already exists between the specified dates.');
            }

            $this->BillGenerationService->store($request);

            return redirect()->back()->with('success', 'Bill Generated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
    public function edit($id): view
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bill = Biling::with('branch', 'AcademicClass', 'student')->find($id);
        return view('fee.bill_genration.edit', compact('bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->BillGenerationService->update($request, $id);

        return redirect()->route('admin.bill-generation.index')->with('success', 'Bill Generated updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->BillGenerationService->destroy($id);

        return redirect()->route('admin.bill-generation.index')->with('success', 'Bill Generated Deleted');
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->BillGenerationService->getdata();
    }


    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $billing = Biling::with('billingData')->find($id);
            if ($billing) {
                $billing->billingData()->delete();

                $billing->delete();

            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $bill = $this->BillGenerationService->changeStatus($request);

    }
}
