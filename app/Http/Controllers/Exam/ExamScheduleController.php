<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Exam\ClassSubject;
use App\Models\Exam\Component;
use App\Models\Exam\ExamDetail;
use App\Models\Exam\ExamSchedule;
use App\Models\Exam\ExamTerm;
use App\Models\Exam\TestType;
use App\Services\ExamScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;


class ExamScheduleController extends Controller
{

    protected $ExamScheduleService;

    public function __construct(ExamScheduleService $examScheduleService)
    {
        $this->ExamScheduleService = $examScheduleService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        return view('exam.exam_schedule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('ExamSchedules-create')) {
            return abort(503);
        }
        $companies = Company::where('status', 1)->get();
        // $tests = ExamDetail::all();
        $tests =  DB::table('test_types')->where('status', 1)->select('id', 'name as test_name')->get();
        return view('exam.exam_schedule.create', compact('companies', 'tests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if (!Gate::allows('ExamSchedules-create')) {
            return abort(503);
        }
        $this->ExamScheduleService->store($request);
        return redirect()->route('exam.exam_schedules.index')
            ->with('success', 'Exam Schedule created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('ExamSchedules-list')) {
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
        if (!Gate::allows('ExamSchedules-edit')) {
            return abort(503);
        }
        $components = Component::where('status', 1)->get();
        $exam_schedule_detail = ExamSchedule::findOrFail($id);
        $branch_id = $exam_schedule_detail->branch_id;
        $class_id = $exam_schedule_detail->class_id;
        $companies = Company::where('status', 1)->get();
        $tests = ExamDetail::all();
        $classSubject = ClassSubject::with('Subject')
            ->where('branch_id', $branch_id)
            ->where('class_id', $class_id)
            ->get();
        return view('exam.exam_schedule.edit', compact('companies', 'tests', 'exam_schedule_detail', 'components', 'classSubject'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all(), $id);
        if (!Gate::allows('ExamSchedules-edit')) {
            return abort(503);
        }
        $rules = [
                'company_id' => ['required', 'integer', Rule::exists('company', 'id')],
                'branch_id'    => ['required', 'integer', Rule::exists('branches', 'id')],
                'exam_term_id' => ['required', 'integer', Rule::exists('exam_terms', 'id')],
                'test_type_id' => ['required', 'integer', Rule::exists('test_types', 'id')],
                'class_id'     => ['required', 'integer', Rule::exists('classes', 'id')],
                'course_id'   => ['required', 'integer', Rule::exists('courses', 'id')],
                'component_id' => ['required', 'integer', Rule::exists('components', 'id')],
                'marks'        => 'nullable|numeric|min:0',
                'grade'        => 'nullable|in:on',
                'pass'         => 'nullable|in:on',

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $examSchedule = ExamSchedule::findOrFail($id);
        $examSchedule->update([
            'company_id'   => $request->company_id,
            'branch_id'    => $request->branch_id,
            'exam_term_id' => $request->exam_term_id,
            'test_type_id' => $request->test_type_id,
            'class_id'     => $request->class_id,
            'course_id'   => $request->course_id,
            'component_id' => $request->component_id,
            'marks'        => $request->marks,
            'grade'        => $request->has('grade'),
            'pass'         => $request->has('pass'),
        ]);


        return redirect()->route('exam.exam_schedules.index')->with('success', 'Exam Schedule updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('ExamSchedules-delete')) {
            return abort(503);
        }
        $this->ExamScheduleService->destroy($id);
        return redirect()->route('exam.exam_schedules.index')->with('success', 'Exam Schedule Deleted successfully');
    }


    public function fetchExamTerm(Request $request)
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $examTerm = ExamTerm::where('branch_id', $request->branch_id)->get();
        return $examTerm;
    }

    public function classSubjectData(Request $request)
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = $request->all();
        $branch_id = $data['branch_id'];
        $class_id = $data['class_id'];

        $classSubject = ClassSubject::with('Subject')
            ->where('branch_id', $branch_id)
            ->where('class_id', $class_id)
            ->get();

        $components = Component::where('status', 1)->get();

        return view('exam.exam_schedule.data', compact('classSubject', 'components'));
    }

    public function fetch_class_subject(Request $request)
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = $request->all();
        $class_id = $data['class_id'];
        $classSubjects = ClassSubject::with('Subject')
            ->where('class_id', $class_id)
            ->get();

        $subjects = $classSubjects->map(function ($classSubject) {
            return $classSubject->Subject;
        });

        return response()->json($subjects);
    }

    public function fetch_class_subjects(Request $request)
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = $request->all();

        if (isset($data['id'])) {
            $class_id = $data['id'];
        } else {
            $class_id = $data['class_id'];
        }


        $classSubjects = Course::where('class_id', $class_id)
            ->get();

        return response()->json($classSubjects);
    }


    public function component_data(Request $request)
    {

        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = $request->all();
        $types = TestType::where('status', 1)->get();

        return view('exam.components.data', compact('types'));
    }

    public function getData()
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = ExamSchedule::with([
            'company',
            'branch',
            'testType',
            'examTerm',
            'class',
            'subject',
            'component'
        ])->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('test_type', function ($row) {
                return is_object($row->testType) ? ($row->testType->test_name ?? 'N/A') : 'Invalid Data';
            })
            ->addColumn('company', fn($row) => $row->company->name ?? 'N/A')
            ->addColumn('branch', fn($row) => $row->branch->name ?? 'N/A')
            ->addColumn('class', fn($row) => $row->class->name ?? 'N/A')
            ->addColumn('subject', fn($row) => $row->subject->name ?? 'N/A')
            ->addColumn('component', fn($row) => $row->component->name ?? 'N/A')
            ->addColumn('marks', fn($row) => $row->marks ?? '0')
            ->addColumn('grade', fn($row) => $row->grade ? 'Yes' : 'No')
            ->addColumn('pass', fn($row) => $row->pass ? 'Yes' : 'No')
            ->addColumn('exam_term', fn($row) => $row->examTerm->term_desc ?? 'N/A')
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-M-Y h:i A');
            })
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
               if (Gate::allows('ExamSchedules-edit')) {
                $btn .= '<a href="' . route("exam.exam_schedules.edit", $row->id) . '" class="btn btn-primary btn-sm" style="margin-right: 4px;">Edit</a>';
          
              }

                if(Gate::allows('ExamSchedules-delete')){
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure?\');" action="' . route("exam.exam_schedules.destroy", $row->id) . '">';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                $btn .= '</form>';

                }
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDataOnEdit($exam_schedule_id)
    {
        if (!Gate::allows('ExamSchedules-list')) {
            return abort(503);
        }
        $data = ExamSchedule::findOrFail($exam_schedule_id);
        return response()->json(['data' => $data]);
    }
}
