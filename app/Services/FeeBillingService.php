<?php

namespace App\Services;

use App\Models\Fee\FeeBilling;
use App\Models\Fee\FeeStructure;
use App\Models\Fee\FeeStructureDetail;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeFactor;
use App\Models\Student\Students;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FeeBillingService
{
    /**
     * Generate billing for multiple students
     */
    public function generateBulkBilling($classId, $sessionId, $billingMonth, $excludeArrears = false)
    {
        DB::beginTransaction();
        try {
            $students = Students::where('class_id', $classId)
                ->where('session_id', $sessionId)
                ->where('is_active', 1)
                ->get();

            $billingRecords = [];
            $class = AcademicClass::find($classId);
            $session = AcademicSession::find($sessionId);

            foreach ($students as $student) {
                $billing = $this->generateStudentBilling($student, $class, $session, $billingMonth, $excludeArrears);
                if ($billing) {
                    $billingRecords[] = $billing;
                }
            }

            DB::commit();
            return $billingRecords;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate billing for a single student
     */
    public function generateStudentBilling($student, $class, $session, $billingMonth, $excludeArrears = false)
    {
        // Get student's fee structure
        $feeStructure = FeeStructure::where('class_id', $class->id)
            ->where('session_id', $session->id)
            ->where('is_active', 1)
            ->first();

        if (!$feeStructure) {
            return null; // No fee structure found
        }

        // Calculate total amount
        $totalAmount = $this->calculateStudentFeeAmount($feeStructure, $billingMonth);
        
        if ($totalAmount <= 0) {
            return null; // No amount to bill
        }

        // Add arrears if not excluded
        if (!$excludeArrears) {
            $arrears = $this->getStudentArrears($student->id);
            $totalAmount += $arrears;
        }

        // Generate challan number
        $challanNumber = $this->generateChallanNumber($class, $billingMonth);

        // Create billing record
        $billing = FeeBilling::create([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'session_id' => $session->id,
            'challan_number' => $challanNumber,
            'total_amount' => $totalAmount,
            'outstanding_amount' => $totalAmount, // Initially same as total
            'due_date' => $this->calculateDueDate($billingMonth),
            'status' => 'pending',
            'billing_month' => $billingMonth,
            'created_by' => Auth::id(),
        ]);

        // Apply discounts with validity check
        $billing->applyDiscounts();
        
        return $billing;
    }

    /**
     * Calculate student fee amount
     */
    private function calculateStudentFeeAmount($feeStructure, $billingMonth)
    {
        $totalAmount = 0;
        $month = date('n', strtotime($billingMonth . '-01'));
        
        $structureDetails = FeeStructureDetail::where('structure_id', $feeStructure->id)->get();
        
        foreach ($structureDetails as $detail) {
            $category = FeeCategory::find($detail->category_id);
            $amount = $this->applyFeeFactor($detail->amount, $feeStructure->factor_id, $billingMonth, $category->type, $month);
            $totalAmount += $amount;
        }
        
        return $totalAmount;
    }

    /**
     * Apply fee factor based on month and category type
     */
    private function applyFeeFactor($baseAmount, $factorId, $billingMonth, $categoryType, $month)
    {
        $factor = FeeFactor::find($factorId);
        
        // June/July logic for 10-month factor
        if (($month == 6 || $month == 7) && $factor->name == '10-month') {
            return 0; // Skip billing for 10-month factor in June/July
        }
        
        // Apply factor multiplier
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
     * Generate unique challan number
     */
    private function generateChallanNumber($class, $billingMonth)
    {
        $campusCode = $class->code ?? 'CAMP';
        $year = date('Y', strtotime($billingMonth . '-01'));
        $month = date('m', strtotime($billingMonth . '-01'));
        
        $prefix = $campusCode . $year . $month;
        
        $lastBilling = FeeBilling::where('challan_number', 'like', $prefix . '%')
            ->orderBy('challan_number', 'desc')
            ->first();
        
        if ($lastBilling) {
            $lastNumber = intval(substr($lastBilling->challan_number, -4));
            $billNumber = $lastNumber + 1;
        } else {
            $billNumber = 1;
        }
        
        return $prefix . str_pad($billNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate due date (15th of the month)
     */
    private function calculateDueDate($billingMonth)
    {
        return date('Y-m-15', strtotime($billingMonth . '-01'));
    }

    /**
     * Mark billing as paid
     */
    public function markBillingAsPaid($billingId, $paymentMethod = 'cash', $remarks = null)
    {
        $billing = FeeBilling::findOrFail($billingId);
        
        $billing->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'remarks' => $remarks,
            'updated_by' => Auth::id(),
        ]);
        
        return $billing;
    }

    /**
     * Get billing statistics
     */
    public function getBillingStats($classId = null, $sessionId = null, $month = null)
    {
        $query = FeeBilling::query();
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }
        
        if ($month) {
            $query->where('billing_month', $month);
        }
        
        return [
            'total_bills' => $query->count(),
            'paid_bills' => $query->where('status', 'paid')->count(),
            'pending_bills' => $query->where('status', 'pending')->count(),
            'overdue_bills' => $query->where('status', 'overdue')->count(),
            'total_amount' => $query->sum('total_amount'),
            'paid_amount' => $query->where('status', 'paid')->sum('total_amount'),
            'pending_amount' => $query->where('status', 'pending')->sum('total_amount'),
        ];
    }

    /**
     * Update overdue bills
     */
    public function updateOverdueBills()
    {
        $overdueBills = FeeBilling::where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->get();
        
        foreach ($overdueBills as $bill) {
            $bill->update(['status' => 'overdue']);
        }
        
        return $overdueBills->count();
    }

    /**
     * Get billing history for student
     */
    public function getStudentBillingHistory($studentId, $limit = 10)
    {
        return FeeBilling::where('student_id', $studentId)
            ->with(['academicClass', 'academicSession'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Print billing challan
     */
    public function getBillingForPrint($billingId)
    {
        return FeeBilling::with([
            'student',
            'academicClass',
            'academicSession'
        ])->findOrFail($billingId);
    }
}

