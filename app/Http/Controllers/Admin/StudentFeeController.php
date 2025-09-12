<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Fee\StudentFee;
use App\Models\Admin\FeeFactor;
use App\Models\Student\Students;
use App\Models\Admin\FeeCategory;
use App\Models\Admin\FeeStructure;
use Illuminate\Support\Facades\DB;
use App\Services\StudentFeeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StudentFeeController extends Controller
{
    protected $studentFeeService;
    public function __construct(StudentFeeService $studentFeeService)
    {
        $this->studentFeeService = $studentFeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('fee.student_fee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();

        return view('fee.student_fee.create', compact('companies', 'sessions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        try {
          DB::beginTransaction();
          $request->validate([
                'company_id' => 'required|integer|exists:company,id',
                'session_id' => 'required|integer|exists:sessions,id',
                'branch_id' => 'required|integer|exists:branches,id',
                'class_id' => 'required|integer|exists:classes,id',
                'student_checkbox' => 'required|array|min:1',
                'student_checkbox.*' => 'string',
                'total_amount_of_discount.*' => 'nullable|numeric',
                'total_amount_of_month.*' => 'nullable|numeric',
            ]);
            $this->studentFeeService->store($request);
            DB::commit();
            return redirect()->route('admin.student-regular-fee.index')->with('success', 'Fee Structure created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
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
    public function edit($id): View
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $studentFee = StudentFee::with('student', 'student_fee_data.feeHead')->find($id);

        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();

        return view('fee.student_fee.edit', compact('companies', 'sessions', 'studentFee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->studentFeeService->update($request, $id);

        return redirect()->route('admin.student-regular-fee.index')->with('success', 'Fee Structure updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {

            $studentFee = StudentFee::find($id);
            $this->studentFeeService->destroy($studentFee);

            return redirect()->route('admin.student-regular-fee.index')->with('success', 'Fee Structure deleted successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function studentRegularFee(Request $request): View
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();
        $session_id = $data['session_id'];
        $company_id = $data['company_id'];
        $branch_id = $data['branch_id'];
        $class_id = $data['class_id'];

        $feeStructure = FeeStructure::with('feeStructureValue.feeHead')->where('session_id', $session_id)->where('company_id', $company_id)
            ->where('branch_id', $branch_id)->where('class_id', $class_id)
            ->first();
        $students = Students::where('branch_id', $branch_id)->where('class_id', $class_id)
            ->get();

        $feeFactors = UserHelper::feeFactor();
        //dd($students);
        return view('fee.student_fee.data', compact('feeStructure', 'students', 'feeFactors'));
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->studentFeeService->getdata();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $StudentFee = StudentFee::find($id);
            if ($StudentFee) {
                $StudentFee->delete();
                $StudentFee->student_fee_data()->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }


}
