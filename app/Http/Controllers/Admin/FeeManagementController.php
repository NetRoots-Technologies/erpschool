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
use App\Models\Academic\ActiveSession;
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
        $structures = FeeStructure::with(['academicClass', 'academicSession', 'createdBy', 'feeStructureDetails'])
            ->select(['id', 'name', 'academic_class_id', 'academic_session_id', 'fee_factor_id', 'is_active', 'created_at']);

        return DataTables::of($structures)
            ->addColumn('action', function ($structure) {

                $editBtn = "";
                $deleteBtn = "";

                if(Gate::allows('fee-structures-edit')){
                $editBtn .= '<a href="' . route('admin.fee-management.structures.edit', $structure->id) . '" class="btn btn-sm btn-primary">Edit</a>';

                }

                if(Gate::allows('fee-structures-delete')){
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
            ->rawColumns(['action', 'status'])
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

        return view('admin.fee-management.structures.edit', compact('structure', 'classes', 'sessions', 'factors', 'categories'));
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

                if(Gate::allows('fee-collections-view')){
                $viewBtn .= '<a href="' . route('admin.fee-management.collections.show', $collection->id) . '" class="btn btn-sm btn-info">View</a>';

                }

                if(Gate::allows('fee-collections-edit')){
                $editBtn .= '<a href="' . route('admin.fee-management.collections.edit', $collection->id) . '" class="btn btn-sm btn-primary">Edit</a>';

                }
                return $viewBtn . ' ' . $editBtn;
            })
            ->addColumn('student_name', function ($collection) {
                return $collection->student->fullname ?? 'N/A';
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
            ->rawColumns(['action'])
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
            $totalDiscount = $discounts->sum(function($discount) use ($collection) {
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

    public function getStudentsByClass($classId)
    {
        if (!Gate::allows('fee-collections-create')) {
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

    public function getSessionsByClass($classId)
    {
       

        // Get sessions assigned to this class through ActiveSession table
        $sessions = ActiveSession::with('academicSession')
            ->where('class_id', $classId)
            ->where('status', 1)
            ->whereHas('academicSession', function($query) {
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
            'sessions' => $sessions->map(function($session) {
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
                    $billing->status = 'partial';
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
            $totalDiscount = $discounts->sum(function($discount) use ($collection) {
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
            $newOutstandingAmount = $finalAmount - $newPaidAmount;

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

    public function getDiscountsData()
    {
        $discounts = FeeDiscount::with(['student', 'category', 'createdBy'])
            ->select(['id', 'student_id', 'category_id', 'discount_type', 'discount_value', 'reason', 'valid_from', 'valid_to', 'created_at']);

        return DataTables::of($discounts)
            ->addColumn('action', function ($discount) {

                $deleteBtn = ''; 
                $editBtn = '';

                if(Gate::allows('fee-discount-edit')){
                  $editBtn = '<a href="' . route('admin.fee-management.discounts.edit', $discount->id) . '" class="btn btn-sm btn-primary">Edit</a>';

                }

                if(Gate::allows('fee-discount-delete')){
                $deleteBtn = '<button class="btn btn-sm btn-danger" onclick="deleteDiscount(' . $discount->id . ')">Delete</button>';

                }
                

                return $editBtn . ' ' . $deleteBtn;
            })
            ->addColumn('student_name', function ($discount) {
                return $discount->student->fullname ?? 'N/A';
            })
            ->addColumn('category_name', function ($discount) {
                return $discount->category->name ?? 'N/A';
            })
            ->rawColumns(['action'])
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
            'reason' => 'required|string|max:255',
            'valid_from_month' => 'required|date_format:Y-m',
            'valid_to_month' => 'required|date_format:Y-m|after_or_equal:valid_from_month'
        ]);

        FeeDiscount::create([
            'student_id' => $request->student_id,
            'category_id' => $request->category_id,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
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
            'valid_to_month' => 'required|date_format:Y-m|after_or_equal:valid_from_month'
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
            ->addColumn('class_name', function ($bill) {
                return $bill->student->AcademicClass->name ?? 'N/A';
            })
            ->addColumn('status', function ($bill) {
                // Determine correct status based on paid amount
                $paidAmount = $bill->paid_amount ?? 0;
                $finalAmount = $bill->getFinalAmount();
                $outstandingAmount = $finalAmount - $paidAmount;
                
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
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function generateBilling(Request $request)
    {
        if (!Gate::allows('fee-billing-create')) {
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

            // Check if the class-session combination is active
            $activeSession = ActiveSession::where('class_id', $request->academic_class_id)
                ->where('session_id', $request->academic_session_id)
                ->where('status', 1)
                ->first();

            if (!$activeSession) {
                \Log::info('No active session found for class: ' . $request->academic_class_id . ' and session: ' . $request->academic_session_id);
                return redirect()->route('admin.fee-management.billing')
                    ->with('error', 'No active session found for the selected class and session combination.');
            }

            // Get students in the specified class (session relationship is managed through ActiveSession)
            $students = Students::where('class_id', $request->academic_class_id)
                ->where('is_active', 1)
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
                $totalAmount = $feeStructure->feeStructureDetails()->sum('amount');
                \Log::info('Total amount calculated: ' . $totalAmount);

                // Generate unique challan number
                $challanNumber = $this->generateUniqueChallanNumber($request->billing_month);

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

                // Apply discounts automatically
                $billing->applyDiscounts();

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


    public function printBilling($id)
    {
        if (!Gate::allows('fee-billing-print')) {
            abort(403, 'Unauthorized access');
        }

        $billing = FeeBilling::with(['student.AcademicClass' ,'student.branch','academicSession'])
            ->findOrFail($id);

        // Load applicable discounts for this billing
        $applicableDiscounts = $billing->getApplicableDiscounts()->load('category');

        // Load transport fees for the student
        $transportFees = [];
        $totalTransportFee = 0;
        if ($billing->student) {
            $transportFees = $billing->student->transportations()
                ->where('status', 'active')
                ->with(['vehicle', 'route'])
                ->get();
            
            $totalTransportFee = $transportFees->sum('monthly_charges');
        }
        return view('admin.fee-management.billing.print', compact('billing', 'applicableDiscounts', 'transportFees', 'totalTransportFee'));
    }

    /**
     * Pay Challan Page
     */
    public function payChallan()
    {
        if (!Gate::allows('pay-challan')) {
            abort(403, 'Unauthorized access');
        }

        $classes = AcademicClass::where('status', 1)->get();
        
        return view('admin.fee-management.collections.pay-challan', compact('classes'));
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
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($challan) {
                // Determine correct status based on paid amount
                $paidAmount = $challan->paid_amount ?? 0;
                $finalAmount = $challan->getFinalAmount();
                $outstandingAmount = $finalAmount - $paidAmount;
                
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
                    'created_at' => $challan->created_at
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
            
            foreach($applicableDiscounts as $discount) {
                $discountAmount = $discount->calculateDiscount($challan->total_amount);
                $totalDiscount += $discountAmount;
                
                $discounts[] = [
                    'category_name' => $discount->category->name ?? 'General',
                    'discount_type' => $discount->discount_type,
                    'discount_value' => $discount->discount_value,
                    'discount_amount' => $discountAmount
                ];
            }
            
            $finalAmount = $challan->total_amount - $totalDiscount;
            
            return response()->json([
                'discounts' => $discounts,
                'totalDiscount' => $totalDiscount,
                'finalAmount' => $finalAmount
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
            
            foreach($transportFees as $transport) {
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
            $maxPayableAmount = $finalAmount - $currentPaidAmount;
            
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
            $newOutstandingAmount = $finalAmount - $newPaidAmount;
            
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
            
            $challan->save();

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

        return view('admin.fee-management.reports.index');
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
        if (!Gate::allows('fee-report-ledger')) {
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

