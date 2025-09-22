<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inventry;
use App\Models\MealBatch;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use App\Models\Account\Ledger;
use App\Models\MealBatchDetail;
use App\Services\LedgerService;
use App\Models\Academic\Section;
use App\Models\Admin\Department;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\StudentAttendance;

class MealBatchController extends Controller
{
    protected $ledgerService;
    public function __construct(LedgerService $ledgerService) {
        $this->ledgerService = $ledgerService;
    }
    
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $branches = Branch::where('status', 1)->with('classes')->get();
        $section = Section::with('academicClass')->get();

        $products = Inventry::whereIn('type', ['P', 'F'])
            ->where('expiry_date', '>', Carbon::today())
            ->get();

        $student_attendance = StudentAttendance::with([
            'AttendanceData' => function ($query) {
                $query->where('attendance', 'P');
            },
            'AttendanceData.student'
        ])->where('attendance_date', Carbon::today())->get();

        $batch_types = config('constants.batch_type');

        return view('admin.inventory_management.school_lunch.index', compact('branches', 'section', 'products', 'student_attendance', 'batch_types'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
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

        // DB::beginTransaction();
        // try {
            $product = Inventry::find($request->finished_goods);

            $existingBatch = MealBatch::where('branch_id', $request->branch)
                ->where('parent_id', $request->class)
                ->where('date', $request->date)
                ->where('batch_type', $request->batch_type)
                ->exists();

            if ($existingBatch) {
                throw new \Exception("Already assigned");
            }

            $assigned_count = collect($request->assigned)->filter(function ($value) {
                return $value == 1;
            })->count();

            if ($product->quantity < $assigned_count) {
                throw new \Exception("Not enough products in inventory");
            }

            $product->decrement('quantity', $assigned_count);
            $product->save();


          foreach ($request->student_section_id as $index => $section_id) {

                $mealBatch = MealBatch::create([
                "creator_id" => Auth::user()->id,
                "branch_id" => $request->branch,
                "parent_id" => $request->class,
                "parent_type" => AcademicClass::class,
                "section_id" => $section_id,
                "date" => $request->date,
                "product_id" => $request->finished_goods,
                "batch_type" => $request->batch_type,
            ]);
            }



            foreach ($request->student_id as $index => $student_id) {

                MealBatchDetail::create([
                    "batch_id" => $mealBatch->id,
                    "parent_id" => $student_id,
                    "parent_type" => Students::class,
                    "product_id" => $request->finished_goods,
                    "assigned" => $request->assigned[$index],
                ]);

                // $studentName = Ledger::where('ledgers.id', $request->ledger_id)
                //     ->join('students', 'students.id', '=', 'ledgers.parent_type_id')
                //     ->join('classes', 'classes.id', '=', 'students.class_id')
                //     ->join('sections', 'sections.id', '=', 'students.section_id')
                //     ->select(
                //         'ledgers.id',
                //         'ledgers.parent_type_id',
                //         'students.id as student_id',
                //         'students.first_name',
                //         'students.last_name',
                //         'classes.name as class_name',
                //         'sections.name as section_name'
                //     )
                //     ->first();

                // $name = "$studentName->first_name $studentName->last_name[$studentName->class_name - $studentName->section_name]";

                // $sudentLedger = Ledger::where('id', $request->ledger_id)->first();

                // $data = [
                //     "amount" => $request->total_price,
                //     "narration" => "Student Canteen Transaction $name",
                //     "branch_id" => $sudentLedger->branch_id,
                //     "entry_type_id" => 8,
                // ];
                // $entry = $this->ledgerService->createEntry($data);

                // $dataitem = [
                //     "entry_type_id" => 8,
                //     "entry_id" => $entry->id,
                //     "ledger_id" => $sudentLedger->id,
                //     "amount" => $request->total_price,
                //     "balanceType" => 'c',
                //     "narration" => $data['narration'],
                // ];

                // $this->ledgerService->createEntryItems($dataitem);

                // $dataitem = [
                //     "entry_type_id" => 8,
                //     "entry_id" => $entry->id,
                //     "ledger_id" => 1,//cash ledger is 1 
                //     "amount" => $request->total_price,
                //     "balanceType" => 'd',
                //     "narration" => $data['narration'],
                // ];

                // $this->ledgerService->createEntryItems($dataitem);

            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Lunch assigned successfully',
            ]);

        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

    public function view()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.inventory_management.school_lunch.view');
    }
    public function getAssigned()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $food_batches = MealBatch::with(['user', 'branch', 'class', 'product', 'section', 'mealBatchDetails'])
            ->where('parent_type', AcademicClass::class)
            ->get();
        return response()->json(["success" => true, 'data' => $food_batches]);
    }

    public function get_assigned_student($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $student_batch_products = MealBatchDetail::where('batch_id', $id)->with(['student', 'product'])->get();

        return view('admin.inventory_management.school_lunch.student_list', compact('student_batch_products'));
    }

    //for employees
    public function emp_index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $branches = Branch::where('status', 1)->with('department')->get();
        $batch_types = config('constants.batch_type');
        $department = Department::with('employee')->get();

        return view('admin.inventory_management.staff_lunch.index', compact('branches', 'batch_types', 'department'));
    }

    public function emp_store(Request $request)
    {
        if (!Gate::allows('students')) {
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

            $existingBatch = MealBatch::where('branch_id', $request->branch)
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

            $assignedCount = collect($request->assigned)->filter(function ($value) {
                return $value == 1;
            })->count();

            if ($product->quantity < $assignedCount) {
                return response()->json(['error' => 'Not enough products in inventory'], 400);
            }

            $product->quantity -= $assignedCount;
            $product->save();

            $food_batches = MealBatch::create([
                "creator_id" => Auth::user()->id,
                "branch_id" => $request->branch,
                "parent_id" => $request->department,
                "parent_type" => Department::class,
                "date" => $request->date,
                "product_id" => $request->finished_goods,
                "batch_type" => $request->batch_type,
            ]);

            foreach ($request->employee_id as $index => $employee_id) {

                MealBatchDetail::create([
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.inventory_management.staff_lunch.view');
    }
    public function empGetAssigned()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $food_batches = MealBatch::with(['user', 'branch', 'department', 'product', 'mealBatchDetails'])
            ->where('parent_type', Department::class)->get();

        return response()->json(["success" => true, 'data' => $food_batches]);
    }

    public function get_assigned_employee($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $employee_batch_products = MealBatchDetail::where('batch_id', $id)->with(['employee', 'product'])->get();
        return view('admin.inventory_management.staff_lunch.employee_list', compact('employee_batch_products'));
    }

    public function get_quantityProducts()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $products = Inventry::whereIn('type', ['P', 'F'])
            ->where('expiry_date', '>', Carbon::today())
            ->select('id', 'name', 'quantity')->get();

        return response()->json(["success" => true, 'products' => $products]);
    }
}
