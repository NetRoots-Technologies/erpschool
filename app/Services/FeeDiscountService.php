<?php

namespace App\Services;

use App\Models\Fee\FeeDiscount;
use App\Models\Fee\FeeCategory;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FeeDiscountService
{
    /**
     * Create individual discount
     */
    public function createIndividualDiscount($data)
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
     * Create bulk discount for multiple students
     */
    public function createBulkDiscount($data)
    {
        DB::beginTransaction();
        try {
            $discounts = [];
            
            foreach ($data['student_ids'] as $studentId) {
                $discounts[] = FeeDiscount::create([
                    'student_id' => $studentId,
                    'category_id' => $data['category_id'],
                    'discount_type' => $data['discount_type'],
                    'discount_value' => $data['discount_value'],
                    'reason' => $data['reason'] ?? null,
                    'is_active' => $data['is_active'] ?? true,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return $discounts;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update discount
     */
    public function updateDiscount($id, $data)
    {
        $discount = FeeDiscount::findOrFail($id);
        
        $discount->update([
            'category_id' => $data['category_id'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'reason' => $data['reason'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'updated_by' => Auth::id(),
        ]);
        
        return $discount;
    }

    /**
     * Delete discount
     */
    public function deleteDiscount($id)
    {
        $discount = FeeDiscount::findOrFail($id);
        $discount->delete();
        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscountAmount($studentId, $categoryId, $baseAmount)
    {
        $discounts = FeeDiscount::where('student_id', $studentId)
            ->where('category_id', $categoryId)
            ->where('is_active', 1)
            ->get();

        $totalDiscount = 0;

        foreach ($discounts as $discount) {
            if ($discount->discount_type == 'percentage') {
                $discountAmount = ($baseAmount * $discount->discount_value) / 100;
            } else {
                $discountAmount = $discount->discount_value;
            }

            $totalDiscount += $discountAmount;
        }

        // Ensure discount doesn't exceed the base amount
        return min($totalDiscount, $baseAmount);
    }

    /**
     * Get student discounts
     */
    public function getStudentDiscounts($studentId, $categoryId = null)
    {
        $query = FeeDiscount::where('student_id', $studentId)
            ->where('is_active', 1)
            ->with(['category']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    /**
     * Get discount statistics
     */
    public function getDiscountStats($fromDate = null, $toDate = null)
    {
        $query = FeeDiscount::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        return [
            'total_discounts' => $query->count(),
            'active_discounts' => $query->where('is_active', 1)->count(),
            'inactive_discounts' => $query->where('is_active', 0)->count(),
            'percentage_discounts' => $query->where('discount_type', 'percentage')->count(),
            'fixed_discounts' => $query->where('discount_type', 'fixed')->count(),
        ];
    }

    /**
     * Get discounts by category
     */
    public function getDiscountsByCategory($categoryId)
    {
        return FeeDiscount::where('category_id', $categoryId)
            ->where('is_active', 1)
            ->with(['student', 'category'])
            ->get();
    }

    /**
     * Get discounts by student
     */
    public function getDiscountsByStudent($studentId)
    {
        return FeeDiscount::where('student_id', $studentId)
            ->where('is_active', 1)
            ->with(['category'])
            ->get();
    }

    /**
     * Apply discount to fee collection
     */
    public function applyDiscountToCollection($studentId, $collections)
    {
        $discountedCollections = [];

        foreach ($collections as $collection) {
            $categoryId = $collection['category_id'];
            $baseAmount = $collection['amount'];
            
            $discountAmount = $this->calculateDiscountAmount($studentId, $categoryId, $baseAmount);
            $discountedAmount = $baseAmount - $discountAmount;

            $discountedCollections[] = [
                'category_id' => $categoryId,
                'amount' => $baseAmount,
                'discount_amount' => $discountAmount,
                'discounted_amount' => $discountedAmount,
            ];
        }

        return $discountedCollections;
    }

    /**
     * Get discount summary for student
     */
    public function getStudentDiscountSummary($studentId)
    {
        $discounts = $this->getStudentDiscounts($studentId);
        
        $summary = [
            'total_discounts' => $discounts->count(),
            'categories' => []
        ];

        foreach ($discounts as $discount) {
            $categoryName = $discount->category->name;
            
            if (!isset($summary['categories'][$categoryName])) {
                $summary['categories'][$categoryName] = [
                    'count' => 0,
                    'total_value' => 0,
                    'type' => $discount->discount_type
                ];
            }
            
            $summary['categories'][$categoryName]['count']++;
            $summary['categories'][$categoryName]['total_value'] += $discount->discount_value;
        }

        return $summary;
    }

    /**
     * Validate discount data
     */
    public function validateDiscountData($data)
    {
        $errors = [];

        if ($data['discount_type'] == 'percentage' && ($data['discount_value'] < 1 || $data['discount_value'] > 100)) {
            $errors[] = 'Percentage discount must be between 1 and 100';
        }

        if ($data['discount_type'] == 'fixed' && $data['discount_value'] <= 0) {
            $errors[] = 'Fixed amount discount must be greater than 0';
        }

        // Check if student exists
        if (!Students::find($data['student_id'])) {
            $errors[] = 'Selected student does not exist';
        }

        // Check if category exists
        if (!FeeCategory::find($data['category_id'])) {
            $errors[] = 'Selected fee category does not exist';
        }

        return $errors;
    }

    /**
     * Get active discounts for bulk operations
     */
    public function getActiveDiscountsForBulk($studentIds, $categoryId)
    {
        return FeeDiscount::whereIn('student_id', $studentIds)
            ->where('category_id', $categoryId)
            ->where('is_active', 1)
            ->with(['student', 'category'])
            ->get();
    }
}

