<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Quotta;
use App\Models\HR\WorkShift;
use App\Models\HRM\Employees;
use App\Models\ApprovalAuthority;
use App\Models\HR\LeaveRequest;
use App\Models\ApprovalRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->LeaveRequestService = $leaveRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Leave Request List')) {
            return abort(503);
        }
        return view('hr.leave_requests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Leave Request create')) {
            return abort(503);
        }
        $employees = Employees::where('job_seeking', '!=', 'visitingLecturer')->get();
        $leavetypes = Quotta::all();
        $workshifts = WorkShift::all();
        // die;

        return view('hr.leave_requests.create', compact('employees', 'leavetypes', 'workshifts'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    // //   dd($request->leave_type_id, $request->employee_id);
    //     if (!Gate::allows('Leave Request create')) {
    //         return abort(503);
    //     }
    //     try {
    //         $this->LeaveRequestService->store($request);
    //         return redirect()->route('hr.leave_requests.index')->with('success', 'Leave Request created successfully');

    //     } catch (ValidationException $e) {
    //         return redirect()->back()->withErrors($e->errors())->withInput();
    //     }
    // }

    
    public function store(Request $request)
    {
        DB::transaction(function() use ($request) {

            // 1️⃣ Create LeaveRequest
            $leaveRequest = LeaveRequest::create([
                'hrm_employee_id' => $request->employee_id,
                'hr_quota_setting_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'days' => $request->days,
                'comments' => $request->comments,
                'status' => 0, // pending
            ]);

            // 2️⃣ Create ApprovalRequests for authorities
            $authorities = ApprovalAuthority::all(); // ya role/department specific filter
            foreach($authorities as $authority) {
                ApprovalRequest::create([
                    'leave_request_id' => $leaveRequest->id,
                    'approval_authority_id' => $authority->id,
                    'status' => 0, // pending
                    'remarks' => null
                ]);
            }

        });

        return redirect()->back()->with('success', 'Leave request created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Leave Request List')) {
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
        if (!Gate::allows('Leave Request edit')) {
            return abort(503);
        }
        $employees = Employees::where('job_seeking', '!=', 'visitingLecturer')->get();

        $leavetypes = Quotta::all();
        $workshifts = WorkShift::all();
        $leaveRequest = $this->LeaveRequestService->edit($id);

        return view('hr.leave_requests.edit', compact('employees', 'leavetypes', 'workshifts', 'leaveRequest'));

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
        if (!Gate::allows('Leave Request edit')) {
            return abort(503);
        }
        $leaveRequest = $this->LeaveRequestService->update($request, $id);

        return redirect()->route('hr.leave_requests.index')
            ->with('success', 'Leave Request Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Leave Request delete')) {
            return abort(503);
        }
        $this->LeaveRequestService->destroy($id);

        return redirect()->route('hr.leave_requests.index')
            ->with('success', 'Leave Request deleted successfully');
    }


    public function getData()
    {
        if (!Gate::allows('Leave Request List')) {
            return abort(503);
        }
        $LeaveRequest = $this->LeaveRequestService->getdata();
        return $LeaveRequest;
    }

    public function employee_leave(Request $request)
    {
        if (!Gate::allows('Leave Request List')) {
            return abort(503);
        }
        $quotaSettings = $this->LeaveRequestService->employee_leave($request);
        return response()->json($quotaSettings);
    }



}

