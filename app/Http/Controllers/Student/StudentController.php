<?php

namespace App\Http\Controllers\Student;


use Exception;
use App\Models\HR\Agent;
use App\Models\Admin\Branch;
use App\Models\Admin\Course;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Admin\Session;
use App\Models\Student\AcademicSession;
use App\Models\HRM\Employees;
use App\Services\LedgerService;
use App\Models\Student\Students;
use App\Services\StudentServices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Student\StudentSibling;
use Illuminate\Support\Facades\Config;
use App\Models\Fee\StudentFee;
use App\Exports\StudentSampleExport;
use App\Imports\StudentExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Config;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $StudentServices;
    protected $LedgerService;

    public function __construct(StudentServices $StudentServices, LedgerService $LedgerService)
    {
        $this->StudentServices = $StudentServices;
        $this->LedgerService = $LedgerService;
    }


    public function index()
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $courses = Course::all();
        $student_fee = StudentFee::all();
        $agents = Agent::get();
        $sessions = Session::get();
        return view('acadmeic.student.index', compact('courses', 'student_fee', 'agents', 'sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Gate::allows('students-create')) {
        //     return abort(503);
        // }

        $data = $this->StudentServices->create();
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();
        return view('acadmeic.student.create', compact('students', 'branches', 'data', 'companies'));

    }

    public function states(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->StudentServices->get_state($request->id);


    }

    public function cities(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->StudentServices->get_city($request->id);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!Gate::allows('students-create')) {
        //     return abort(503);
        // }

        $student = $this->StudentServices->store($request);

        $group_ids = Config('constants.FixedGroups.Student_Canteen_Receivables');
        $ledger_name = $student->student_id . ' - ' . $student->first_name . ' ' . $student->last_name;
        $branch_id = $student->branch_id;
        $model_name = Students::class;
        $model_type_id = $student->id;
        $this->LedgerService->createAutoLedgers([$group_ids], $ledger_name, $branch_id, $model_name, $model_type_id);

        return redirect()->route('academic.students.index')
            ->with('success', 'Student created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->StudentServices->edit($id);
        return view('student.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Students-edit')) {
            return abort(503);
        }
        $student = Students::with('student_siblings', 'student_schools', 'student_emergency_contacts', 'AcademicClass')->find($id);
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();
        $sessions = AcademicSession::where('status', 1)->get();
        $selectClass = StudentSibling::where('student_id', $student->id)->first();
        $selectedClass = $student->class_id;


        return view('acadmeic.student.edit', compact('branches', 'student', 'companies', 'students', 'sessions', 'selectedClass', 'id'));
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
        if (!Gate::allows('Students-edit')) {
            return abort(503);
        }

        $this->StudentServices->update($request, $id);


        return redirect()->route('academic.students.index')
            ->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function get_data_student(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $student = $this->StudentServices->getData($request);
        return $student;
    }

    public function destroy($id)
    {
        if (!Gate::allows('students-delete')) {
            return abort(503);
        }
        $student = $this->StudentServices->destroy($id);
        return redirect()->route('academic.students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Students::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }


    public function report()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('acadmeic.sibling_report.index');
    }


    public function getStudentSiblingData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->StudentServices->StudentSiblingData();
    }

    public function fetch_siblingClass(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $students = Students::with('AcademicClass')->where('id', $request->student_id)->first();
        return $students->AcademicClass;
    }

    public function fetch_siblingDob(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $student = Students::with('AcademicClass')->where('id', $request->student_id)->first();

        if ($student) {
            return response()->json([
                'student_dob' => $student->student_dob,
                'gender' => $student->gender
            ]);
        } else {
            return response()->json([
                'error' => 'Student not found'
            ], 404);
        }
    }

    public function fetchStudentData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        
        $branch = Branch::find($request->branch_id);
        if (!$branch) {
            return response()->json(['error' => 'Branch not found'], 404);
        }

        $branchCode = $branch->branch_code;

        if (!$branchCode) {
            return response()->json(['error' => 'Branch Code not found'], 404);
        }

        $latestStudent = Students::where('student_id', 'like', $branchCode . '-%')
            ->orderBy('student_id', 'desc')
            ->first();

        if ($latestStudent) {
            $latestIdNumber = intval(substr($latestStudent->student_id, strlen($branchCode) + 1)) + 1;
        } else {
            $latestIdNumber = 1;
        }

        $newStudentId = $branchCode . '-' . str_pad($latestIdNumber, 2, '0', STR_PAD_LEFT);

        return response()->json(['new_student_id' => $newStudentId]);
    }

    public function StudentRollNo(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->all();

        $latestStudent = Students::where('section_id', $data['section'])
            ->where('branch_id', $data['branch_id'])
            ->where('class_id', $data['class_id'])
            ->orderBy('roll_no', 'desc')
            ->first();

        if ($latestStudent) {
            $studentRollNo = $latestStudent->roll_no + 1;
        } else {
            $studentRollNo = 1;
        }

        return response()->json(['studentRollNo' => $studentRollNo]);
    }


    public function EmpCode(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branch = Branch::find($request->branch_id);
        if (!$branch) {
            return response()->json(['error' => 'Branch not found'], 404);
        }

        $branchCode = $branch->branch_code;

        if (!$branchCode) {
            return response()->json(['error' => 'Branch Code not found'], 404);
        }

        $latestEmp = Employees::where('emp_id', 'like', $branchCode . '-%')
            ->orderBy('emp_id', 'desc')
            ->first();

        if ($latestEmp) {
            $latestIdNumber = intval(substr($latestEmp->emp_id, strlen($branchCode) + 1)) + 1;
        } else {
            $latestIdNumber = 1;
        }

        $newEmpId = $branchCode . '-' . str_pad($latestIdNumber, 2, '0', STR_PAD_LEFT);
        return response()->json(['empId' => $newEmpId]);

    }

    public function fetchCnicStudentData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $getSameCnicStudents = Students::with('AcademicClass:id,name')->where('father_cnic', $request->cnic)->get();
        // dd($getSameCnicStudents);
        return $getSameCnicStudents;
    }

    public function existingStudentLedger()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $students = Students::whereNotNull('student_id')->select('id', 'student_id', 'branch_id', 'first_name', 'last_name')->get();

            $group_ids = Config('constants.FixedGroups.Student_Canteen_Receivables');

            foreach ($students as $student) {
                $ledger_name = $student->student_id . ' - ' . $student->first_name . ' ' . $student->last_name;
                $branch_id = $student->branch_id;
                $model_name = Students::class;
                $model_type_id = $student->id;
                $this->LedgerService->createAutoLedgers([$group_ids], $ledger_name, $branch_id, $model_name, $model_type_id);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }

    public function fetchStudents(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'section_id' => 'required|integer|exists:sections,id',
        ]);

        $students = Students::with(['company', 'branch', 'AcademicClass', 'section'])
            ->where('branch_id', $request->branch_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get();

        $response = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'full_name' => $student->first_name . ' ' . $student->last_name,
                'company_name' => optional($student->company)->name,
                'branch_name' => optional($student->branch)->name,
                'class_name' => optional($student->AcademicClass)->name,
                'section_name' => optional($student->section)->name,
            ];
        });


        return response()->json($response);
    }



    public function exportbulkfile()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new StudentSampleExport, 'student_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StudentExcelImport, $request->file('import_file'));
            return back()->with('success', 'Student records imported successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['import_file' => 'Import failed: ' . $e->getMessage()]);
        }
    }


}


