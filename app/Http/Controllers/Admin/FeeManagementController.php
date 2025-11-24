<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\Fee\FeeFactor;
use App\Models\Fee\FeeBilling;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeDiscount;
use App\Services\LedgerService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Fee\FeeStructure;
use App\Models\Student\Students;
use App\Models\Fee\FeeAdjustment;
use App\Models\Fee\FeeAllocation;
use App\Models\Fee\FeeCollection;
use App\Imports\FeeCategoryImport;
use App\Imports\FeeDiscountImport;
use Illuminate\Support\Facades\DB;

use App\Imports\FeeStructureImport;
use Illuminate\Support\Facades\Log;
use App\Exports\StudentLedgerExport;
use App\Http\Controllers\Controller;
use App\Imports\FeeCollectionImport;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\ActiveSession;
use App\Models\Fee\FeeDiscountHistory;
use App\Models\Fee\FeeStructureDetail;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Student\AcademicSession;
use App\Models\Accounts\CustomerInvoice;
use App\Models\Fee\StudentFeeAssignment;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\StudentLedgerSingleSheetExport;


class FeeManagementController extends Controller
{
    protected $ledgerservice;
    public function __construct(LedgerService $ledgerservice)
    {
        $this->middleware('auth');
        $this->ledgerservice = $ledgerservice;
    }

