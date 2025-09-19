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
use App\Models\Academic\AcademicSession;
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

        FeeCategory::create([
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
        $structures = FeeStructure::with(['academicClass', 'academicSession', 'createdBy'])
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
            ->rawColumns(['action'])
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

        $request->validate([
            'name' => 'required|string|max:191',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'fee_factor_id' => 'required|exists:fee_factors,id',
            'is_active' => 'boolean',
            'categories' => 'required|array',
            'categories.*.category_id' => 'required|exists:fee_categories,id',
            'categories.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $structure = FeeStructure::create([
                'name' => $request->name,
                'academic_class_id' => $request->academic_class_id,
                'academic_session_id' => $request->academic_session_id,
                'fee_factor_id' => $request->fee_factor_id,
                'is_active' => $request->has('is_active'),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->categories as $category) {
                FeeStructureDetail::create([
                    'structure_id' => $structure->id,
                    'category_id' => $category['category_id'],
                    'amount' => $category['amount'],
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.fee-management.structures')
                ->with('success', 'Fee structure created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
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
        $collections = FeeCollection::with(['student', 'academicClass', 'academicSession'])
            ->select(['id', 'student_id', 'academic_session_id', 'fee_assignment_id', 'paid_amount', 'status', 'collection_date', 'created_at']);

        return DataTables::of($collections)
            ->addColumn('action', function ($collection) {
                $viewBtn = '<a href="' . route('admin.fee-management.collections.show', $collection->id) . '" class="btn btn-sm btn-info">View</a>';
                $editBtn = '<a href="' . route('admin.fee-management.collections.edit', $collection->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                return $viewBtn . ' ' . $editBtn;
            })
            ->addColumn('student_name', function ($collection) {
                return $collection->student->name ?? 'N/A';
            })
            ->addColumn('class_name', function ($collection) {
                return $collection->academicClass->name ?? 'N/A';
            })
            ->addColumn('status', function ($collection) {
                $badgeClass = $collection->status == 'paid' ? 'success' : ($collection->status == 'pending' ? 'warning' : 'danger');
                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($collection->status) . '</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function createCollection()
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $students = Students::with(['academicClass', 'academicSession'])->get();
        $classes = AcademicClass::where('status', 1)->get();
        $sessions = AcademicSession::where('status', 1)->get();

        return view('admin.fee-management.collections.create', compact('students', 'classes', 'sessions'));
    }

    public function storeCollection(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
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
                'academic_session_id' => $request->session_id,
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
                return $discount->student->name ?? 'N/A';
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

        $students = Students::with('academicClass')->get();
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
        $students = Students::with('academicClass')->get();
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

        return view('admin.fee-management.billing.index');
    }

    public function getBillingData()
    {
        $billing = FeeBilling::with(['student.academicClass', 'academicSession'])
            ->select(['id', 'student_id', 'academic_session_id', 'challan_number', 'total_amount', 'due_date', 'status', 'created_at']);

        return DataTables::of($billing)
            ->addColumn('action', function ($bill) {
                $viewBtn = '<a href="' . route('admin.fee-management.billing.show', $bill->id) . '" class="btn btn-sm btn-info">View</a>';
                $printBtn = '<a href="' . route('admin.fee-management.billing.print', $bill->id) . '" class="btn btn-sm btn-success" target="_blank">Print</a>';
                return $viewBtn . ' ' . $printBtn;
            })
            ->addColumn('student_name', function ($bill) {
                return $bill->student->name ?? 'N/A';
            })
            ->addColumn('class_name', function ($bill) {
                return $bill->student->academicClass->name ?? 'N/A';
            })
            ->addColumn('status', function ($bill) {
                $badgeClass = $bill->status == 'paid' ? 'success' : ($bill->status == 'pending' ? 'warning' : 'overdue');
                return '<span class="badge badge-' . $badgeClass . '">' . ucfirst($bill->status) . '</span>';
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
            'academic_class_id' => 'required|exists:academic_classes,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'billing_month' => 'required|date_format:Y-m',
            'exclude_arrears' => 'boolean',
        ]);

        // Generate billing logic here
        // This would involve creating FeeBilling records for all students in the class
        
        return redirect()->route('admin.fee-management.billing')
            ->with('success', 'Billing generated successfully!');
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

