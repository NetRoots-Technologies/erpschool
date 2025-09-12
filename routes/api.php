<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\SchoolClass;
use App\Http\Controllers\Student\StudentController;
use App\Models\Student\AcademicSession;
use App\Models\Academic\SchoolType;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\ActiveSession;
use App\Models\Admin\Department;
use App\Models\HR\Designation;
use App\Models\HRM\Employees;
use App\Models\HR\WorkShift;
use App\Http\Controllers\Admin\DepartmentController;
use App\Models\Category;
use App\Models\Admin\CourseType;
use App\Models\Admin\Course;
use App\Models\Academic\Section;
use App\Models\Student\Students;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/companies', function () {
        return response()->json(Company::select('id', 'name')->get());
    });

    Route::get('/get-branches-by-company/{companyId}', function ($companyId) {
        $branches = Branch::where('company_id', $companyId)->select('id', 'name')->get();
        return $branches->isEmpty()
            ? response()->json(['message' => 'No branches found'], 404)
            : response()->json($branches);
    });

    Route::get('/academic-sessions', function () {
        return response()->json(AcademicSession::select('id', 'name')->get());
    });

    Route::get('/school-types-by-branch/{branch_id}', function ($branch_id) {
        $branch = Branch::with('schoolBranch')->find($branch_id);

        if (!$branch || $branch->schoolBranch->isEmpty()) {
            return response()->json(['message' => 'No school types found'], 404);
        }

        $schoolTypes = [];
        foreach ($branch->schoolBranch as $schoolBranch) {
            $schoolType = SchoolType::find($schoolBranch->school_type_id);
            if ($schoolType) {
                $schoolTypes[] = ['id' => $schoolType->id, 'name' => $schoolType->name];
            }
        }
        return response()->json($schoolTypes);
    });

        // For Class
    Route::get('/get-academic-classes-by-branch/{branchId}', function ($branchId) {
        $classes = AcademicClass::where('branch_id', $branchId)->select('id', 'name')->get();
        return $classes->isEmpty()
            ? response()->json(['message' => 'No academic classes found'], 404)
            : response()->json($classes);
    });

         // For Active Session
    Route::get('/get-active-session-by-class/{classId}', function ($classId) {
        $session = ActiveSession::with('academicSession:id,name')->where('class_id', $classId)->first();

        return $session && $session->academicSession
            ? response()->json(['id' => $session->academicSession->id, 'name' => $session->academicSession->name])
            : response()->json(['message' => 'No active academic session found'], 404);
    });

         // for get   the all school
    Route::get('/school-types', function () {
        return response()->json(SchoolType::all());
    });

            // for get   the all department
    Route::get('/hr/fetch-departments', function () {
        return response()->json(Department::where('status', 1)->get());
    });

            // for get designation  against department
    Route::get('/hr/fetch-designation', function (Request $request) {
        $departmentId = $request->query('department_id');

        if (!$departmentId) {
            return response()->json(['message' => 'department_id is required'], 400);
        }

        $designations = Designation::where('department_id', $departmentId)
            ->where('status', 1)
            ->get();

        return response()->json($designations);
    });

         //  emplye code show against branch
    Route::get('/generate-emp-code/{branchId}', function ($branchId) {
        $branch = Branch::find($branchId);

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

        $latestIdNumber = $latestEmp
            ? intval(substr($latestEmp->emp_id, strlen($branchCode) + 1)) + 1
            : 1;

        $newEmpId = $branchCode . '-' . str_pad($latestIdNumber, 2, '0', STR_PAD_LEFT);
        return response()->json(['empId' => $newEmpId]);
    });

    // get all  value work_shifts
    Route::get('/work-shifts', function () {
        $workShifts = WorkShift::with('workdays')->get();
        return response()->json($workShifts);
    });


    // get all value of employees
    Route::get('/employees', function () {
        $employees = Employees::where('status', 1)->select('id', 'name')->get();
        return response()->json($employees);
    });

    // for category  all get
    Route::get('/categories', function () {
        $categories = Category::select('id', 'name')->get(); // Adjust fields as needed
        return response()->json($categories);
    });

 //   for subject type get all
    Route::get('/subject-types', function () {
        $types = CourseType::where('status', 1)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    });
    Route::get('/get-latest-branches',function(){
        $branches = Branch::select('id','name')->where('status', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $branches
        ]);
    });

    Route::get('fetch-academic-studentId/{branch_id}', function ($branch_id) {
        // Find the branch by ID
        $branch = Branch::find($branch_id);

        if (!$branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found'
            ], 404);
        }

        $branchCode = $branch->branch_code;

        if (!$branchCode) {
            return response()->json([
                'success' => false,
                'message' => 'Branch code not found'
            ], 404);
        }

        // Find the latest student ID that matches branch code
        $latestStudent = Students::where('student_id', 'like', $branchCode . '-%')
            ->orderBy('student_id', 'desc')
            ->first();

        if ($latestStudent) {
            $latestIdNumber = intval(substr($latestStudent->student_id, strlen($branchCode) + 1)) + 1;
        } else {
            $latestIdNumber = 1;
        }

        $newStudentId = $branchCode . '-' . str_pad($latestIdNumber, 2, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'new_student_id' => $newStudentId
        ]);
    })->name('fetch_studentId');



    Route::get('fetch-academic-sections/{class_id}', function ($class_id) {
        try {
            $sections = Section::where('class_id', $class_id)->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections: ' . $e->getMessage()
            ], 500);
        }
    })->name('fetchSections');

    //Just for Fetch Employee ID on Employee Creation Form 

Route::get('fetch-academic-empRoll/{branch_id}', [StudentController::class, 'EmpCode'])->name('fetchEmpNo');