<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Models\Admin\Session;
use App\Models\Fee\PaidStudentFee;
use App\Models\Student\Students;
use Illuminate\Http\Request;
use App\Services\StudentFeeServices;
use Illuminate\Support\Facades\DB;
use App\Models\Fee\StudentFee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class StudentFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    protected $StudentFeeServices;
    public function __construct(StudentFeeServices $StudentFeeServices)
    {
        $this->StudentFeeServices = $StudentFeeServices;
    }


    public function student_fee_voucher(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = PaidStudentFee::with('student_fee', 'student_fee.course', 'student_fee.student', 'student_fee.session', 'student_fee.student.student_detail')->where('id', $id)->get();
        return view('studentFee.voucher1', compact('data'));
    }

    public function student_fee_paid(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->student_fee_paid($request, $id);
        return 'done';
    }

    public function get_data_student_fee_more_than_30k()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->StudentFeeServices->get_data_student_fee_more_than_30k();
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $courses = Course::all();
        $student_fee = StudentFee::all();
        return view('studentFee.index', compact('courses', 'student_fee'));
    }

    public function get_course_session(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value=''>Select option</option>";
        $Session = Session::where('course_id', $request->id)->get();

        foreach ($Session as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->title . "</option>";
        }
        return $html;

    }

    public function remaining_fee($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->student_paid_fee_detail($id);
        return view('studentFee.remaining_fee', compact('data'));
    }

    public function remaining_fee_post($id, Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->student_paid_fee($request, $id);
        return redirect()->route('admin.fee_paid_detail', $id);
    }

    public function student_paid_fee_detail($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->student_paid_fee_detail($id);
        return view('studentFee.studentfee_detail', compact('data', 'id'));
    }

    public function discount_on_instalment($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->student_paid_fee_detail($id);
        return view('studentFee.discount_on_instalment', compact('data', 'id'));
    }

    public function discount_on_instalment_post(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->discount_on_instalment_post($request, $id);
        return redirect()->route('admin.fee_paid_detail', $id);
    }

    public function make_defaulter(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->StudentFeeServices->make_defaulter_post($request, $id);
        return redirect()->route('admin.fee_paid_detail', $id);

    }

    public function make_defaulter_reactive(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->StudentFeeServices->make_defaulter_reactive($request, $id);
        return redirect()->route('admin.fee_paid_detail', $id);

    }

    public function fee_paid_detail_edit_post(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->fee_paid_detail_edit_post($request, $id);

        return redirect()->back();
    }

    public function get_data_student_paid_fee_detail($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->StudentFeeServices->get_data_student_paid_fee_detail($id);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get_fee(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->StudentFeeServices->get_fee($request->id);
    }

    public function get_sessions(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->StudentFeeServices->get_sessions($request->id);
    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->create();
        $session = Session::all();
        return view('studentFee.create', compact('data', 'session'));
    }

    public function create1($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->create();
        $session = Session::all();
        return view('studentFee.create', compact('data', 'session', 'id'));
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
        $validated = $request->validate([
            'data_bank_id' => 'required',
            'student_fee' => 'required',
            'course_id' => 'required',
            'course_fee' => 'required',
        ]);

        $this->StudentFeeServices->store($request);

        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Fee created successfully');
    }

    public function get_data_student_fee(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $studentFee = $this->StudentFeeServices->get_data_student_fee($request);
        return $studentFee;
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
    public function edit(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentFeeServices->edit($id);
        return view('studentFee.edit', compact('data'));
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
        $validated = $request->validate([
            'data_bank_id' => 'required',
            'student_fee' => 'required',
            'course_id' => 'required',
            'course_fee' => 'required',
            'installement_type' => 'required',
        ]);
        $this->StudentFeeServices->update($request, $id);

        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Fee updated successfully');
    }

    public function update_fee_paid_date(Request $request, $id)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        PaidStudentFee::where('id', $request->id)->update([
            'paid_date' => $request->paid_date,
            'paid_status' => 'paid',
            'source' => $request->source,
        ]);

        $installement_no = PaidStudentFee::where('id', $request->id)->value('installement_no');

        if ($installement_no == '1') {
            $student_id = PaidStudentFee::where('id', $request->id)->value('student_id');

            Students::where('id', $student_id)->update([
                'status' => 'active',
            ]);
        }

        return 'done';
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = $this->StudentFeeServices->destroy($id);
        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Fee deleted successfully');
    }

    public function fee_paid_detail_delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student = $this->StudentFeeServices->fee_paid_detail_delete($id);
        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Fee deleted successfully');
    }
    public function crone()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student = $this->StudentFeeServices->cronjob();
        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Fee deleted successfully');
    }
    public function assign_certificate($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student = $this->StudentFeeServices->assign_certificate($id);
        return redirect()->route('admin.student_fee.index')
            ->with('success', 'Student Certificate Assigned successfully');
    }
}
