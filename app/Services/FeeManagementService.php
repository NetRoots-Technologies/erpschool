<?php

namespace App\Services;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FeeManagementService
{
    /**
     * Create a new fee category
     */
    public function createFeeCategory($data)
    {
        return FeeCategory::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type'],
            'is_mandatory' => $data['is_mandatory'] ?? false,
            'affects_financials' => $data['affects_financials'] ?? true,
            'is_active' => $data['is_active'] ?? true,
            'company_id' => Auth::user()->company_id ?? null,
            'branch_id' => Auth::user()->branch_id ?? null,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Update a fee category
     */
    public function updateFeeCategory($id, $data)
    {
        $category = FeeCategory::findOrFail($id);
        $category->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type'],
            'is_mandatory' => $data['is_mandatory'] ?? false,
            'affects_financials' => $data['affects_financials'] ?? true,
            'is_active' => $data['is_active'] ?? true,
            'updated_by' => Auth::id(),
        ]);
        return $category;
    }

    /**
     * Create a fee structure
     */
    public function createFeeStructure($data)
    {
        DB::beginTransaction();
        try {
            $structure = FeeStructure::create([
                'name' => $data['name'],
                'class_id' => $data['class_id'],
                'session_id' => $data['session_id'],
                'factor_id' => $data['factor_id'],
                'is_active' => $data['is_active'] ?? true,
                'created_by' => Auth::id(),
            ]);

            // Create structure details
            foreach ($data['categories'] as $category) {
                FeeStructureDetail::create([
                    'structure_id' => $structure->id,
                    'category_id' => $category['category_id'],
                    'amount' => $category['amount'],
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return $structure;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Record fee collection
     */
    public function recordFeeCollection($data)
    {
        DB::beginTransaction();
        try {
            $totalAmount = array_sum(array_column($data['collections'], 'amount'));

            $collection = FeeCollection::create([
                'student_id' => $data['student_id'],
                'class_id' => $data['class_id'],
                'session_id' => $data['session_id'],
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
                'status' => 'paid',
                'collection_date' => $data['collection_date'],
                'payment_method' => $data['payment_method'],
                'remarks' => $data['remarks'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Create collection details
            foreach ($data['collections'] as $collectionDetail) {
                FeeCollectionDetail::create([
                    'collection_id' => $collection->id,
                    'category_id' => $collectionDetail['category_id'],
                    'amount' => $collectionDetail['amount'],
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return $collection;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate fee billing for a class
     */
    public function generateFeeBilling($classId, $sessionId, $billingMonth, $excludeArrears = false)
    {
        DB::beginTransaction();
        try {
            $students = Students::where('class_id', $classId)
                ->where('session_id', $sessionId)
                ->where('is_active', 1)
                ->get();

            $billingRecords = [];

            foreach ($students as $student) {
                // Get student's fee structure
                $feeStructure = $this->getStudentFeeStructure($student->id, $classId, $sessionId);
                
                if (!$feeStructure) {
                    continue; // Skip if no fee structure assigned
                }

                // Calculate total amount
                $totalAmount = $this->calculateFeeAmount($feeStructure, $billingMonth);
                
                // Add arrears if not excluded
                if (!$excludeArrears) {
                    $arrears = $this->getStudentArrears($student->id);
                    $totalAmount += $arrears;
                }

                // Generate challan number
                $challanNumber = $this->generateChallanNumber($classId, $billingMonth);

                // Create billing record
                $billing = FeeBilling::create([
                    'student_id' => $student->id,
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'challan_number' => $challanNumber,
                    'total_amount' => $totalAmount,
                    'due_date' => $this->calculateDueDate($billingMonth),
                    'status' => 'pending',
                    'billing_month' => $billingMonth,
                    'created_by' => Auth::id(),
                ]);

                $billingRecords[] = $billing;
            }

            DB::commit();
            return $billingRecords;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get student's fee structure
     */
    private function getStudentFeeStructure($studentId, $classId, $sessionId)
    {
        return FeeStructure::where('class_id', $classId)
            ->where('session_id', $sessionId)
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Calculate fee amount based on structure and month
     */
    private function calculateFeeAmount($feeStructure, $billingMonth)
    {
        $totalAmount = 0;
        
        $structureDetails = FeeStructureDetail::where('structure_id', $feeStructure->id)->get();
        
        foreach ($structureDetails as $detail) {
            $category = FeeCategory::find($detail->category_id);
            
            // Apply factor based on billing month and category type
            $amount = $this->applyFeeFactor($detail->amount, $feeStructure->factor_id, $billingMonth, $category->type);
            $totalAmount += $amount;
        }
        
        return $totalAmount;
    }

    /**
     * Apply fee factor based on month and category type
     */
    private function applyFeeFactor($baseAmount, $factorId, $billingMonth, $categoryType)
    {
        $factor = FeeFactor::find($factorId);
        $month = date('n', strtotime($billingMonth . '-01'));
        
        // June/July logic
        if ($month == 6 || $month == 7) {
            if ($factor->name == '10-month') {
                return 0; // Skip for 10-month factor
            }
        }
        
        return $baseAmount * $factor->multiplier;
    }

    /**
     * Get student arrears
     */
    private function getStudentArrears($studentId)
    {
        return FeeBilling::where('student_id', $studentId)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');
    }

    /**
     * Generate challan number
     */
    private function generateChallanNumber($classId, $billingMonth)
    {
        $class = AcademicClass::find($classId);
        $campusCode = $class->code ?? 'CAMP';
        $year = date('Y', strtotime($billingMonth . '-01'));
        $month = date('m', strtotime($billingMonth . '-01'));
        
        $lastBilling = FeeBilling::where('challan_number', 'like', $campusCode . $year . $month . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $billNumber = $lastBilling ? 
            (intval(substr($lastBilling->challan_number, -4)) + 1) : 1;
        
        return $campusCode . $year . $month . str_pad($billNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate due date
     */
    private function calculateDueDate($billingMonth)
    {
        $dueDate = date('Y-m-15', strtotime($billingMonth . '-01'));
        return $dueDate;
    }

    /**
     * Get income report data
     */
    public function getIncomeReport($fromDate, $toDate)
    {
        return FeeCollection::whereBetween('collection_date', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->with(['student', 'academicClass'])
            ->get();
    }

    /**
     * Get outstanding dues
     */
    public function getOutstandingDues($classId = null, $sessionId = null)
    {
        $query = FeeBilling::where('status', '!=', 'paid')
            ->with(['student', 'academicClass', 'academicSession']);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Get student ledger
     */
    public function getStudentLedger($studentId)
    {
        $collections = FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.category'])
            ->orderBy('collection_date', 'desc')
            ->get();

        $adjustments = FeeAdjustment::where('student_id', $studentId)
            ->orderBy('adjustment_date', 'desc')
            ->get();

        return [
            'collections' => $collections,
            'adjustments' => $adjustments
        ];
    }

    /**
     * Create fee discount
     */
    public function createFeeDiscount($data)
    {
        return FeeDiscount::create([
            'student_id' => $data['student_id'],
            'category_id' => $data['category_id'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'reason' => $data['reason'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Create fee adjustment
     */
    public function createFeeAdjustment($data)
    {
        return FeeAdjustment::create([
            'student_id' => $data['student_id'],
            'adjustment_type' => $data['adjustment_type'],
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'adjustment_date' => $data['adjustment_date'],
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        return [
            'total_categories' => FeeCategory::count(),
            'total_structures' => FeeStructure::count(),
            'total_collections' => FeeCollection::where('status', 'paid')->sum('paid_amount'),
            'pending_amount' => FeeCollection::where('status', 'pending')->sum('total_amount'),
        ];
    }
}

