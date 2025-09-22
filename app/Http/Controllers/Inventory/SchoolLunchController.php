<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use App\Models\Inventry;
use App\Models\FoodBatch;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use App\Models\Academic\Section;
use App\Models\Admin\Department;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Models\StudentBatchProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\StudentAttendance;
use App\Models\Academic\StudentAttendanceData;

class SchoolLunchController extends Controller
{
    //for students
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branches = Branch::where('status', 1)->with('classes')->get();
        $section = Section::with('academicClass')->get();

        $student_attendance = StudentAttendance::with([
            'AttendanceData' => function ($query) {
                $query->where('attendance', 'P');
            },
            'AttendanceData.student'
        ])->where('attendance_date', Carbon::today())->get();

        $batch_types = config('constants.batch_type');

        return view('admin.inventory_management.school_lunch.index', compact('branches', 'section', 'student_attendance', 'batch_types'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            "student_id" => 'required|array',
            "student_lunch_id" => 'required',
            "assigned" => 'required|array',
            "branch" => 'required',
            "class" => 'required',
            "date" => 'required',
            "finished_goods" => 'required',
            "batch_type" => 'required',
            "student_section_id" => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $product = Inventry::find($request->finished_goods);

            $existingBatch = FoodBatch::where('branch_id', $request->branch)
                ->where('parent_id', $request->class)
                ->where('date', $request->date)
                ->where('batch_type', $request->batch_type)
                ->exists();

            if ($existingBatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already assigned',
                ], 400);
            }

            if ($product->quantity < $request->student_count) {
                return response()->json(['error' => 'Not enough products in inventory'], 400);
            }

            $product->quantity -= $request->student_count;
            $product->save();


            $food_batches = [];

            foreach ($request->student_section_id as $sectionId) {
                $foodBatch = FoodBatch::create([
                    "creator_id" => Auth::user()->id,
                    "branch_id" => $request->branch,
                    "parent_id" => $request->class,
                    "parent_type" => AcademicClass::class,
                    "section_id" => $sectionId,
                    "date" => $request->date,
                    "product_id" => $request->finished_goods,
                    "batch_type" => $request->batch_type,
                ]);

                $food_batches[$sectionId] = $foodBatch->id;
            }

            foreach ($request->student_id as $index => $student_id) {

                $sectionId = $request->student_section_id[$index] ?? null;

                if (!$sectionId || !isset($food_batches[$sectionId])) {
                    throw new \Exception("Missing section ID: $sectionId");
                }

                StudentBatchProduct::create([
                    "batch_id" => $food_batches[$sectionId],
                    "parent_id" => $student_id,
                    "parent_type" => Students::class,
                    "product_id" => $request->finished_goods,
                    "assigned" => $request->assigned[$index],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Lunch assigned successfully',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function view()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.school_lunch.view');
    }
    public function getAssigned()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $food_batches = FoodBatch::with(['user', 'branch', 'class', 'section', 'product', 'studentBatchProducts'])
            ->where('parent_type', AcademicClass::class)
            ->get();
        return response()->json(["success" => true, 'data' => $food_batches]);
    }

    public function get_assigned_student($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $student_batch_products = StudentBatchProduct::where('batch_id', $id)->with(['student', 'product'])->get();

        return view('admin.inventory_management.school_lunch.student_list', compact('student_batch_products'));
    }

    //for employees
    public function emp_index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branches = Branch::where('status', 1)->with('department')->get();

        $batch_types = config('constants.batch_type');
        $department = Department::with('employee:id,name,department_id')->select('id', 'name', 'branch_id')->get();
        return view('admin.inventory_management.staff_lunch.index', compact('branches', 'batch_types', 'department'));
    }

    public function emp_store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            "employee_id" => 'required|array',
            "assigned" => 'required|array',
            "finished_goods" => 'required',
            "branch" => 'required',
            "department" => 'required',
            "date" => 'required',
            "finished_goods" => 'required',
            "batch_type" => 'required',
        ]);

        DB::beginTransaction();
        try {
            $product = Inventry::find($request->finished_goods);

            $existingBatch = FoodBatch::where('branch_id', $request->branch)
                ->where('parent_id', $request->department)
                ->where('date', $request->date)
                ->where('batch_type', $request->batch_type)
                ->first();

            if ($existingBatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lunch is already assigned for this batch'
                ], 400);
            }

            if ($product->quantity < $request->employee_count) {
                return response()->json(['error' => 'Not enough products in inventory'], 400);
            }

            $product->quantity -= $request->employee_count;
            $product->save();

            $food_batches = FoodBatch::create([
                "creator_id" => Auth::user()->id,
                "branch_id" => $request->branch,
                "parent_id" => $request->department,
                "parent_type" => Department::class,
                "date" => $request->date,
                "product_id" => $request->finished_goods,
                "batch_type" => $request->batch_type,
            ]);

            foreach ($request->employee_id as $index => $employee_id) {

                StudentBatchProduct::create([
                    "batch_id" => $food_batches->id,
                    "parent_id" => $employee_id,
                    "parent_type" => Employees::class,
                    "product_id" => $request->finished_goods,
                    "assigned" => $request->assigned[$index],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'product' => $product,
                'message' => 'Lunch assigned successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function emp_view()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.staff_lunch.view');
    }
    public function empGetAssigned()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $food_batches = FoodBatch::with(['user', 'branch', 'department', 'product', 'studentBatchProducts'])
            ->where('parent_type', Department::class)->get();

        return response()->json(["success" => true, 'data' => $food_batches]);
    }

    public function get_assigned_employee($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employee_batch_products = StudentBatchProduct::where('batch_id', $id)->with(['employee', 'product'])->get();
        return view('admin.inventory_management.staff_lunch.employee_list', compact('employee_batch_products'));
    }

    public function get_quantityProducts()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $products = Inventry::whereIn('type', ['P', 'F'])
            ->where('expiry_date', '>', Carbon::today())
            ->select('id', 'name', 'quantity')->get();

        return response()->json(["success" => true, 'products' => $products]);
    }

    // public function set_quantityProducts(Request $request)
    //     {
    //         dd($request->all());
    //         $product = Inventry::find($request->finished_goods);

    //         if ($product->quantity < $request->employee_count) {
    //             return response()->json(['error' => 'Not enough products in inventory'], 400);
    //         }

    //         $product->quantity -= $request->employee_count;
    //         $product->save();
    //     }

}