    /**
     * Display the main fee management dashboard
     */
    public function index()
    {
        if (!Gate::allows('fee-dashboard')) {
            abort(403, 'Unauthorized access');
        }

        // Calculate total collected amount from all paid collections
        $totalCollected = FeeCollection::where('status', 'paid')->sum('paid_amount');

        // Calculate pending/outstanding amount from billing records
        $pendingBillings = FeeBilling::whereIn('status', ['generated', 'partial'])->get();
        $pendingAmount = 0;

        foreach ($pendingBillings as $billing) {
            $finalAmount = $billing->getFinalAmount();
            $paidAmount = $billing->paid_amount ?? 0;
            $outstanding = $finalAmount - $paidAmount;
            if ($outstanding > 0) {
                $pendingAmount += $outstanding;
            }
        }

        // Additional statistics
        $totalBillings = FeeBilling::count();
        $paidBillings = FeeBilling::where('status', 'paid')->count();
        $partialBillings = FeeBilling::where('status', 'partial')->count();
        $pendingBillings = FeeBilling::where('status', 'generated')->count();
        // $feeStructures = FeeStructure::all();


        $studentsWithHighFoodCharges = FeeStructure::select('student_id', DB::raw('SUM(fee_structure_details.amount) as total_food'))
            ->join('fee_structure_details', 'fee_structure_details.fee_structure_id', '=', 'fee_structures.id')
            ->join('fee_categories', 'fee_categories.id', '=', 'fee_structure_details.fee_category_id')
            ->where('fee_categories.name', 'Food Charges')
            ->where('fee_categories.is_active', 1)
            ->groupBy('student_id')
            ->having('total_food', '>', 1)
            ->get();

        // Count of such students
        $count = $studentsWithHighFoodCharges->count();

        $studentsWithHighFoodCharges = FeeStructure::select('student_id', DB::raw('SUM(fee_structure_details.amount) as total_food'))
            ->join('fee_structure_details', 'fee_structure_details.fee_structure_id', '=', 'fee_structures.id')
            ->join('fee_categories', 'fee_categories.id', '=', 'fee_structure_details.fee_category_id')
            ->where('fee_categories.name', 'Food Charges')
            ->where('fee_categories.is_active', 1)
            ->groupBy('student_id')
            ->having('total_food', '>', 1)
            ->get();
        $totalFoodCharges = $studentsWithHighFoodCharges->sum('total_food');

        $data = [
            'title' => 'Fee Management Dashboard',
            'total_categories' => FeeCategory::count(),
            'total_structures' => FeeStructure::count(),
            'total_collections' => $totalCollected,
            'pending_amount' => $pendingAmount,
            'total_billings' => $totalBillings,
            'paid_billings' => $paidBillings,
            'partial_billings' => $partialBillings,
            'pending_billings' => $pendingBillings,
            'food_charge_structures' => $count,
            // 👇 Food Charges Stats
            'food_charge_students' => $studentsWithHighFoodCharges->count(),
            'total_food_charges' => $totalFoodCharges,
            'recent_collections' => FeeCollection::with(['student.AcademicClass', 'academicSession'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.fee-management.index', compact('data'));
    }

    /**
     * Fee Categories Management
     */
    public function categories()
    {
        if (!Gate::allows('fee-Categories-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.categories.index');
    }

    public function getCategoriesData()
    {
        $categories = FeeCategory::with(['company', 'branch', 'createdBy'])
            ->select(['id', 'name', 'description', 'type', 'is_mandatory', 'affects_financials', 'is_active', 'created_at']);

        return DataTables::of($categories)
            ->addColumn('action', function ($category) {

                $editBtn = '';
                $deleteBtn = '';
                if (Gate::allows('fee-Categories-edit')) {
                    $editBtn .= '<a href="' . route('admin.fee-management.categories.edit', $category->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                }

                if (Gate::allows('fee-Categories-delete')) {
                    $deleteBtn .= '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' . $category->id . ')">Delete</button>';
                }
                return $editBtn . ' ' . $deleteBtn;
            })
            ->addColumn('status', function ($category) {
                return $category->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            })
            // ✅ Format created_at here
            ->editColumn('created_at', function ($category) {
                return $category->created_at
                    ? $category->created_at->format('d-m-Y H:i')
                    : '—'; // if null or invalid
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function createCategory()
    {
        if (!Gate::allows('fee-Categories-create')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.categories.create');
    }

    public function storeCategory(Request $request)
    {
        if (!Gate::allows('fee-Categories-create')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'type' => 'required|in:admission,monthly,annual,one_time,allocation',
            'is_mandatory' => 'boolean',
            'affects_financials' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $category = FeeCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_mandatory' => $request->has('is_mandatory'),
            'affects_financials' => $request->has('affects_financials'),
            'is_active' => $request->has('is_active'),
            'company_id' => auth()->user()->company_id ?? null,
            'branch_id' => auth()->user()->branch_id ?? null,
            'created_by' => auth()->id(),
        ]);

        // Check if request is AJAX (from modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fee category created successfully!',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.fee-management.categories')
            ->with('success', 'Fee category created successfully!');
    }

    public function editCategory($id)
    {
        if (!Gate::allows('fee-Categories-edit')) {
            abort(403, 'Unauthorized access');
        }

        $category = FeeCategory::findOrFail($id);
        return view('admin.fee-management.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        if (!Gate::allows('fee-Categories-edit')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'type' => 'required|in:admission,monthly,annual,one_time,allocation',
            'is_mandatory' => 'boolean',
            'affects_financials' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $category = FeeCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_mandatory' => $request->has('is_mandatory'),
            'affects_financials' => $request->has('affects_financials'),
            'is_active' => $request->has('is_active'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fee-management.categories')
            ->with('success', 'Fee category updated successfully!');
    }

    public function deleteCategory($id)
    {
        if (!Gate::allows('fee-Categories-delete')) {
            abort(403, 'Unauthorized access');
        }

        $category = FeeCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Fee category deleted successfully!']);
    }

    /**
     * Fee Structures Management
     */
    public function structures()
    {
        if (!Gate::allows('fee-structures-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.structures.index');
    }

    public function getStructuresData()
    {
        $structures = FeeStructure::with(['student', 'academicClass', 'academicSession', 'createdBy', 'feeStructureDetails'])
            ->select(['id', 'name', 'academic_class_id', 'academic_session_id', 'fee_factor_id', 'is_active', 'created_at', 'student_id']);

        return DataTables::of($structures)
            ->addColumn('action', function ($structure) {

                $editBtn = "";
                $deleteBtn = "";

                if (Gate::allows('fee-structures-edit')) {
                    $editBtn .= '<a href="' . route('admin.fee-management.structures.edit', $structure->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                }

                if (Gate::allows('fee-structures-delete')) {
                    $deleteBtn .= '<button class="btn btn-sm btn-danger" onclick="deleteStructure(' . $structure->id . ')">Delete</button>';
                }

                return $editBtn . ' ' . $deleteBtn;
            })
            ->addColumn('class_name', function ($structure) {
                return $structure->academicClass->name ?? 'N/A';
            })
            ->addColumn('session_name', function ($structure) {
                return $structure->academicSession->name ?? 'N/A';
            })
            ->addColumn('total_amount', function ($structure) {
                return $structure->feeStructureDetails->sum('amount') ?? 0;
            })
            ->addColumn('status', function ($structure) {
                return $structure->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('student_name', function ($structure) {
                return $structure->student->first_name . ' ' . $structure->student->last_name;
            })
            ->addColumn('student_id', function ($structure) {
                return $structure->student->student_id;
            })
            ->filterColumn('student_id', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->where('student_id', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->whereRaw("TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) LIKE ?", ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['action', 'status', 'student_name', 'student_id'])
            ->make(true);
    }

    public function createStructure()
    {
        if (!Gate::allows('fee-structures-create')) {
            abort(403, 'Unauthorized access');
        }

        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $factors = FeeFactor::where('is_active', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.structures.create', compact('classes', 'sessions', 'factors', 'categories'));
    }


    // Student Get by Class
    public function getClassByStudent($class_id)
    {

        $students = Students::query()
            ->where('class_id', $class_id)
            ->get(['id', 'first_name', 'last_name'])
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->first_name . ' ' . $s->last_name])
            ->values();

        return response()->json($students);
    }

    public function storeStructure(Request $request)
    {
        if (!Gate::allows('fee-structures-create')) {
            abort(403, 'Unauthorized access');
        }

        // Debug: Log the request data
        \Log::info('Fee Structure Creation Request:', $request->all());

        $request->validate([
            'name' => 'required|string|max:191',
            'class_id' => 'required|exists:classes,id',
            'session_id' => 'required|exists:acadmeic_sessions,id',
            'factor_id' => 'required|exists:fee_factors,id',
            'description' => 'nullable|string',
            'categories' => 'required|array|min:1',
            'categories.*.category_id' => 'required|exists:fee_categories,id',
            'categories.*.amount' => 'required|numeric|min:0',
            'categories.*.notes' => 'nullable|string',
            'student_id' => "required",
        ]);

        // ✅ Step 1: Check if already created
        // $exists = FeeStructure::where('academic_class_id', $request->class_id)
        //     ->where('academic_session_id', $request->session_id)
        //     ->where('fee_factor_id', $request->factor_id)
        //     ->where('student_id', $request->student_id)
        //     ->whereYear('created_at', '=', now()->year)
        //     ->exists();

        // if ($exists) {
        //     $year = now()->year;
        //     return redirect()->back()->with('error', "Fee structure already created for this student in year {$year}. Please update the structure.");
        // }

        $discount = FeeDiscount::where('student_id', $request->student_id)->first();
        $tuitionFeeCategoryWithDiscount = [];
        $roboticsFeeCategoryWithAmount = [];
        if ($discount) {
            foreach ($request->categories as $category) {
                $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
                if ($categories->name == "Tuition fee") {
                    if ($discount->discount_type == "percentage") {
                        $tuitionFeeCategoryWithDiscount = $category['amount'] - ($category['amount'] * $discount->discount_value / 100);
                    } else {
                        $tuitionFeeCategoryWithDiscount = $category['amount'] - $discount->discount_value;
                    }
                }
                // if ($categories->name == 'Robotics Charges') {
                //     $roboticsFeeCategoryWithAmount = $category['amount'];
                // }
            }
        } else {
            foreach ($request->categories as $category) {
                $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
                if ($categories->name == "Tuition Fee") {
                    $tuitionFeeCategoryWithDiscount = $category['amount'];
                }

                // if ($categories->name == 'Robotics Charges') {
                //     $roboticsFeeCategoryWithAmount = $category['amount'];
                // }
            }
        }

        $tuitionFeeCategoryWithFeeFector = 0;
        if ($tuitionFeeCategoryWithDiscount) {
            $factor = FeeFactor::findOrFail($request->factor_id);
            if ($factor->factor_value == 1.0) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 12;
            } elseif ($factor->factor_value == 1.2) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 10;
            } elseif ($factor->factor_value == 2.0) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 6;
            }
        }

        // $roboticsFeeCategoryWithFeeFector = 0;
        // if ($roboticsFeeCategoryWithAmount) {
        //     $factor = FeeFactor::findOrFail($request->factor_id);
        //     if ($factor->factor_value == 1.0) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 12;
        //     } elseif ($factor->factor_value == 1.2) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 10;
        //     } elseif ($factor->factor_value == 2.0) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 6;
        //     }
        // }

        $total = 0;
        foreach ($request->categories as $category) {
            $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
            if ($categories->name != "Tuition fee") {
                $total += $category['amount'];
            }
        }


        $finalAmount = $tuitionFeeCategoryWithFeeFector + $total;

        
        DB::beginTransaction();
        try {
            $structure = FeeStructure::create([
                'name' => $request->name,
                'description' => $request->description,
                'academic_class_id' => $request->class_id,
                'academic_session_id' => $request->session_id,
                'fee_factor_id' => $request->factor_id,
                'student_id' => $request->student_id,
                'is_active' => true,
                'company_id' => auth()->user()->company_id ?? null,
                'branch_id' => auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
                'final_amount' => $finalAmount  ?? 0,
            ]);

            foreach ($request->categories as $category) {
                FeeStructureDetail::create([
                    'fee_structure_id' => $structure->id,
                    'fee_category_id' => $category['category_id'],
                    'amount' => $category['amount'],
                    'notes' => $category['notes'] ?? null,
                    'company_id' => auth()->user()->company_id ?? null,
                    'branch_id' => auth()->user()->branch_id ?? null,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            \Log::info('Fee Structure Created Successfully:', ['structure_id' => $structure->id]);
            return redirect()->route('admin.fee-management.structures')
                ->with('success', 'Fee structure created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Fee Structure Creation Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Error creating fee structure: ' . $e->getMessage());
        }
    }

    public function editStructure($id)
    {
        if (!Gate::allows('fee-structures-edit')) {
            abort(403, 'Unauthorized access');
        }

        $structure = FeeStructure::with('feeStructureDetails')->findOrFail($id);
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $factors = FeeFactor::where('is_active', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();
        $students = Students::query()
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->get();

        return view('admin.fee-management.structures.edit', compact('structure', 'classes', 'sessions', 'factors', 'categories', 'students'));
    }

    public function updateStructure(Request $request, $id)
    {

        if (!Gate::allows('fee-structures-edit')) {
            abort(403, 'Unauthorized access');
        }


        $request->validate([
            'name' => 'required|string|max:255',
            'academic_class_id' => 'required|exists:classes,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'fee_factor_id' => 'required|exists:fee_factors,id',
            'description' => 'nullable|string',
            'categories' => 'required|array|min:1',
            'categories.*.category_id' => 'required|exists:fee_categories,id',
            'categories.*.amount' => 'required|numeric|min:0',
            'categories.*.notes' => 'nullable|string',
            'student_id' => "required",
        ]);



        $discount = FeeDiscount::where('student_id', $request->student_id)->first();
        $tuitionFeeCategoryWithDiscount = [];
        $roboticsFeeCategoryWithAmount = [];
        if ($discount) {
            foreach ($request->categories as $category) {
                $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
                if ($categories->name == "Tuition fee") {
                    if ($discount->discount_type == "percentage") {
                        $tuitionFeeCategoryWithDiscount = $category['amount'] - ($category['amount'] * $discount->discount_value / 100);
                    } else {
                        $tuitionFeeCategoryWithDiscount = $category['amount'] - $discount->discount_value;
                    }
                }
                // if ($categories->name == 'Robotics Charges') {
                //     $roboticsFeeCategoryWithAmount = $category['amount'];
                // }
            }
        } else {
            foreach ($request->categories as $category) {
                $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
                if ($categories->name == "Tuition Fee") {
                    $tuitionFeeCategoryWithDiscount = $category['amount'];
                }

                // if ($categories->name == 'Robotics Charges') {
                //     $roboticsFeeCategoryWithAmount = $category['amount'];
                // }
            }
        }

        // dd($tuitionFeeCategoryWithDiscount , $request->fee_factor_id);

        if ($tuitionFeeCategoryWithDiscount) {
            $factor = FeeFactor::findOrFail($request->fee_factor_id);
            if ($factor->factor_value == 1.0) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 12;
            } elseif ($factor->factor_value == 1.2) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 10;
            } elseif ($factor->factor_value == 2.0) {
                $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 6;
            }
        }

        // $roboticsFeeCategoryWithFeeFector = 0;

        // if ($roboticsFeeCategoryWithAmount) {
        //     $factor = FeeFactor::findOrFail($request->fee_factor_id);
        //     if ($factor->factor_value == 1.0) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 12;
        //     } elseif ($factor->factor_value == 1.2) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 10;
        //     } elseif ($factor->factor_value == 2.0) {
        //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 6;
        //     }
        // }

        $total = 0;
        foreach ($request->categories as $category) {
            $categories = FeeCategory::where('is_active', 1)->where('id', $category['category_id'])->first();
            if ($categories->name != "Tuition fee") {
                $total += $category['amount'];
            }
        }

        $finalAmount = $tuitionFeeCategoryWithFeeFector  + $total;


        DB::beginTransaction();
        try {
            $structure = FeeStructure::findOrFail($id);
            $structure->update([
                'name' => $request->name,
                'academic_class_id' => $request->academic_class_id,
                'academic_session_id' => $request->academic_session_id,
                'fee_factor_id' => $request->fee_factor_id,
                'description' => $request->description,
                'student_id' => $request->student_id,
                'updated_by' => auth()->id(),
                'final_amount' => $finalAmount  ?? 0,
            ]);

            // Delete existing details
            FeeStructureDetail::where('fee_structure_id', $structure->id)->delete();

            // Create new details
            foreach ($request->categories as $category) {
                FeeStructureDetail::create([
                    'fee_structure_id' => $structure->id,
                    'fee_category_id' => $category['category_id'],
                    'amount' => $category['amount'],
                    'notes' => $category['notes'] ?? null,
                    'company_id' => auth()->user()->company_id ?? null,
                    'branch_id' => auth()->user()->branch_id ?? null,
                    'created_by' => auth()->id(),
                ]);
            }


            DB::commit();
            return redirect()->route('admin.fee-management.structures')
                ->with('success', 'Fee structure updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating fee structure: ' . $e->getMessage());
        }
    }

    public function deleteStructure($id)
    {
        if (!Gate::allows('fee-structures-delete')) {
            abort(403, 'Unauthorized access');
        }

        $structure = FeeStructure::findOrFail($id);
        $structure->delete();

        return response()->json(['message' => 'Structure deleted successfully!']);
    }

    /**
     * Fee Collections Management
     */
    public function collections()
    {
        if (!Gate::allows('fee-collections-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.collections.index');
    }

    public function getCollectionsData()
    {
        $collections = FeeCollection::with(['student.AcademicClass', 'academicSession', 'details', 'billing'])
            ->select(['id', 'student_id', 'academic_session_id', 'fee_assignment_id', 'billing_id', 'paid_amount', 'collection_date', 'payment_method', 'created_at']);

        return DataTables::of($collections)
            ->addColumn('action', function ($collection) {

                $viewBtn = '';
                $editBtn = '';

                if (Gate::allows('fee-collections-view')) {
                    $viewBtn .= '<a href="' . route('admin.fee-management.collections.show', $collection->id) . '" class="btn btn-sm btn-info">View</a>';
                }

                if (Gate::allows('fee-collections-edit')) {
                    $editBtn .= '<a href="' . route('admin.fee-management.collections.edit', $collection->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                }
                return $viewBtn . ' ' . $editBtn;
            })
            ->addColumn('student_name', function ($collection) {
                return $collection->student->fullname ?? 'N/A';
            })

            ->addColumn('student_id', function ($collection) {
                return $collection->student->student_id ?? 'N/A';
            })
            ->addColumn('class_name', function ($collection) {
                return $collection->student->AcademicClass->name ?? 'N/A';
            })
            ->addColumn('challan_number', function ($collection) {
                return $collection->billing->challan_number ?? 'N/A';
            })
            ->addColumn('total_amount', function ($collection) {
                return $collection->details->sum('amount') ?? 0;
            })

            ->filterColumn('student_id', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->where('student_id', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->whereRaw("TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) LIKE ?", ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['action', 'student_name', 'student_id'])
            ->make(true);
    }

    public function showCollection($id)
    {
        if (!Gate::allows('fee-collections-view')) {
            abort(403, 'Unauthorized access');
        }

        $collection = FeeCollection::with(['student.AcademicClass', 'academicClass', 'academicSession', 'details.feeCategory', 'billing'])
            ->findOrFail($id);

        // Load transport fees for the student
        $transportFees = [];
        $totalTransportFee = 0;
        if ($collection->student) {
            $transportFees = $collection->student->transportations()
                ->where('status', 'active')
                ->with(['vehicle', 'route'])
                ->get();

            $totalTransportFee = $transportFees->sum('monthly_charges');
        }

        // Load discounts if billing exists
        $discounts = [];
        $totalDiscount = 0;
        if ($collection->billing) {
            $discounts = $collection->billing->getApplicableDiscounts()->load('category');
            $totalDiscount = $discounts->sum(function ($discount) use ($collection) {
                return $discount->calculateDiscount($collection->billing->total_amount);
            });
        }

        return view('admin.fee-management.collections.show', compact('collection', 'transportFees', 'totalTransportFee', 'discounts', 'totalDiscount'));
    }

    public function createCollection()
    {
        if (!Gate::allows('fee-collections-create')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with(['AcademicClass', 'academicSession'])->get();
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.collections.create', compact('students', 'classes', 'sessions', 'categories'));
    }

    public function getStudentsByClass($student_roll_id)
    {


        if (!Gate::allows('fee-collections-create')) {
            abort(403, 'Unauthorized access');
        }



        if (isset(request()->class_id) && request()->class_id != null) {

            $classId = request()->class_id;

            $students = Students::with(['AcademicClass', 'academicSession'])
                ->where('class_id', $classId)
                ->get();

            return response()->json([
                'students' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->fullname,
                        'class_name' => $student->AcademicClass->name ?? 'N/A',
                        'session_name' => $student->academicSession->name ?? 'N/A',
                        'class_id' => $student->AcademicClass->id ?? null,
                        'session_id' => $student->academicSession->id ?? null
                    ];
                })
            ]);
        } else {
            $student = Students::with(['AcademicClass', 'academicSession'])
                ->where('id', $student_roll_id)
                ->first();


            $data = [
                'id' => $student->id,
                'name' => $student->fullname,
                'class_name' => $student->AcademicClass->name ?? 'N/A',
                'session_name' => $student->academicSession->name ?? 'N/A',
                'class_id' => $student->AcademicClass->id ?? null,
                'session_id' => $student->academicSession->id ?? null
            ];


            return response()->json($data);
        }
    }

    public function getSessionsByClass($classId)
    {


        // Get sessions assigned to this class through ActiveSession table
        $sessions = ActiveSession::with('academicSession')
            ->where('class_id', $classId)
            ->where('status', 1)
            ->whereHas('academicSession', function ($query) {
                $query->where('status', 1);
            })
            ->get()
            ->pluck('academicSession')
            ->unique('id')
            ->values();

        // If no sessions found from ActiveSession table, return all active sessions as fallback
        if ($sessions->isEmpty()) {
            $sessions = AcademicSession::where('status', 1)->get();
        }

        return response()->json([
            'sessions' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'name' => $session->name
                ];
            })
        ]);
    }

    public function storeCollection(Request $request)
    {

        if (!Gate::allows('fee-collections-create')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'collection_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'remarks' => 'nullable|string',
            'collections' => 'required|array|min:1',
            'collections.*.category_id' => 'required|exists:fee_categories,id',
            'collections.*.amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = array_sum(array_column($request->collections, 'amount'));

            // Check if there's an existing billing for this student and session
            $billing = FeeBilling::where('student_id', $request->student_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->where('status', '!=', 'paid')
                ->first();

            // If billing exists, use billing's final amount (with discounts applied)
            if ($billing) {
                $finalAmount = $billing->getFinalAmount();
                $totalAmount = min($totalAmount, $finalAmount); // Don't collect more than due
            }

            $collection = FeeCollection::create([
                'student_id' => $request->student_id,
                'academic_session_id' => $request->academic_session_id,
                'fee_assignment_id' => $request->fee_assignment_id ?? 1,
                'paid_amount' => $totalAmount,
                'status' => 'paid',
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'remarks' => $request->remarks,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->collections as $collectionDetail) {
                FeeCollectionDetail::create([
                    'fee_collection_id' => $collection->id,
                    'fee_category_id' => $collectionDetail['category_id'],
                    'amount' => $collectionDetail['amount'],
                    'created_by' => auth()->id(),
                ]);
            }

            // Update billing status if billing exists
            if ($billing) {
                $billing->paid_amount = ($billing->paid_amount ?? 0) + $totalAmount;
                $billing->outstanding_amount = $billing->getFinalAmount() - $billing->paid_amount;

                if ($billing->outstanding_amount <= 0) {
                    $billing->status = 'paid';
                } else {
                    $billing->status = 'partially_paid';
                }
                $billing->save();
            }

            // ✅ ACCOUNTING INTEGRATION - Record fee collection in accounts
            try {
                \Log::info("=== FEE COLLECTION ACCOUNTING START ===");
                \Log::info("Collection ID: {$collection->id}, Student ID: {$collection->student_id}, Amount: {$collection->paid_amount}");

                $student = Students::find($request->student_id);
                $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();

                $integrationRequest = new \Illuminate\Http\Request([
                    'student_id' => $collection->student_id,
                    'fee_amount' => $collection->paid_amount,
                    'collection_date' => $collection->collection_date,
                    'reference' => 'FEE-' . str_pad($collection->id, 6, '0', STR_PAD_LEFT) . ' - ' . ($student->fullname ?? 'Student'),
                ]);

                \Log::info("Calling recordAcademicFee with data: " . json_encode($integrationRequest->all()));

                $response = $integrationController->recordAcademicFee($integrationRequest);

                $responseData = $response->getData(true);

                \Log::info("Integration response: " . json_encode($responseData));

                if (isset($responseData['success']) && $responseData['success']) {
                    \Log::info("✅ Fee accounting entry created successfully for collection ID: {$collection->id}, Entry ID: " . ($responseData['entry_id'] ?? 'N/A'));
                } else {
                    \Log::error("❌ Fee accounting integration returned error: " . ($responseData['message'] ?? 'Unknown error'));
                }

                \Log::info("=== FEE COLLECTION ACCOUNTING END ===");
            } catch (\Exception $e) {
                \Log::error("❌ Fee accounting integration EXCEPTION: " . $e->getMessage());
                \Log::error("Stack trace: " . $e->getTraceAsString());
                // Don't fail the fee collection if accounts integration fails
            }

            DB::commit();
            return redirect()->route('admin.fee-management.collections')
                ->with('success', 'Fee collection recorded successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error recording fee collection: ' . $e->getMessage());
        }
    }

    public function editCollection($id)
    {
        if (!Gate::allows('fee-collections-edit')) {
            abort(403, 'Unauthorized access');
        }

        $collection = FeeCollection::with(['student.AcademicClass', 'academicClass', 'academicSession', 'details.feeCategory', 'billing'])
            ->findOrFail($id);

        // Load transport fees for the student
        $transportFees = [];
        $totalTransportFee = 0;
        if ($collection->student) {
            $transportFees = $collection->student->transportations()
                ->where('status', 'active')
                ->with(['vehicle', 'route'])
                ->get();

            $totalTransportFee = $transportFees->sum('monthly_charges');
        }

        // Load discounts if billing exists
        $discounts = [];
        $totalDiscount = 0;
        if ($collection->billing) {
            $discounts = $collection->billing->getApplicableDiscounts()->load('category');
            $totalDiscount = $discounts->sum(function ($discount) use ($collection) {
                return $discount->calculateDiscount($collection->billing->total_amount);
            });
        }

        // If this is a challan-based collection, redirect to a different edit page
        if ($collection->billing_id) {
            return view('admin.fee-management.collections.edit-challan', compact('collection', 'transportFees', 'totalTransportFee', 'discounts', 'totalDiscount'));
        }

        // Otherwise, use the old edit system for non-challan collections
        $students = Students::with(['AcademicClass', 'academicSession'])->get();
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.collections.edit', compact('collection', 'students', 'classes', 'sessions', 'categories', 'transportFees', 'totalTransportFee', 'discounts', 'totalDiscount'));
    }

    /**
     * Update Challan-based Collection
     */
    public function updateChallanCollection(Request $request, $id)
    {


        if (!Gate::allows('fee-collections-edit')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'collection_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'paid_amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $collection = FeeCollection::with('billing')->findOrFail($id);

            if (!$collection->billing_id) {
                return back()->withErrors(['error' => 'This collection is not linked to a challan'])->withInput();
            }

            $challan = $collection->billing;
            $oldAmount = $collection->paid_amount;
            $newAmount = $request->paid_amount;
            $difference = $newAmount - $oldAmount;

            // Update collection record
            $collection->update([
                'paid_amount' => $newAmount,
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'remarks' => $request->remarks ?: null,
                'updated_by' => auth()->id(),
            ]);

            // Update challan amounts
            $currentPaidAmount = $challan->paid_amount ?? 0;
            $newPaidAmount = $currentPaidAmount + $difference;
            $finalAmount = $challan->getFinalAmount();
            $newOutstandingAmount = $finalAmount + $challan->fine_amount  - $newPaidAmount;

            $challan->paid_amount = $newPaidAmount;
            $challan->outstanding_amount = $newOutstandingAmount;

            // Update challan status
            if ($newOutstandingAmount <= 0) {
                $challan->status = 'paid';
            } else if ($newPaidAmount > 0) {
                $challan->status = 'partial';
            } else {
                $challan->status = 'pending';
            }
            $challan->save();

            DB::commit();

            return redirect()->route('admin.fee-management.collections')
                ->with('success', 'Challan payment updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating challan payment: ' . $e->getMessage());
            return back()->with('error', 'Error updating payment: ' . $e->getMessage())->withInput();
        }
    }

    public function updateCollection(Request $request, $id)
    {
        if (!Gate::allows('fee-collections-edit')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_class_id' => 'required|exists:classes,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'collection_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'remarks' => 'nullable|string',
            'collections' => 'required|array',
            'collections.*.category_id' => 'required|exists:fee_categories,id',
            'collections.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $collection = FeeCollection::findOrFail($id);
            $totalAmount = array_sum(array_column($request->collections, 'amount'));

            $collection->update([
                'student_id' => $request->student_id,
                'academic_session_id' => $request->academic_session_id,
                'paid_amount' => $totalAmount,
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'remarks' => $request->remarks,
                'updated_by' => auth()->id(),
            ]);

            // Delete existing details
            FeeCollectionDetail::where('fee_collection_id', $collection->id)->delete();

            // Create new details
            foreach ($request->collections as $collectionDetail) {
                FeeCollectionDetail::create([
                    'fee_collection_id' => $collection->id,
                    'fee_category_id' => $collectionDetail['category_id'],
                    'amount' => $collectionDetail['amount'],
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.fee-management.collections')
                ->with('success', 'Fee collection updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating fee collection: ' . $e->getMessage());
        }
    }

    /**
     * Fee Discounts Management
     */
    public function discounts()
    {
        if (!Gate::allows('fee-discount-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.discounts.index');
    }

    // public function getDiscountsData()
    // {
    //     $discounts = FeeDiscount::with(['student', 'category', 'createdBy'])
    //         ->select(['id', 'student_id', 'category_id', 'discount_type', 'discount_value', 'reason', 'show_on_voucher', 'valid_from', 'valid_to', 'created_at']);
    //     return DataTables::of($discounts)
    //         ->addColumn('action', function ($discount) {

    //             $deleteBtn = '';
    //             $editBtn = '';

    //             if (Gate::allows('fee-discount-edit')) {
    //                 $editBtn = '<a href="' . route('admin.fee-management.discounts.edit', $discount->id) . '" class="btn btn-sm btn-primary">Edit</a>';
    //             }

    //             if (Gate::allows('fee-discount-delete')) {
    //                 $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteDiscount(' . $discount->id . ')">Delete</button>';
    //             }


    //             return $editBtn . ' ' . $deleteBtn;
    //         })
    //         ->addColumn('student_name', function ($discount) {
    //             return $discount->student->fullname ?? 'N/A';
    //         })
    //         ->addColumn('category_name', function ($discount) {
    //             return $discount->category->name ?? 'N/A';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

    public function getDiscountsData()
    {
        $discounts = FeeDiscount::with(['student', 'category', 'createdBy'])
            ->select(['id', 'student_id', 'category_id', 'discount_type', 'discount_value', 'reason', 'show_on_voucher', 'valid_from', 'valid_to', 'created_at']);

        return DataTables::of($discounts)
            ->addColumn('action', function ($discount) {

                $deleteBtn = '';
                $editBtn = '';
                $historyBtn = '';

                if (Gate::allows('fee-discount-edit')) {
                    $editBtn = '<a href="' . route('admin.fee-management.discounts.edit', $discount->id) . '" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>';
                }

                if (Gate::allows('fee-discount-delete')) {
                    $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteDiscount(' . $discount->id . ')">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>';
                }

                // 🟡 Add History Button
                $historyBtn = '<a href="' . route('admin.fee-management.discounts.history', $discount->id) . '" 
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-history"></i> History
                                </a>';

                return $editBtn . ' ' . $deleteBtn . ' ' . $historyBtn;
            })
            ->addColumn('student_name', function ($discount) {
                return $discount->student->fullname ?? 'N/A';
            })

            ->addColumn('student_id', function ($discount) {
                return $discount->student->student_id ?? 'N/A';
            })

            ->addColumn('category_name', function ($discount) {
                return $discount->category->name ?? 'N/A';
            })

            ->filterColumn('student_id', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->where('student_id', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->whereRaw("TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) LIKE ?", ["%{$keyword}%"]);
                });
            })


            ->rawColumns(['action', 'student_id', 'student_name'])
            ->make(true);
    }


    public function createDiscount()
    {
        if (!Gate::allows('fee-discount-create')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with('AcademicClass')->get();
        $categories = FeeCategory::all();

        return view('admin.fee-management.discounts.create', compact('students', 'categories'));
    }

    public function storeDiscount(Request $request)
    {
        if (!Gate::allows('fee-discount-create')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'show_on_voucher' => 'nullable|boolean',
            'reason' => 'required|string|max:255',
            'valid_from_month' => 'required|date_format:Y-m',
            'valid_to_month' => 'required|date_format:Y-m|after_or_equal:valid_from_month'
        ]);

        FeeDiscount::create([
            'student_id' => $request->student_id,
            'category_id' => $request->category_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'show_on_voucher' => $request->show_on_voucher,
            'reason' => $request->reason,
            'valid_from' => $request->valid_from_month . '-01', // First day of month
            'valid_to' => date('Y-m-t', strtotime($request->valid_to_month . '-01')), // Last day of month
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fee-management.discounts')
            ->with('success', 'Discount created successfully!');
    }

    public function editDiscount($id)
    {
        if (!Gate::allows('fee-discount-edit')) {
            abort(403, 'Unauthorized access');
        }

        $discount = FeeDiscount::findOrFail($id);
        $students = Students::with('AcademicClass')->get();
        $categories = FeeCategory::all();

        return view('admin.fee-management.discounts.edit', compact('discount', 'students', 'categories'));
    }

    public function updateDiscount(Request $request, $id)
    {
        if (!Gate::allows('fee-discount-edit')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'valid_from_month' => 'required|date_format:Y-m',
            'valid_to_month' => 'required|date_format:Y-m|after_or_equal:valid_from_month',
        ]);

        $discount = FeeDiscount::findOrFail($id);

        $discount->update([
            'student_id' => $request->student_id,
            'category_id' => $request->category_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'reason' => $request->reason,
            'valid_from' => $request->valid_from_month . '-01', // First day of month
            'valid_to' => date('Y-m-t', strtotime($request->valid_to_month . '-01')), // Last day of month
            'show_on_voucher' => $request->has('show_on_voucher') ? 1 : 0, // ✅ This line handles the checkbox

        ]);

        return redirect()->route('admin.fee-management.discounts')
            ->with('success', 'Discount updated successfully!');
    }

    /**
     * Generate unique challan number
     */
    private function generateUniqueChallanNumber($billingMonth)
    {
        $year = date('Y', strtotime($billingMonth . '-01'));
        $month = date('m', strtotime($billingMonth . '-01'));

        $prefix = 'CHL-' . $year . '-' . $month;

        // Get the last challan number for this month
        $lastBilling = FeeBilling::where('challan_number', 'like', $prefix . '%')
            ->orderBy('challan_number', 'desc')
            ->first();

        if ($lastBilling) {
            $lastNumber = intval(substr($lastBilling->challan_number, -6));
            $billNumber = $lastNumber + 1;
        } else {
            $billNumber = 1;
        }

        return $prefix . '-' . str_pad($billNumber, 6, '0', STR_PAD_LEFT);
    }

    public function deleteDiscount($id)
    {
        if (!Gate::allows('fee-discount-delete')) {
            abort(403, 'Unauthorized access');
        }

        $discount = FeeDiscount::findOrFail($id);
        $discount->delete();

        return response()->json(['message' => 'Discount deleted successfully!']);
    }

    /**
     * Fee Billing Management
     */
    public function billing()
    {
        if (!Gate::allows('fee-billing-list')) {
            abort(403, 'Unauthorized access');
        }

        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();

        return view('admin.fee-management.billing.index', compact('classes', 'sessions'));
    }

    public function getBillingData(Request $request)
    {

        if (!Gate::allows('fee-billing-list')) {
            abort(403, 'Unauthorized access');
        }

        $billing = FeeBilling::with(['student.AcademicClass', 'academicSession'])
            ->select(['id', 'student_id', 'academic_session_id', 'challan_number', 'total_amount', 'paid_amount', 'outstanding_amount', 'due_date', 'status', 'billing_month', 'created_at']);

        // Apply month filter if provided
        if ($request->has('filter_month') && !empty($request->filter_month)) {
            $billing->where('billing_month', $request->filter_month);
        }

        return DataTables::of($billing)
            ->addColumn('action', function ($bill) {
                $printBtn = '';
                if (Gate::allows('fee-billing-print')) {
                    $printBtn .= '<a href="' . route('admin.fee-management.billing.print', $bill->id) . '" class="btn btn-sm btn-success" target="_blank">Print</a>';
                }

                return $printBtn;
            })
            ->addColumn('student_name', function ($bill) {
                return $bill->student->fullname ?? 'N/A';
            })

            ->addColumn('student_id', function ($bill) {
                return $bill->student->student_id ?? 'N/A';
            })

            ->addColumn('class_name', function ($bill) {
                return $bill->student->AcademicClass->name ?? 'N/A';
            })

            ->filterColumn('student_id', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->where('student_id', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->whereRaw("TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) LIKE ?", ["%{$keyword}%"]);
                });
            })

            ->addColumn('status', function ($bill) {
                // Determine correct status based on paid amount
                $paidAmount = $bill->paid_amount ?? 0;
                $finalAmount = $bill->getFinalAmount();
                $outstandingAmount = $finalAmount - $paidAmount;

                // dd($paidAmount , $finalAmount, $outstandingAmount);
                if ($outstandingAmount <= 0) {
                    $status = 'paid';
                    $badgeClass = 'success';
                } else if ($paidAmount > 0) {
                    $status = 'partial';
                    $badgeClass = 'warning';
                } else {
                    $status = 'pending';
                    $badgeClass = 'info';
                }

                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })



            ->rawColumns(['action', 'student_id', 'student_name', 'status'])
            ->make(true);
    }

    // public function generateBilling(Request $request)
    // {

    //     if (!Gate::allows('fee-billing-create')) {
    //         abort(403, 'Unauthorized access');
    //     }

    //     $request->validate([
    //         'academic_class_id' => 'required|exists:classes,id',
    //         'academic_session_id' => 'required|exists:acadmeic_sessions,id',
    //         'billing_month' => 'required|date_format:Y-m',
    //         'exclude_arrears' => 'boolean',
    //     ]);

    //     DB::beginTransaction();
    //     try {

    //         $activeSession = ActiveSession::where('class_id', $request->academic_class_id)
    //             ->where('session_id', $request->academic_session_id)
    //             ->where('status', 1)
    //             ->first();

    //         if (!$activeSession) {
    //             return redirect()->route('admin.fee-management.billing')
    //                 ->with('error', 'No active session found for the selected class and session combination.');
    //         }

    //         // Get students in the specified class (session relationship is managed through ActiveSession)
    //         $student = Students::where('class_id', $request->academic_class_id)
    //             ->where('id', $request->student_id)
    //             ->where('is_active', 1)
    //             ->first();

    //         if (!$student) {

    //             return redirect()->route('admin.fee-management.billing')
    //                 ->with('error', 'No students found for the selected class and session.');
    //         }

    //         // Get fee structure for the class and session
    //         $feeStructure = FeeStructure::where('academic_class_id', $request->academic_class_id)
    //             ->where('academic_session_id', $request->academic_session_id)
    //             ->where('student_id', $request->student_id)
    //             ->where('is_active', 1)
    //             ->first();

    //         if (!$feeStructure) {
    //             return redirect()->route('admin.fee-management.billing')->with('error', 'No fee structure found for the selected student , class and session.');
    //         }


    //         $existingBilling = FeeBilling::where('student_id', $student->id)
    //             ->where('academic_session_id', $request->academic_session_id)
    //             ->where('billing_month', $request->billing_month)
    //             ->first();

    //         if ($existingBilling) {

    //             return redirect()->route('admin.fee-management.billing')
    //                 ->with('error', 'Billing already exists for student: ' . $student->first_name . ' ' . $student->last_name);
    //         }
    //         $totalAmount = $feeStructure->final_amount;

    //         $challanNumber = $this->generateUniqueChallanNumber($request->billing_month);

    //         // Create billing record
    //         $billing = FeeBilling::create([
    //             'student_id' => $student->id,
    //             'academic_session_id' => $request->academic_session_id,
    //             'challan_number' => $challanNumber,
    //             'billing_month' => $request->billing_month,
    //             'total_amount' => $totalAmount,
    //             'bill_date' => now(),
    //             'due_date' => now()->addDays(30), // 30 days from now
    //             'outstanding_amount' => $totalAmount, // Initially outstanding amount equals total amount
    //             'status' => 'generated',
    //             'company_id' => auth()->user()->company_id ?? null,
    //             'branch_id' => auth()->user()->branch_id ?? null,
    //             'created_by' => auth()->id(),
    //         ]);

    //         // Apply discounts automatically
    //         $billing->applyDiscounts();



    //         DB::commit();


    //         return redirect()->route('admin.fee-management.billing')
    //             ->with('success', "Billing generated successfully");
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         \Log::error('Billing generation error: ' . $e->getMessage());
    //         return redirect()->route('admin.fee-management.billing')
    //             ->with('error', 'Error generating billing: ' . $e->getMessage());
    //     }
    // }


    public function generateBilling(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'academic_class_id' => 'required|exists:classes,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'billing_month' => 'required|date_format:Y-m',
            'due_date' => 'required',
            'exclude_arrears' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            \Log::info('Billing generation started', [
                'class_id'      => $request->academic_class_id,
                'session_id'    => $request->academic_session_id,
                'billing_month' => $request->billing_month
            ]);

            // Get students in the specified class and session
            $students = Students::where('class_id', $request->academic_class_id)
                ->where('session_id', $request->academic_session_id)
                ->get();

            \Log::info('Students found: ' . $students->count());

            if ($students->isEmpty()) {
                \Log::info('No students found for class: ' . $request->academic_class_id . ' and session: ' . $request->academic_session_id);
                DB::rollBack();
                return redirect()->route('admin.fee-management.billing')
                    ->with('error', 'No students found for the selected class and session.');
            }

            $billingCount = 0;
            \Log::info('Processing ' . $students->count() . ' students for billing');

            // Anchor bill and due dates to the target month (optional but cleaner)
            $billDate = \Carbon\Carbon::now();

            $dueDate = $request->input('due_date');
            // $dueDate  = \Carbon\Carbon::now()->addDays(30);

            foreach ($students as $student) {
                \Log::info('Processing student: ' . $student->id . ' - ' . ($student->fullname ?? 'N/A'));

                // Skip if fee structure not found for this student
                $feeStructure = FeeStructure::where('academic_class_id', $request->academic_class_id)
                    ->where('academic_session_id', $request->academic_session_id)
                    ->where('student_id', $student->id)
                    ->where('is_active', 1)
                    ->orderBy('id', 'desc')
                    ->first();

                    // dd($feeStructure);

                if (!$feeStructure) {
                    \Log::info("Skipping student {$student->id}: no active fee structure for the selected class/session.");
                    continue; // <- skip just this student
                }

                // Skip if billing already exists for this student, session and billing month
                $existingBilling = FeeBilling::where('student_id', $student->id)
                    ->where('academic_session_id', $request->academic_session_id)
                    ->where('billing_month', $request->billing_month)
                    ->exists();

                if ($existingBilling) {
                    \Log::info("Skipping student {$student->id}: billing already exists for {$request->billing_month}.");
                    continue; // <- skip just this student
                }

                // Calculate total amount from fee structure (using final_amount as in your code)
                $totalAmount = (float) $feeStructure->final_amount;
                \Log::info("Total amount calculated for student {$student->id}: {$totalAmount}");

                // Generate challan number (kept your format; ensure uniqueness if needed)
                // $challanNumber = 'CHL-' . date('Y') . '-' . str_pad($student->id, 6, '0', STR_PAD_LEFT);
                $challanNumber = $this->generateUniqueChallanNumber($request->billing_month);


                // Create billing record
                $billing = FeeBilling::create([
                    'student_id'         => $student->id,
                    'academic_session_id' => $request->academic_session_id,
                    'challan_number'     => $challanNumber,
                    'billing_month'      => $request->billing_month,
                    'total_amount'       => $totalAmount,
                    'bill_date'          => $billDate,
                    'due_date'           => $dueDate,
                    'outstanding_amount' => $totalAmount,
                    'status'             => 'generated',
                    'company_id'         => auth()->user()->company_id ?? null,
                    'branch_id'          => auth()->user()->branch_id ?? null,
                    'created_by'         => auth()->id(),
                ]);

                \Log::info("Billing created for student {$student->id} with ID: {$billing->id}");

                // Create Students Ledger record using servies

                $this->ledgerservice->generateMonthlyForStudents($student, $billDate, $dueDate, $challanNumber, $billing, $totalAmount);

                $billingCount++;
            }

            DB::commit();

            if ($billingCount > 0) {
                return redirect()->route('admin.fee-management.billing')
                    ->with('success', "Billing generated successfully for {$billingCount} students!");
            }

            return redirect()->route('admin.fee-management.billing')
                ->with('warning', 'No new billing records were created. All eligible students were skipped (missing fee structure or already billed).');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Billing generation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.fee-management.billing')
                ->with('error', 'Error generating billing: ' . $e->getMessage());
        }
    }



    public function printBilling($id)
    {
        if (!Gate::allows('fee-billing-print')) {
            abort(403, 'Unauthorized access');
        }

        $billing = FeeBilling::with(['student.AcademicClass', 'student.branch', 'academicSession'])
            ->findOrFail($id);


        $applicableDiscounts = $billing->getApplicableDiscounts()->load('category');

        if ($applicableDiscounts) {
            $showDiscount = DB::table('fee_discounts')->where('student_id', $billing->student_id)->select('show_on_voucher')->first();
        }

       
        $baseAmount     = (float) ($billing->total_amount ?? 0);
        $totalDiscount  = 0.0;
        if ($applicableDiscounts && $applicableDiscounts->count() > 0) {
            foreach ($applicableDiscounts as $disc) {
                $type  = strtolower($disc->discount_type ?? 'fixed'); // 'percentage' or 'fixed'
                $value = (float) ($disc->discount_value ?? 0);
                if ($type === 'percentage' && $value > 0) {
                    $totalDiscount += round(($baseAmount * ($value / 100)), 2);
                } elseif ($type === 'fixed' && $value > 0) {
                    $totalDiscount += $value;
                } else {
                    $totalDiscount += $baseAmount;
                }
            }
        }

        // dd($totalDiscount);

        $transportFees = [];
        $totalTransportFee = 0;
        if ($billing->student) {
            $transportFees = $billing->student->transportations()
                ->where('status', 'active')
                ->with(['vehicle', 'route'])
                ->get();

            $totalTransportFee = $transportFees->sum('monthly_charges');
        }

        // ---------- Previous Unpaid Amount ----------
        $previousUnpaidBills = FeeBilling::where('student_id', $billing->student_id)
            ->where('id', '!=',  $id)
            ->where('outstanding_amount', '>', 0)->get();
        $previousArrears = $previousUnpaidBills->sum('outstanding_amount');
        $unpaidMonthsList = $previousUnpaidBills->pluck('billing_month')->filter()->unique()->values();

        $fineAmount = 0.0;
        if (!empty($billing->due_date) && now()->gt($billing->due_date)) {
            $fineAmount = 1500.0;
        }


        // $sumForAllData =  $baseAmount - $totalDiscount + $fineAmount + $totalTransportFee +  $previousArrears;
        $sumForAllData =  $baseAmount + $fineAmount + $totalTransportFee +  $previousArrears;

        $hardcodedAmount =  $baseAmount + $totalTransportFee +  $previousArrears;
        // $hardcodedAmount =  $baseAmount - $totalDiscount + $totalTransportFee +  $previousArrears;
    
        return view('admin.fee-management.billing.print', compact('billing', 'applicableDiscounts', 'showDiscount', 'transportFees', 'totalTransportFee', 'previousArrears', 'unpaidMonthsList', 'previousUnpaidBills', 'fineAmount', 'sumForAllData', 'hardcodedAmount'));
    }

    /**
     * Pay Challan Page
     */
    public function payChallan()
    {
        if (!Gate::allows('pay-challan')) {
            abort(403, 'Unauthorized access');
        }
        $students = Students::where('status', 1)->where('is_active', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();

        return view('admin.fee-management.collections.pay-challan', compact('classes', 'students'));
    }

    /**
     * Get Challans by Student
     */
    public function getChallansByStudent($studentId)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $challans = FeeBilling::where('student_id', $studentId)
            ->where('status', '!=', 'draft')
            ->where('outstanding_amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($challan) {
                // Determine correct status based on paid amount
                $paidAmount = $challan->paid_amount ?? 0;
                $finalAmount = $challan->getFinalAmount();

                $fine_amount = 0;
                if (now()->gt($challan->due_date) ) {
                    $fine_amount += 1500;
                }


                $outstandingAmount = $finalAmount + $fine_amount - $paidAmount;

                if ($outstandingAmount <= 0) {
                    $status = 'paid';
                } else if ($paidAmount > 0) {
                    $status = 'partial';
                } else {
                    $status = 'pending';
                }


                return [
                    'id' => $challan->id,
                    'challan_number' => $challan->challan_number,
                    'billing_month' => $challan->billing_month,
                    'total_amount' => $challan->total_amount,
                    'paid_amount' => $paidAmount,
                    'outstanding_amount' => $outstandingAmount,
                    'due_date' => $challan->due_date,
                    'status' => $status,
                    'created_at' => $challan->created_at,
                    'fine_amount' => $fine_amount,
                ];
            });

        return response()->json(['challans' => $challans]);
    }

    /**
     * Get Challan Discounts
     */
    public function getChallanDiscounts($challanId)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        try {
            $challan = FeeBilling::findOrFail($challanId);
            $applicableDiscounts = $challan->getApplicableDiscounts()->load('category');

            $totalDiscount = 0;
            $discounts = [];

            foreach ($applicableDiscounts as $discount) {
                $discountAmount = $discount->calculateDiscount($challan->total_amount);
                $totalDiscount += $discountAmount;

                $discounts[] = [
                    'category_name' => $discount->category->name ?? 'General',
                    'discount_type' => $discount->discount_type,
                    'discount_value' => $discount->discount_value,
                    'discount_amount' => $discountAmount
                ];
            }

            // $fineAmount = 0;
            // if (now()->gt($challan->due_date)) {
            //     $fineAmount += 1500;
            // }

            // $finalAmount = $challan->total_amount  - $totalDiscount;
            $finalAmount = $challan->total_amount;

            return response()->json([
                'discounts' => 0,
                'totalDiscount' => 0,
                // 'discounts' => $discounts,
                // 'totalDiscount' => $totalDiscount,
                'finalAmount' => $finalAmount,

            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading challan discounts: ' . $e->getMessage());
            return response()->json(['discounts' => [], 'totalDiscount' => 0, 'finalAmount' => 0]);
        }
    }

    /**
     * Get student transport fees for fee collection
     */
    public function getStudentTransportFees($studentId)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        try {
            $student = Students::findOrFail($studentId);
            $transportFees = $student->transportations()
                ->where('status', 'active')
                ->with(['vehicle', 'route'])
                ->get();

            $totalTransportFee = 0;
            $transportData = [];

            foreach ($transportFees as $transport) {
                $totalTransportFee += $transport->monthly_charges;

                $transportData[] = [
                    'vehicle_number' => $transport->vehicle->vehicle_number ?? 'N/A',
                    'route_name' => $transport->route->route_name ?? 'N/A',
                    'monthly_charges' => $transport->monthly_charges
                ];
            }

            return response()->json([
                'transportFees' => $transportData,
                'totalTransportFee' => $totalTransportFee
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading student transport fees: ' . $e->getMessage());
            return response()->json(['transportFees' => [], 'totalTransportFee' => 0]);
        }
    }

    /**
     * Store Challan Payment
     */
    public function storeChallanPayment(Request $request)
    {
        
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'challan_id' => 'required|exists:fee_billing,id',
            'collection_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'paid_amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Get the challan
            $challan = FeeBilling::findOrFail($request->challan_id);


            // Validate payment amount
            $finalAmount = $challan->getFinalAmount();
            $currentPaidAmount = $challan->paid_amount ?? 0;
            $maxPayableAmount = $finalAmount + $request->fine_amount - $currentPaidAmount;

            if ($request->paid_amount > $maxPayableAmount) {
                return back()->withErrors(['paid_amount' => 'Payment amount cannot exceed maximum payable amount (Rs. ' . number_format($maxPayableAmount, 2) . ')'])->withInput();
            }

            // Create fee collection record
            $collection = FeeCollection::create([
                'student_id' => $request->student_id,
                'academic_session_id' => $request->academic_session_id,
                'billing_id' => $challan->id,
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'remarks' => $request->remarks ?: null,
                'company_id' => auth()->user()->company_id ?? 1,
                'branch_id' => auth()->user()->branch_id ?? 1,
                'created_by' => auth()->id()
            ]);

            // Update challan status and outstanding amount
            $newPaidAmount = $currentPaidAmount + $request->paid_amount;
            $newOutstandingAmount = $finalAmount + $request->fine_amount - $newPaidAmount;

            $challan->paid_amount = $newPaidAmount;
            $challan->outstanding_amount = $newOutstandingAmount;

            // Determine new status - only 3 statuses: paid, pending, partial
            if ($newOutstandingAmount <= 0) {
                $challan->status = 'paid';
            } else if ($newPaidAmount > 0) {
                $challan->status = 'partial';
            } else {
                $challan->status = 'pending';
            }

            $challan->fine_amount = $request->fine_amount ?? 0;
            $challan->save();

            // Create A collection
            $invoice = CustomerInvoice::where('id', $challan->customer_invoice_id)
                ->where('student_id', $request->student_id)
                ->lockForUpdate()
                ->first();

            if ($invoice) {
                $receivedOld = (float) ($invoice->received_amount ?? 0);
                $paidNow     = (float) ($request->paid_amount ?? 0);
                $receivedNew = $receivedOld + $paidNow;

                // Recalculate balance
                $balance = (float) $invoice->total_amount - $receivedNew;

                // Select exact correct status
                if ($balance <= 0.00) {
                    $status = 'paid';
                    $balance = 0; // no negative
                } elseif ($receivedNew > 0 && $balance > 0) {
                    $status = 'partially_paid';
                } else {
                    $status = 'sent'; // agar pehle hi sent tha aur kuch pay nahi hua
                }
                $invoice->update([
                    'received_amount' => $receivedNew,
                    'balance'         => $balance,
                    'status'          => $status,
                ]);
            }

            DB::table('payment_allocations')->insert([
                'journal_entry_id' => null,
                'customer_invoice_id' => $challan->customer_invoice_id ?? null,
                'student_id' => $collection->student_id,
                'amount' => $collection->paid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ✅ ACCOUNTING INTEGRATION - Record fee collection in accounts
            try {
                \Log::info("=== CHALLAN PAYMENT ACCOUNTING START ===");
                \Log::info("Collection ID: {$collection->id}, Challan: {$challan->challan_number}, Student ID: {$collection->student_id}, Amount: {$collection->paid_amount}");

                $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();
               
                $integrationRequest = new \Illuminate\Http\Request([
                    'student_id' => $collection->student_id,
                    'fee_amount' => $collection->paid_amount,
                    'collection_date' => $collection->collection_date,
                    'reference' => 'FEE-' . str_pad($collection->id, 6, '0', STR_PAD_LEFT) . ' (Challan: ' . $challan->challan_number . ')',
                    'customer_invoice_id' => $challan->customer_invoice_id //new add Here
                ]);

                \Log::info("Calling recordAcademicFee with data: " . json_encode($integrationRequest->all()));

                $response = $integrationController->recordAcademicFee($integrationRequest);
                $responseData = $response->getData(true);
                \Log::info("Integration response: " . json_encode($responseData));

                if (isset($responseData['success']) && $responseData['success']) {
                    \Log::info("✅ Challan payment accounting entry created successfully, Entry ID: " . ($responseData['entry_id'] ?? 'N/A'));
                } else {
                    \Log::error("❌ Challan payment accounting returned error: " . ($responseData['message'] ?? 'Unknown error'));
                }

                \Log::info("=== CHALLAN PAYMENT ACCOUNTING END ===");
            } catch (\Exception $e) {
                \Log::error("❌ Challan payment accounting EXCEPTION: " . $e->getMessage());
                \Log::error("Stack trace: " . $e->getTraceAsString());
                // Don't fail the whole transaction, just log the error
            }

            DB::commit();

            return redirect()->route('admin.fee-management.collections')
                ->with('success', 'Payment processed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error processing challan payment: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error processing payment. Please try again.'])->withInput();
        }
    }

    /**
     * Reports
     */
    public function reports()
    {
        if (!Gate::allows('fee-report-list')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with(['academicClass', 'academicSession'])->get();

        return view('admin.fee-management.reports.index', compact('students'));
    }

    public function incomeReport(Request $request)
    {
        if (!Gate::allows('fee-report-income')) {
            abort(403, 'Unauthorized access');
        }

        $fromDate = $request->from_date ?? date('Y-m-01');
        $toDate = $request->to_date ?? date('Y-m-d');

        $collections = FeeCollection::whereBetween('collection_date', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->with(['student', 'academicClass'])
            ->get();

        return view('admin.fee-management.reports.income', compact('collections', 'fromDate', 'toDate'));
    }

    public function outstandingReport(Request $request)
    {
        if (!Gate::allows('fee-report-outstanding')) {
            abort(403, 'Unauthorized access');
        }

        $classId = $request->class_id;
        $sessionId = $request->session_id;

        $query = FeeBilling::where('status', '!=', 'paid')
            ->with(['student.academicClass', 'academicSession']);

        if ($classId) {
            $query->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        if ($sessionId) {
            $query->where('academic_session_id', $sessionId);
        }

        $outstanding = $query->get();

        return view('admin.fee-management.reports.outstanding', compact('outstanding'));
    }

    public function studentLedger($studentId)
    {
        if (!Gate::allows('fee-report-ledger')) {
            abort(403, 'Unauthorized access');
        }

        $student = Students::with(['academicClass', 'academicSession'])->findOrFail($studentId);

        $collections = FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.feeCategory', 'billing'])
            ->orderBy('collection_date', 'desc')
            ->get();

        $adjustments = FeeAdjustment::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        $feeBilling = FeeBilling::where('student_id', $studentId)->orderBy('created_at', 'desc')->get();


        return view('admin.fee-management.reports.student-ledger', compact('student', 'collections', 'adjustments', 'feeBilling'));
    }

    public function exportStudentLedgerPdf($studentId)
    {
        $student = Students::with(['academicClass', 'academicSession'])->findOrFail($studentId);

        $collections = FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.feeCategory', 'billing'])
            ->orderBy('collection_date', 'desc')
            ->get();

        $adjustments = FeeAdjustment::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
        $pdf = Pdf::loadView('admin.fee-management.reports.student-ledger-pdf', compact('student', 'collections', 'adjustments'));
        return $pdf->download('student-ledger-' . $student->student_id . '.pdf');
    }

    public function exportStudentLedgerExcel($studentId)
    {
        $student = Students::with(['academicClass', 'academicSession'])->findOrFail($studentId);

        // optional reporting period from querystring (yyyy-mm-dd), fallback wide range
        $from = request()->get('from'); // e.g. 2025-07-01
        $to   = request()->get('to');   // e.g. 2025-10-29

        // Bills / Charges (Debit) - FeeBilling model
        $billsQuery = FeeBilling::where('student_id', $studentId);
        if ($from) $billsQuery->whereDate('bill_date', '>=', $from);
        if ($to)   $billsQuery->whereDate('bill_date', '<=', $to);
        $bills = $billsQuery->orderBy('bill_date', 'asc')->get();

        // Collections (Credit)
        $collectionsQuery = FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.feeCategory', 'billing']);
        if ($from) $collectionsQuery->whereDate('collection_date', '>=', $from);
        if ($to)   $collectionsQuery->whereDate('collection_date', '<=', $to);
        $collections = $collectionsQuery->orderBy('collection_date', 'asc')->get();

        // Adjustments
        $adjustmentsQuery = FeeAdjustment::where('student_id', $studentId);
        if ($from) $adjustmentsQuery->whereDate('created_at', '>=', $from);
        if ($to)   $adjustmentsQuery->whereDate('created_at', '<=', $to);
        $adjustments = $adjustmentsQuery->orderBy('created_at', 'asc')->get();

        $export = new StudentLedgerSingleSheetExport(
            $student,
            $bills,
            $collections,
            $adjustments,
            $from,
            $to
        );

        $filename = 'student-ledger-' . $student->student_id . '-' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export, $filename);
    }


    // Export and Import

    public function downloadTemplateByFeeCategory()
    {

        $file = public_path('templates/fee_category.xlsx');

        if (file_exists($file)) {
            return response()->download($file, 'fee_category.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found!');
        }
    }

    public function importByFeeCategory(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            $file = $request->file('file');
            Excel::import(new FeeCategoryImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Fee Category Imported Successfully!');
    }

    // Export And Import By Structure

    public function downloadTemplateByStructure()
    {

        $file = public_path('templates/fee-structures.xlsx');
        if (file_exists($file)) {
            return response()->download($file, 'fee-structures.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found!');
        }
    }


    public function importByStructure(Request $request)
    {


        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            $file = $request->file('file');
            Excel::import(new FeeStructureImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Structure Imported Successfully!');
    }

    // Export And Import By Discount

    public function downloadTemplateByDiscount()
    {

        $file = public_path('templates/fee-discounts.xlsx');

        if (file_exists($file)) {
            return response()->download($file, 'fee-discounts.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found!');
        }
    }


    public function importByDiscount(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            $file = $request->file('file');
            Excel::import(new FeeDiscountImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Fee Discount Imported Successfully!');
    }

    // Export And Import By Fee Collection

    public function downloadTemplateByCollection()
    {

        $file = public_path('templates/fee_collection.xlsx');

        if (file_exists($file)) {
            return response()->download($file, 'fee_collection.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found!');
        }
    }


    public function importByCollection(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            $file = $request->file('file');
            Excel::import(new FeeCollectionImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Fee Collection Imported Successfully!');
    }


    public function history($id)
    {
        $histories = FeeDiscountHistory::with('histories', 'updateUser')
            ->where('fee_discount_id', $id)
            ->get();
        // dd($histories);
        return view('admin.fee-management.discounts.history', compact('histories'));
    }

    // Student Fee Billing Status 

    public function feeBillsStatusReport(Request $request)
    {

        if ($request->ajax()) {
            $feeBilling = FeeBilling::with(['student.academicClass', 'academicSession', 'createdBy']);

            if ($request->has('filter_month') && !empty($request->filter_month)) {
                $feeBilling->where('billing_month', $request->filter_month);
            }

            if ($request->has('status') && !empty($request->status)) {
                $feeBilling->where('status', $request->status);
            }

            return DataTables::of($feeBilling)

                ->addColumn('student_name', function ($data) {
                    return $data->student->fullname;
                })

                ->addColumn('student_id', function ($data) {
                    return $data->student->student_id;
                })

                ->addColumn('father_name', function ($data) {
                    return $data->student->father_name;
                })

                ->addColumn('class', function ($data) {
                    return $data->student->academicClass->name;
                })

                ->addColumn('session', function ($data) {
                    return $data->academicSession->name;
                })

                ->addColumn('status', function ($data) {
                    if ($data->status == 'paid') {
                        return '<span class="badge badge-success"> Paid </span>';
                    } elseif ($data->status == 'partially_paid') {
                        return '<span class="badge badge-warning"> Partially Paid </span>';
                    } else {
                        return '<span class="badge badge-info"> Generated </span>';
                    }
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('class_id') && !empty($request->class_id)) {
                        $query->whereHas('student', function ($q) use ($request) {
                            $q->where('class_id', $request->class_id);
                        });
                    }
                })



                ->rawColumns(['father_name', 'student_name', 'class', 'session', 'student_id', 'status'])
                ->addIndexColumn()
                ->make(true);
        }

        $classes = AcademicClass::where('status', 1)->get();
        return view('admin.fee-management.reports.student-fee-status', compact('classes'));
    }

    // feeBillsByClass
  public function feeBillsByClass(Request $request)
    {

        if ($request->ajax()) {
            $feeBilling = FeeBilling::with(['student.academicClass', 'academicSession', 'createdBy']);

            if ($request->has('filter_month') && !empty($request->filter_month)) {
                $feeBilling->where('billing_month', $request->filter_month);
            }

            if ($request->has('status') && !empty($request->status)) {
                $feeBilling->where('status', $request->status);
            }

            return DataTables::of($feeBilling)

                ->addColumn('student_name', function ($data) {
                    return $data->student->fullname;
                })

                ->addColumn('student_id', function ($data) {
                    return $data->student->student_id;
                })

                ->addColumn('father_name', function ($data) {
                    return $data->student->father_name;
                })

                ->addColumn('class', function ($data) {
                    return $data->student->academicClass->name;
                })

                ->addColumn('session', function ($data) {
                    return $data->academicSession->name;
                })

                ->addColumn('outstanding_amount', function ($data) {
                    return $data->outstanding_amount;
                })


                ->addColumn('status', function ($data) {
                    if ($data->status == 'paid') {
                        return '<span class="badge badge-success"> Paid </span>';
                    } elseif ($data->status == 'partially_paid') {
                        return '<span class="badge badge-warning"> Partially Paid </span>';
                    } else {
                        return '<span class="badge badge-info"> Generated </span>';
                    }
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('class_id') && !empty($request->class_id)) {
                        $query->whereHas('student', function ($q) use ($request) {
                            $q->where('class_id', $request->class_id);
                        });
                    }
                })



                ->rawColumns(['father_name', 'student_name', 'class', 'session', 'student_id', 'status' , 'outstanding_amount'])
                ->addIndexColumn()
                ->make(true);
        }

        $classes = AcademicClass::where('status', 1)->get();
        return view('admin.fee-management.reports.fee-bills-by-class', compact('classes'));
    }

    // feeBillsByAccount

     public function feeBillsByAccount(Request $request)
    {

        if ($request->ajax()) {
            $feeBilling = FeeBilling::with(['student.academicClass', 'academicSession', 'createdBy']);

            if ($request->has('filter_month') && !empty($request->filter_month)) {
                $feeBilling->where('billing_month', $request->filter_month);
            }

            if ($request->has('status') && !empty($request->status)) {
                $feeBilling->where('status', $request->status);
            }

            return DataTables::of($feeBilling)

                ->addColumn('student_name', function ($data) {
                    return $data->student->fullname;
                })

                ->addColumn('student_id', function ($data) {
                    return $data->student->student_id;
                })

                ->addColumn('father_name', function ($data) {
                    return $data->student->father_name;
                })

                ->addColumn('class', function ($data) {
                    return $data->student->academicClass->name;
                })

                ->addColumn('session', function ($data) {
                    return $data->academicSession->name;
                })

                ->addColumn('outstanding_amount', function ($data) {
                    return $data->outstanding_amount;
                })


                ->addColumn('status', function ($data) {
                    if ($data->status == 'paid') {
                        return '<span class="badge badge-success"> Paid </span>';
                    } elseif ($data->status == 'partially_paid') {
                        return '<span class="badge badge-warning"> Partially Paid </span>';
                    } else {
                        return '<span class="badge badge-info"> Generated </span>';
                    }
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('class_id') && !empty($request->class_id)) {
                        $query->whereHas('student', function ($q) use ($request) {
                            $q->where('class_id', $request->class_id);
                        });
                    }
                })



                ->rawColumns(['father_name', 'student_name', 'class', 'session', 'student_id', 'status' , 'outstanding_amount'])
                ->addIndexColumn()
                ->make(true);
        }

        $classes = AcademicClass::where('status', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();
        return view('admin.fee-management.reports.fee-bills-by-account', compact('classes'));
    }

}
