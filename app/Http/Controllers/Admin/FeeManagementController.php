<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeStructure;
use App\Models\Fee\FeeStructureDetail;
use App\Models\Fee\StudentFeeAssignment;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Fee\FeeDiscount;
use App\Models\Fee\FeeAdjustment;
use App\Models\Fee\FeeAllocation;
use App\Models\Fee\FeeFactor;
use App\Models\Fee\FeeBilling;
use App\Models\Student\Students;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class FeeManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the main fee management dashboard
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $data = [
            'title' => 'Fee Management Dashboard',
            'total_categories' => FeeCategory::count(),
            'total_structures' => FeeStructure::count(),
            'total_collections' => FeeCollection::where('status', 'paid')->sum('paid_amount'),
            'pending_amount' => FeeCollection::where('status', 'pending')->sum('paid_amount'),
        ];

        return view('admin.fee-management.index', compact('data'));
    }

    /**
     * Fee Categories Management
     */
    public function categories()
    {
        if (!Gate::allows('Dashboard-list')) {
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
                $editBtn = '<a href="' . route('admin.fee-management.categories.edit', $category->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' . $category->id . ')">Delete</button>';
                return $editBtn . ' ' . $deleteBtn;
            })
            ->addColumn('status', function ($category) {
                return $category->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function createCategory()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.categories.create');
    }

    public function storeCategory(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $category = FeeCategory::findOrFail($id);
        return view('admin.fee-management.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.structures.index');
    }

    public function getStructuresData()
    {
        $structures = FeeStructure::with(['academicClass', 'academicSession', 'createdBy', 'feeStructureDetails'])
            ->select(['id', 'name', 'academic_class_id', 'academic_session_id', 'fee_factor_id', 'is_active', 'created_at']);

        return DataTables::of($structures)
            ->addColumn('action', function ($structure) {
                $editBtn = '<a href="' . route('admin.fee-management.structures.edit', $structure->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteStructure(' . $structure->id . ')">Delete</button>';
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
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function createStructure()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $factors = FeeFactor::where('is_active', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.structures.create', compact('classes', 'sessions', 'factors', 'categories'));
    }

    public function storeStructure(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        ]);

        DB::beginTransaction();
        try {
            $structure = FeeStructure::create([
                'name' => $request->name,
                'description' => $request->description,
                'academic_class_id' => $request->class_id,
                'academic_session_id' => $request->session_id,
                'fee_factor_id' => $request->factor_id,
                'is_active' => true,
                'company_id' => auth()->user()->company_id ?? null,
                'branch_id' => auth()->user()->branch_id ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->categories as $category) {
                FeeStructureDetail::create([
                    'fee_structure_id' => $structure->id,
                    'fee_category_id' => $category['category_id'],
                    'amount' => $category['amount'],
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $structure = FeeStructure::with('details')->findOrFail($id);
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $factors = FeeFactor::where('is_active', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.structures.edit', compact('structure', 'classes', 'sessions', 'factors', 'categories'));
    }

    public function updateStructure(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'fee_factor_id' => 'required|exists:fee_factors,id',
            'description' => 'nullable|string',
            'categories' => 'required|array|min:1',
            'categories.*.category_id' => 'required|exists:fee_categories,id',
            'categories.*.amount' => 'required|numeric|min:0',
            'categories.*.due_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $structure = FeeStructure::findOrFail($id);
            $structure->update([
                'name' => $request->name,
                'academic_class_id' => $request->academic_class_id,
                'academic_session_id' => $request->academic_session_id,
                'fee_factor_id' => $request->fee_factor_id,
                'description' => $request->description,
                'updated_by' => auth()->id(),
            ]);

            // Delete existing details
            FeeStructureDetail::where('structure_id', $structure->id)->delete();

            // Create new details
            foreach ($request->categories as $category) {
                FeeStructureDetail::create([
                    'structure_id' => $structure->id,
                    'category_id' => $category['category_id'],
                    'amount' => $category['amount'],
                    'due_date' => $category['due_date'] ?? null,
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
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.collections.index');
    }

    public function getCollectionsData()
    {
        $collections = FeeCollection::with(['student.AcademicClass', 'academicSession', 'details'])
            ->select(['id', 'student_id', 'academic_session_id', 'fee_assignment_id', 'paid_amount', 'status', 'collection_date', 'payment_method', 'created_at']);

        return DataTables::of($collections)
            ->addColumn('action', function ($collection) {
                $viewBtn = '<a href="' . route('admin.fee-management.collections.show', $collection->id) . '" class="btn btn-sm btn-info">View</a>';
                $editBtn = '<a href="' . route('admin.fee-management.collections.edit', $collection->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                return $viewBtn . ' ' . $editBtn;
            })
            ->addColumn('student_name', function ($collection) {
                return $collection->student->fullname ?? 'N/A';
            })
            ->addColumn('class_name', function ($collection) {
                return $collection->student->AcademicClass->name ?? 'N/A';
            })
            ->addColumn('total_amount', function ($collection) {
                return $collection->details->sum('amount') ?? 0;
            })
            ->addColumn('status', function ($collection) {
                $badgeClass = $collection->status == 'paid' ? 'success' : ($collection->status == 'pending' ? 'warning' : 'danger');
                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($collection->status) . '</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function showCollection($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $collection = FeeCollection::with(['student', 'academicClass', 'academicSession', 'details.feeCategory'])
            ->findOrFail($id);

        return view('admin.fee-management.collections.show', compact('collection'));
    }

    public function createCollection()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with(['AcademicClass', 'academicSession'])->get();
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();

        return view('admin.fee-management.collections.create', compact('students', 'classes', 'sessions'));
    }

    public function getStudentsByClass($classId)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with(['AcademicClass', 'academicSession'])
            ->where('class_id', $classId)
            ->get();

        return response()->json([
            'students' => $students->map(function($student) {
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
    }

    public function storeCollection(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
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
            $totalAmount = array_sum(array_column($request->collections, 'amount'));

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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $collection = FeeCollection::with(['student', 'academicClass', 'academicSession', 'details.feeCategory'])
            ->findOrFail($id);
        $students = Students::with(['AcademicClass', 'academicSession'])->get();
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();
        $categories = FeeCategory::where('is_active', 1)->get();

        return view('admin.fee-management.collections.edit', compact('collection', 'students', 'classes', 'sessions', 'categories'));
    }

    public function updateCollection(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.discounts.index');
    }

    public function getDiscountsData()
    {
        $discounts = FeeDiscount::with(['student', 'category', 'createdBy'])
            ->select(['id', 'student_id', 'category_id', 'discount_type', 'discount_value', 'reason', 'is_active', 'created_at']);

        return DataTables::of($discounts)
            ->addColumn('action', function ($discount) {
                $editBtn = '<a href="' . route('admin.fee-management.discounts.edit', $discount->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteDiscount(' . $discount->id . ')">Delete</button>';
                return $editBtn . ' ' . $deleteBtn;
            })
            ->addColumn('student_name', function ($discount) {
                return $discount->student->fullname ?? 'N/A';
            })
            ->addColumn('category_name', function ($discount) {
                return $discount->category->name ?? 'N/A';
            })
            ->addColumn('status', function ($discount) {
                return $discount->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function createDiscount()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with('AcademicClass')->get();
        $categories = FeeCategory::all();
        
        return view('admin.fee-management.discounts.create', compact('students', 'categories'));
    }

    public function storeDiscount(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        FeeDiscount::create([
            'student_id' => $request->student_id,
            'category_id' => $request->category_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'reason' => $request->reason,
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fee-management.discounts')
            ->with('success', 'Discount created successfully!');
    }

    public function editDiscount($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $discount = FeeDiscount::findOrFail($id);
        $students = Students::with('AcademicClass')->get();
        $categories = FeeCategory::all();
        
        return view('admin.fee-management.discounts.edit', compact('discount', 'students', 'categories'));
    }

    public function updateDiscount(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $discount = FeeDiscount::findOrFail($id);
        $discount->update([
            'student_id' => $request->student_id,
            'category_id' => $request->category_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'reason' => $request->reason,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.fee-management.discounts')
            ->with('success', 'Discount updated successfully!');
    }

    public function deleteDiscount($id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();

        return view('admin.fee-management.billing.index', compact('classes', 'sessions'));
    }

    public function getBillingData()
    {
        $billing = FeeBilling::with(['student.AcademicClass', 'academicSession'])
            ->select(['id', 'student_id', 'academic_session_id', 'challan_number', 'total_amount', 'due_date', 'status', 'created_at']);

        return DataTables::of($billing)
            ->addColumn('action', function ($bill) {
                $viewBtn = '<a href="' . route('admin.fee-management.billing.show', $bill->id) . '" class="btn btn-sm btn-info">View</a>';
                $printBtn = '<a href="' . route('admin.fee-management.billing.print', $bill->id) . '" class="btn btn-sm btn-success" target="_blank">Print</a>';
                return $viewBtn . ' ' . $printBtn;
            })
            ->addColumn('student_name', function ($bill) {
                return $bill->student->fullname ?? 'N/A';
            })
            ->addColumn('class_name', function ($bill) {
                return $bill->student->AcademicClass->name ?? 'N/A';
            })
            ->addColumn('status', function ($bill) {
                $status = $bill->status ?? 'pending';
                $badgeClass = 'secondary';
                
                switch($status) {
                    case 'paid':
                        $badgeClass = 'success';
                        break;
                    case 'pending':
                        $badgeClass = 'warning';
                        break;
                    case 'generated':
                        $badgeClass = 'info';
                        break;
                    case 'overdue':
                        $badgeClass = 'danger';
                        break;
                    default:
                        $badgeClass = 'secondary';
                }
                
                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function generateBilling(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'academic_class_id' => 'required|exists:classes,id',
            'academic_session_id' => 'required|exists:acadmeic_sessions,id',
            'billing_month' => 'required|date_format:Y-m',
            'exclude_arrears' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            \Log::info('Billing generation started', [
                'class_id' => $request->academic_class_id,
                'session_id' => $request->academic_session_id,
                'billing_month' => $request->billing_month
            ]);

            // Get students in the specified class and session
            $students = Students::where('class_id', $request->academic_class_id)
                ->where('session_id', $request->academic_session_id)
                ->get();

            \Log::info('Students found: ' . $students->count());
            \Log::info('Students data: ', $students->toArray());

            if ($students->isEmpty()) {
                \Log::info('No students found for class: ' . $request->academic_class_id . ' and session: ' . $request->academic_session_id);
                return redirect()->route('admin.fee-management.billing')
                    ->with('error', 'No students found for the selected class and session.');
            }

            // Get fee structure for the class and session
            $feeStructure = FeeStructure::where('academic_class_id', $request->academic_class_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->where('is_active', 1)
                ->first();

            \Log::info('Fee structure found: ' . ($feeStructure ? 'Yes' : 'No'));

            if (!$feeStructure) {
                \Log::info('No fee structure found for class: ' . $request->academic_class_id . ' and session: ' . $request->academic_session_id);
                return redirect()->route('admin.fee-management.billing')
                    ->with('error', 'No fee structure found for the selected class and session.');
            }

            $billingCount = 0;

            \Log::info('Processing ' . $students->count() . ' students for billing');

            foreach ($students as $student) {
                \Log::info('Processing student: ' . $student->id . ' - ' . $student->fullname);
                
                // Check if billing already exists for this student, session and billing month
                $existingBilling = FeeBilling::where('student_id', $student->id)
                    ->where('academic_session_id', $request->academic_session_id)
                    ->where('billing_month', $request->billing_month)
                    ->first();

                if ($existingBilling) {
                    \Log::info('Billing already exists for student: ' . $student->id);
                    continue; // Skip if billing already exists
                }

                // Calculate total amount from fee structure
                $totalAmount = $feeStructure->feeStructureDetails->sum('amount');
                \Log::info('Total amount calculated: ' . $totalAmount);

                // Generate challan number
                $challanNumber = 'CHL-' . date('Y') . '-' . str_pad($student->id, 6, '0', STR_PAD_LEFT);

                // Create billing record
                $billing = FeeBilling::create([
                    'student_id' => $student->id,
                    'academic_session_id' => $request->academic_session_id,
                    'challan_number' => $challanNumber,
                    'billing_month' => $request->billing_month,
                    'total_amount' => $totalAmount,
                    'bill_date' => now(),
                    'due_date' => now()->addDays(30), // 30 days from now
                    'outstanding_amount' => $totalAmount, // Initially outstanding amount equals total amount
                    'status' => 'generated',
                    'company_id' => auth()->user()->company_id ?? null,
                    'branch_id' => auth()->user()->branch_id ?? null,
                    'created_by' => auth()->id(),
                ]);

                \Log::info('Billing created for student: ' . $student->id . ' with ID: ' . $billing->id);
                $billingCount++;
            }

            DB::commit();

            if ($billingCount > 0) {
                return redirect()->route('admin.fee-management.billing')
                    ->with('success', "Billing generated successfully for {$billingCount} students!");
            } else {
                return redirect()->route('admin.fee-management.billing')
                    ->with('warning', 'No new billing records were created. All students may already have billing for this month.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Billing generation error: ' . $e->getMessage());
            return redirect()->route('admin.fee-management.billing')
                ->with('error', 'Error generating billing: ' . $e->getMessage());
        }
    }

    public function showBilling($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $billing = FeeBilling::with(['student.AcademicClass', 'academicSession'])
            ->findOrFail($id);

        return view('admin.fee-management.billing.show', compact('billing'));
    }

    public function printBilling($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $billing = FeeBilling::with(['student.AcademicClass', 'academicSession'])
            ->findOrFail($id);

        return view('admin.fee-management.billing.print', compact('billing'));
    }

    /**
     * Reports
     */
    public function reports()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.fee-management.reports.index');
    }

    public function incomeReport(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $classId = $request->class_id;
        $sessionId = $request->session_id;

        $query = FeeBilling::where('status', '!=', 'paid')
            ->with(['student.academicClass', 'academicSession']);

        if ($classId) {
            $query->whereHas('student', function($q) use ($classId) {
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
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $student = Students::with(['academicClass', 'academicSession'])->findOrFail($studentId);
        
        $collections = FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.category'])
            ->orderBy('collection_date', 'desc')
            ->get();

        $adjustments = FeeAdjustment::where('student_id', $studentId)
            ->orderBy('adjustment_date', 'desc')
            ->get();

        return view('admin.fee-management.reports.student-ledger', compact('student', 'collections', 'adjustments'));
    }
}

