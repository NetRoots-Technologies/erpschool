<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Models\Admin\Session;
use App\Models\Admin\StudentDataBank;
use App\Models\Admin\StudentDataBankCourse;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
// use App\Models\Fee\StudentFee; // Removed - model no longer exists
use App\Services\DataBank;
use Illuminate\Http\Request;
use App\Services\StudentServices;
use DataTables;
use Illuminate\Support\Facades\Gate;

class DataBankController extends Controller
{
    /**
     * @var DataBank
     */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(DataBank $DataBank, StudentServices $StudentServices)
    {
        $this->DataBank = $DataBank;
        $this->StudentServices = $StudentServices;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $data = $this->DataBank->index1();

        $courses = Course::get();

        return view('admin.databank.index', compact('data', 'courses'));
    }

    public function view_data_bank_courses($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = StudentDataBankCourse::join('courses', 'courses.id', '=', 'student_data_bank_courses.course_id')
            ->where('student_data_bank_courses.student_data_bank_id', $id)->select('courses.name as course_name')->get();

        //  return $data;
        $html = "";
        $k = 0;
        foreach ($data as $key => $value) {
            $k++;
            $html = $html . "<div class='row'>";
            $html = $html . "<div class='col-md-12'>";
            $html = $html . "<p><b>$k -</b>  $value->course_name</p>";
            $html = $html . "</div>";
            $html = $html . "</div>";
        }
        return $html;

    }


    public function students_view_installemet(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_id = $id;

        return view('paidFee.get_specific_student_fee', compact('student_id'));

    }

    public function students_view_specific_installemet($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        
        // Updated to use new fee structure - FeeCollection instead of old StudentFee/PaidStudentFee
        $data = FeeCollection::where('fee_collections.student_id', $id)
            ->join('students', 'students.id', '=', 'fee_collections.student_id')
            ->join('classes', 'fee_collections.class_id', '=', 'classes.id')
            ->join('acadmeic_sessions', 'acadmeic_sessions.id', '=', 'fee_collections.academic_session_id')
            ->select(
                'fee_collections.id as id', 
                'students.name as student_name', 
                'fee_collections.total_amount as student_fee', 
                'fee_collections.paid_amount', 
                'fee_collections.balance_amount', 
                'fee_collections.discount_amount', 
                'acadmeic_sessions.title as session_title', 
                'classes.name as class_name', 
                'fee_collections.due_date', 
                'fee_collections.status as paid_status', 
                'fee_collections.id as paid_fee_id'
            )->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                if ($row->paid_status == 'paid') {
                    $btn = '<p>Paid</p>';
                    return $btn;
                } else {
                    $btn = '<a data-id="' . $row->paid_fee_id . '" data-url="' . route("admin.fee_paid_date", $row->paid_fee_id) . '" class="btn btn-primary  btn-sm paid_installement">Pay Fee</a>';
                    return $btn;
                }

                return $btn;
            })
            ->addColumn('paid_date', function ($row) {
                if (isset($row->paid_date)) {
                    $btn = $row->paid_date;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->addColumn('source', function ($row) {
                if (isset($row->source)) {
                    $btn = $row->source;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->rawColumns(['source', 'action', 'paid_date',])
            ->make(true);

    }

    public function walk_in_student(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'mobile_no' => 'required',
        ]);

        $this->DataBank->walk_in_student($request);
        return redirect()->route('admin.walk_in_student.get')->with('success', 'Walk In Student created successfully');

    }

    public function walk_in_student_get()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.databank.index');
    }

    public function walk_in_student_view()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $courses = Course::get();
        return view('admin.databank.walk_in_student', compact('courses'));

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $delete = $this->DataBank->destroy($id);
        return ' walk in student deleted';
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


    public function student_databank_create($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->StudentServices->create();
        $databank = $this->DataBank->databank_create_student($id);

        if (!$databank) {
            return redirect()->route('admin.student_databank.index');
            //            return  redirect()->route('admin.student_databank.index')->with('message', 'Student already created');;
        }

        $session = Session::where('status', 1)->first();
        return view('student.databank_create_student', compact('databank', 'data', 'session'));
    }


    public function student_databank_remarks(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->DataBank->student_databank_remarks($request);
        return "Done";
    }

    public function student_databank_status(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->DataBank->student_databank_status($request);
        return "Done";
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $dataBank = $this->DataBank->getdata($request);
        return $dataBank;
    }

    public function bv_form_view(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.databank.business_valley');
    }

    public function bv_form_get_data(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $dataBank = $this->DataBank->bv_form_get_data($request);
        return $dataBank;
    }
    public function seminar_form_view(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.databank.seminar');
    }

    public function seminar_form_get_data(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $dataBank = $this->DataBank->seminar_form_get_data($request);
        return $dataBank;
    }

    public function onezcamp_form_view(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.databank.onezcamp');
    }

    public function onezcamp_form_get_data(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $dataBank = $this->DataBank->onezcamp_form_get_data($request);
        return $dataBank;
    }
}








