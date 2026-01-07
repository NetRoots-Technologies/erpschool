<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Fee\FeeBilling;
use App\Models\Admin\Biling;
use App\Models\Student\Students;

class EbillingApiController extends Controller
{
    /**
     * Bill Inquiry Endpoint
     * POST /eBillingapi/getpaymentinfo
     * 
     * This endpoint is used to get bill details for MCB payment gateway
     */
    public function getPaymentInfo(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('MCB Bill Inquiry Request', [
                'request' => $request->all(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Validate request parameters according to spec
            $validated = $request->validate([
                'consumernumber' => 'required|string|max:60',
                'institutioncode' => 'required|string|max:5',
                'reserved' => 'nullable|string'
            ]);

            $consumerNumber = trim($validated['consumernumber']);
            $institutionCode = trim($validated['institutioncode']);

            // Try to find bill in FeeBilling table first
            $feeBilling = FeeBilling::where('challan_number', $consumerNumber)
                ->orWhere('customer_invoice_id', $consumerNumber)
                ->first();

            // If not found in FeeBilling, try Biling table
            if (!$feeBilling) {
                $biling = Biling::where('voucher_number', $consumerNumber)
                    ->orWhere('bill_number', $consumerNumber)
                    ->first();

                if ($biling) {
                    // Get student information
                    $student = $biling->student;
                    
                    // Format dates
                    $dueDate = $biling->due_date ? date('Y-m-d', strtotime($biling->due_date)) : null;
                    $billingMonth = $biling->year_month ? date('Y-m', strtotime($biling->year_month)) : date('Y-m');
                    
                    // Calculate amounts
                    $totalAmount = floatval($biling->fees ?? 0);
                    $paidAmount = floatval($biling->paid_amount ?? 0);
                    $outstanding = max(0, $totalAmount - $paidAmount);
                    
                    // Calculate amount after due date (add late fee if overdue)
                    $amountAfterDue = $outstanding;
                    if ($dueDate && strtotime($dueDate) < time()) {
                        // Add late fee (100 PKR - you can customize this)
                        $amountAfterDue = $outstanding + 100;
                    }

                    // Determine status
                    $status = ($biling->status == 1 || $paidAmount >= $totalAmount) ? 'paid' : 'unpaid';

                    // Return success response
                    return response()->json([
                        'responsecode' => '00',
                        'status' => $status,
                        'consumernumber' => $consumerNumber,
                        'institutioncode' => $institutionCode,
                        'consumername' => $student ? ($student->name ?? 'Unknown') : 'Unknown',
                        'billingMonth' => $billingMonth,
                        'amountBeforeDueDate' => number_format($outstanding, 2, '.', ''),
                        'amountAfterDueDate' => number_format($amountAfterDue, 2, '.', ''),
                        'duedate' => $dueDate
                    ], 200);
                }
            } else {
                // Use FeeBilling data
                $student = $feeBilling->student;
                
                // Format dates
                $dueDate = $feeBilling->due_date ? $feeBilling->due_date->format('Y-m-d') : null;
                $billingMonth = $feeBilling->billing_month ? date('Y-m', strtotime($feeBilling->billing_month)) : date('Y-m');
                
                // Calculate amounts
                $totalAmount = floatval($feeBilling->total_amount ?? 0);
                $paidAmount = floatval($feeBilling->paid_amount ?? 0);
                $outstanding = floatval($feeBilling->outstanding_amount ?? max(0, $totalAmount - $paidAmount));
                
                // Calculate amount after due date
                $amountAfterDue = $outstanding;
                if ($dueDate && strtotime($dueDate) < time()) {
                    // Add late fee (100 PKR - you can customize this)
                    $amountAfterDue = $outstanding + 100;
                }

                // Determine status based on FeeBilling status field
                $status = 'unpaid';
                if ($feeBilling->status == 'paid' || $paidAmount >= $totalAmount) {
                    $status = 'paid';
                } elseif ($feeBilling->status == 'partially_paid') {
                    $status = 'unpaid'; // Still has outstanding amount
                }

                // Return success response
                return response()->json([
                    'responsecode' => '00',
                    'status' => $status,
                    'consumernumber' => $consumerNumber,
                    'institutioncode' => $institutionCode,
                    'consumername' => $student ? ($student->name ?? 'Unknown') : 'Unknown',
                    'billingMonth' => $billingMonth,
                    'amountBeforeDueDate' => number_format($outstanding, 2, '.', ''),
                    'amountAfterDueDate' => number_format($amountAfterDue, 2, '.', ''),
                    'duedate' => $dueDate
                ], 200);
            }

            // Consumer number not found
            Log::warning('MCB Bill Inquiry: Consumer not found', [
                'consumernumber' => $consumerNumber,
                'institutioncode' => $institutionCode
            ]);
            
            return response()->json([
                'responsecode' => '01',
                'message' => 'failed'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('MCB Bill Inquiry: Validation Error', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'responsecode' => '04',
                'message' => 'invalid request'
            ], 200);
        } catch (\Exception $e) {
            Log::error('MCB Bill Inquiry: Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'responsecode' => '03',
                'message' => 'unknown error'
            ], 200);
        }
    }

    /**
     * Bill Payment Endpoint
     * POST /eBillingapi/paybill
     * 
     * This endpoint is used to process bill payments from MCB
     */
    public function payBill(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Log incoming request
            Log::info('MCB Bill Payment Request', [
                'request' => $request->all(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Validate request parameters
            $validated = $request->validate([
                'consumernumber' => 'required|string|max:60',
                'institutioncode' => 'required|string|max:5',
                'amount' => 'required|string',
                'transactiondate' => 'required|string|date',
                'reserved' => 'nullable'
            ]);

            $consumerNumber = trim($validated['consumernumber']);
            $institutionCode = trim($validated['institutioncode']);
            $amount = floatval($validated['amount']);
            $transactionDate = $validated['transactiondate'];

            // Check for duplicate transaction (Idempotency check)
            // This prevents processing the same payment twice
            $existingPayment = DB::table('mcb_payment_logs')
                ->where('consumernumber', $consumerNumber)
                ->where('amount', $amount)
                ->where('transactiondate', $transactionDate)
                ->where('responsecode', '00')
                ->first();

            if ($existingPayment) {
                Log::info('MCB Bill Payment: Duplicate transaction detected and ignored', [
                    'consumernumber' => $consumerNumber,
                    'amount' => $amount,
                    'transactiondate' => $transactionDate
                ]);
                
                // Return success for duplicate (already processed)
                return response()->json([
                    'responsecode' => '00',
                    'message' => 'success',
                    'consumernumber' => $consumerNumber
                ], 200);
            }

            // Find bill in FeeBilling table first
            $feeBilling = FeeBilling::where('challan_number', $consumerNumber)
                ->orWhere('customer_invoice_id', $consumerNumber)
                ->first();

            // If not found, try Biling table
            $biling = null;
            if (!$feeBilling) {
                $biling = Biling::where('voucher_number', $consumerNumber)
                    ->orWhere('bill_number', $consumerNumber)
                    ->first();
            }

            // If bill not found in either table
            if (!$feeBilling && !$biling) {
                // Log failed payment attempt
                try {
                    DB::table('mcb_payment_logs')->insert([
                        'consumernumber' => $consumerNumber,
                        'institutioncode' => $institutionCode,
                        'amount' => $amount,
                        'transactiondate' => $transactionDate,
                        'responsecode' => '01',
                        'message' => 'failed',
                        'request_data' => json_encode($request->all()),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } catch (\Exception $logError) {
                    Log::error('MCB Payment Log: Failed to log', ['error' => $logError->getMessage()]);
                }
                
                DB::commit();
                
                Log::warning('MCB Bill Payment: Consumer not found', [
                    'consumernumber' => $consumerNumber,
                    'institutioncode' => $institutionCode
                ]);
                
                return response()->json([
                    'responsecode' => '01',
                    'message' => 'failed'
                ], 200);
            }

            // Process payment for FeeBilling
            if ($feeBilling) {
                // Check if already fully paid
                $totalAmount = floatval($feeBilling->total_amount ?? 0);
                $currentPaid = floatval($feeBilling->paid_amount ?? 0);
                
                if ($feeBilling->status == 'paid' || $currentPaid >= $totalAmount) {
                    // Already paid, log and return success
                    try {
                        DB::table('mcb_payment_logs')->insert([
                            'consumernumber' => $consumerNumber,
                            'institutioncode' => $institutionCode,
                            'amount' => $amount,
                            'transactiondate' => $transactionDate,
                            'responsecode' => '00',
                            'message' => 'success',
                            'request_data' => json_encode($request->all()),
                            'note' => 'Already paid',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } catch (\Exception $logError) {
                        Log::error('MCB Payment Log: Failed to log', ['error' => $logError->getMessage()]);
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'responsecode' => '00',
                        'message' => 'success',
                        'consumernumber' => $consumerNumber
                    ], 200);
                }

                // Update FeeBilling with payment
                $newPaidAmount = $currentPaid + $amount;
                $newOutstanding = max(0, $totalAmount - $newPaidAmount);
                
                $feeBilling->paid_amount = $newPaidAmount;
                $feeBilling->outstanding_amount = $newOutstanding;
                
                // Update status
                if ($newPaidAmount >= $totalAmount) {
                    $feeBilling->status = 'paid';
                } elseif ($newPaidAmount > 0) {
                    $feeBilling->status = 'partially_paid';
                }
                
                $feeBilling->save();

            } else if ($biling) {
                // Process payment for Biling table
                $totalFees = floatval($biling->fees ?? 0);
                $currentPaid = floatval($biling->paid_amount ?? 0);
                
                // Check if already fully paid
                if ($biling->status == 1 || $currentPaid >= $totalFees) {
                    // Already paid, log and return success
                    try {
                        DB::table('mcb_payment_logs')->insert([
                            'consumernumber' => $consumerNumber,
                            'institutioncode' => $institutionCode,
                            'amount' => $amount,
                            'transactiondate' => $transactionDate,
                            'responsecode' => '00',
                            'message' => 'success',
                            'request_data' => json_encode($request->all()),
                            'note' => 'Already paid',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } catch (\Exception $logError) {
                        Log::error('MCB Payment Log: Failed to log', ['error' => $logError->getMessage()]);
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'responsecode' => '00',
                        'message' => 'success',
                        'consumernumber' => $consumerNumber
                    ], 200);
                }

                // Update Biling with payment
                $newPaidAmount = $currentPaid + $amount;
                
                $biling->paid_amount = $newPaidAmount;
                
                // Update status if fully paid
                if ($newPaidAmount >= $totalFees) {
                    $biling->status = 1; // 1 = Paid
                    $biling->paid_date = now();
                }
                
                $biling->save();
            }

            // Log successful payment
            try {
                DB::table('mcb_payment_logs')->insert([
                    'consumernumber' => $consumerNumber,
                    'institutioncode' => $institutionCode,
                    'amount' => $amount,
                    'transactiondate' => $transactionDate,
                    'responsecode' => '00',
                    'message' => 'success',
                    'request_data' => json_encode($request->all()),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $logError) {
                Log::error('MCB Payment Log: Failed to log', ['error' => $logError->getMessage()]);
            }

            DB::commit();

            Log::info('MCB Bill Payment: Successfully processed', [
                'consumernumber' => $consumerNumber,
                'amount' => $amount,
                'institutioncode' => $institutionCode
            ]);

            return response()->json([
                'responsecode' => '00',
                'message' => 'success',
                'consumernumber' => $consumerNumber
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            Log::error('MCB Bill Payment: Validation Error', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'responsecode' => '04',
                'message' => 'invalid request'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('MCB Bill Payment: Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Try to log failed payment
            try {
                DB::table('mcb_payment_logs')->insert([
                    'consumernumber' => $request->input('consumernumber', ''),
                    'institutioncode' => $request->input('institutioncode', ''),
                    'amount' => floatval($request->input('amount', 0)),
                    'transactiondate' => $request->input('transactiondate', ''),
                    'responsecode' => '05',
                    'message' => 'processing failed',
                    'request_data' => json_encode($request->all()),
                    'error_message' => $e->getMessage(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $logError) {
                // Ignore logging errors
            }
            
            return response()->json([
                'responsecode' => '05',
                'message' => 'processing failed'
            ], 200);
        }
    }
}

