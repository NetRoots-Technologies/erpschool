<?php

namespace App\Services;

use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeDiscount;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FeeCollectionService
{
    /**
     * Record fee collection
     */
    public function recordCollection($data)
    {
        DB::beginTransaction();
        try {
            $totalAmount = array_sum(array_column($data['collections'], 'amount'));
            
            // Apply discounts if any
            $discountedAmount = $this->applyDiscounts($data['student_id'], $totalAmount, $data['collections']);
            
            $collection = FeeCollection::create([
                'student_id' => $data['student_id'],
                'class_id' => $data['class_id'],
                'session_id' => $data['session_id'],
                'total_amount' => $totalAmount,
                'paid_amount' => $discountedAmount,
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

            // Update student billing status if applicable
            $this->updateStudentBillingStatus($data['student_id'], $collection->id);

            DB::commit();
            return $collection;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Apply discounts to collection
     */
    private function applyDiscounts($studentId, $totalAmount, $collections)
    {
        $discounts = FeeDiscount::where('student_id', $studentId)
            ->where('is_active', 1)
            ->get();

        $discountedAmount = $totalAmount;

        foreach ($discounts as $discount) {
            if ($discount->discount_type == 'percentage') {
                $discountValue = ($totalAmount * $discount->discount_value) / 100;
            } else {
                $discountValue = $discount->discount_value;
            }

            $discountedAmount -= $discountValue;
        }

        return max(0, $discountedAmount); // Ensure amount doesn't go negative
    }

    /**
     * Update student billing status
     */
    private function updateStudentBillingStatus($studentId, $collectionId)
    {
        // Update any pending bills for this student
        // This would depend on your billing logic
    }

    /**
     * Get collection statistics
     */
    public function getCollectionStats($fromDate = null, $toDate = null)
    {
        $query = FeeCollection::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('collection_date', [$fromDate, $toDate]);
        }

        return [
            'total_collections' => $query->count(),
            'total_amount' => $query->sum('total_amount'),
            'paid_amount' => $query->where('status', 'paid')->sum('paid_amount'),
            'pending_amount' => $query->where('status', 'pending')->sum('total_amount'),
            'cash_collections' => $query->where('payment_method', 'cash')->sum('paid_amount'),
            'bank_transfers' => $query->where('payment_method', 'bank_transfer')->sum('paid_amount'),
            'cheque_collections' => $query->where('payment_method', 'cheque')->sum('paid_amount'),
        ];
    }

    /**
     * Get daily collection report
     */
    public function getDailyCollectionReport($date)
    {
        return FeeCollection::whereDate('collection_date', $date)
            ->with(['student', 'academicClass'])
            ->get();
    }

    /**
     * Get monthly collection report
     */
    public function getMonthlyCollectionReport($month, $year)
    {
        $startDate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return FeeCollection::whereBetween('collection_date', [$startDate, $endDate])
            ->with(['student', 'academicClass'])
            ->get();
    }

    /**
     * Get collection by payment method
     */
    public function getCollectionsByPaymentMethod($fromDate, $toDate)
    {
        return FeeCollection::whereBetween('collection_date', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(paid_amount) as total')
            ->groupBy('payment_method')
            ->get();
    }

    /**
     * Get student collection history
     */
    public function getStudentCollectionHistory($studentId, $limit = 10)
    {
        return FeeCollection::where('student_id', $studentId)
            ->with(['feeCollectionDetails.category'])
            ->orderBy('collection_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Rollback collection
     */
    public function rollbackCollection($collectionId, $reason)
    {
        DB::beginTransaction();
        try {
            $collection = FeeCollection::findOrFail($collectionId);
            
            // Create adjustment record
            $this->createCollectionRollbackAdjustment($collection, $reason);
            
            // Mark collection as rolled back
            $collection->update([
                'status' => 'rolled_back',
                'remarks' => $collection->remarks . ' [ROLLED BACK: ' . $reason . ']',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return $collection;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Create rollback adjustment
     */
    private function createCollectionRollbackAdjustment($collection, $reason)
    {
        // This would create an adjustment record to reverse the collection
        // Implementation depends on your adjustment system
    }

    /**
     * Get outstanding collections
     */
    public function getOutstandingCollections($classId = null, $sessionId = null)
    {
        $query = FeeCollection::where('status', 'pending');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->with(['student', 'academicClass'])->get();
    }

    /**
     * Calculate fine for overdue payment
     */
    public function calculateFine($dueDate, $collectionDate = null)
    {
        $collectionDate = $collectionDate ?: now();
        $dueDate = \Carbon\Carbon::parse($dueDate);
        $collectionDate = \Carbon\Carbon::parse($collectionDate);

        if ($collectionDate->gt($dueDate)) {
            return 1500; // Fixed fine amount as per requirements
        }

        return 0;
    }

    /**
     * Get collection summary for dashboard
     */
    public function getCollectionSummary()
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        return [
            'today_collections' => FeeCollection::whereDate('collection_date', $today)
                ->where('status', 'paid')
                ->sum('paid_amount'),
            'monthly_collections' => FeeCollection::where('collection_date', 'like', $thisMonth . '%')
                ->where('status', 'paid')
                ->sum('paid_amount'),
            'pending_collections' => FeeCollection::where('status', 'pending')
                ->sum('total_amount'),
            'total_students_with_dues' => FeeCollection::where('status', 'pending')
                ->distinct('student_id')
                ->count('student_id'),
        ];
    }
}

