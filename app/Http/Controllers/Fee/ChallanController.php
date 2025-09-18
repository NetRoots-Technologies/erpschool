<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeVoucher;
use App\Models\Fee\FeeCollection;
use App\Models\Student\Students;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use PDF;

class ChallanController extends Controller
{
    public function index()
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }

        $companies = Company::all();
        $branches = Branch::all();
        $students = Students::where('status', 1)->get();

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $companies = Company::where('id', $user->company_id)->get();
            }

            if (!is_null($user->branch_id)) {
                $branches = Branch::where('id', $user->branch_id)->get();
            }
        }

        return view('admin.fee.challans.index', compact('companies', 'branches', 'students'));
    }

    public function generateChallan(Request $request)
    {
        if (!Gate::allows('FeeCollection-create')) {
            return abort(403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_collection_id' => 'required|exists:fee_collections,id',
        ]);

        $student = Students::findOrFail($request->student_id);
        $feeCollection = FeeCollection::with('feeCollectionDetails.feeHead')
            ->findOrFail($request->fee_collection_id);

        // Check if voucher already exists
        $existingVoucher = FeeVoucher::where('student_id', $student->id)
            ->where('fee_collection_id', $feeCollection->id)
            ->first();

        if ($existingVoucher) {
            return response()->json([
                'success' => false,
                'message' => 'Challan already exists for this student and fee collection.'
            ]);
        }

        // Create new fee voucher (challan)
        $voucher = new FeeVoucher();
        $voucher->voucher_number = $voucher->generateVoucherNumber();
        $voucher->fee_collection_id = $feeCollection->id;
        $voucher->student_id = $student->id;
        $voucher->total_amount = $feeCollection->total_amount;
        $voucher->discount_amount = 0;
        $voucher->net_amount = $feeCollection->total_amount;
        $voucher->issue_date = now();
        $voucher->due_date = $feeCollection->due_date;
        $voucher->expiry_date = $feeCollection->due_date->addDays(30);
        $voucher->status = FeeVoucher::STATUS_ISSUED;
        $voucher->is_active = true;
        $voucher->company_id = Auth::user()->company_id ?? $feeCollection->company_id;
        $voucher->branch_id = Auth::user()->branch_id ?? $feeCollection->branch_id;
        $voucher->created_by = Auth::id();
        $voucher->save();

        return response()->json([
            'success' => true,
            'message' => 'Challan generated successfully.',
            'voucher_id' => $voucher->id
        ]);
    }

    public function printChallan($id)
    {
        if (!Gate::allows('FeeVouchers-view')) {
            return abort(403);
        }

        $voucher = FeeVoucher::with([
            'student',
            'feeCollection.feeCollectionDetails.feeHead',
            'company',
            'branch'
        ])->findOrFail($id);

        return view('admin.fee.challans.print', compact('voucher'));
    }

    public function downloadChallan($id)
    {
        if (!Gate::allows('FeeVouchers-view')) {
            return abort(403);
        }

        $voucher = FeeVoucher::with([
            'student',
            'feeCollection.feeCollectionDetails.feeHead',
            'company',
            'branch'
        ])->findOrFail($id);

        $pdf = PDF::loadView('admin.fee.challans.pdf', compact('voucher'));
        
        return $pdf->download('challan-' . $voucher->voucher_number . '.pdf');
    }

    public function getStudentFeeCollections($studentId)
    {
        $collections = FeeCollection::where('student_id', $studentId)
            ->where('status', '!=', FeeCollection::STATUS_PAID)
            ->with('feeCollectionDetails.feeHead')
            ->get();

        return response()->json($collections);
    }

    public function bulkGenerateChallans(Request $request)
    {
        if (!Gate::allows('FeeCollection-create')) {
            return abort(403);
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'fee_collection_id' => 'required|exists:fee_collections,id',
        ]);

        $feeCollection = FeeCollection::findOrFail($request->fee_collection_id);
        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($request->student_ids as $studentId) {
            // Check if voucher already exists
            $existingVoucher = FeeVoucher::where('student_id', $studentId)
                ->where('fee_collection_id', $feeCollection->id)
                ->first();

            if ($existingVoucher) {
                $skippedCount++;
                continue;
            }

            // Create new fee voucher
            $voucher = new FeeVoucher();
            $voucher->voucher_number = $voucher->generateVoucherNumber();
            $voucher->fee_collection_id = $feeCollection->id;
            $voucher->student_id = $studentId;
            $voucher->total_amount = $feeCollection->total_amount;
            $voucher->discount_amount = 0;
            $voucher->net_amount = $feeCollection->total_amount;
            $voucher->issue_date = now();
            $voucher->due_date = $feeCollection->due_date;
            $voucher->expiry_date = $feeCollection->due_date->addDays(30);
            $voucher->status = FeeVoucher::STATUS_ISSUED;
            $voucher->is_active = true;
            $voucher->company_id = Auth::user()->company_id ?? $feeCollection->company_id;
            $voucher->branch_id = Auth::user()->branch_id ?? $feeCollection->branch_id;
            $voucher->created_by = Auth::id();
            $voucher->save();

            $generatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generatedCount} challans. Skipped {$skippedCount} existing challans.",
            'generated' => $generatedCount,
            'skipped' => $skippedCount
        ]);
    }

    public function getdata(Request $request)
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }

        $vouchers = FeeVoucher::with(['student', 'feeCollection', 'company', 'branch'])
            ->when($request->student_id, function ($query, $studentId) {
                return $query->where('student_id', $studentId);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->company_id, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            })
            ->when($request->branch_id, function ($query, $branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables($vouchers)
            ->addColumn('student_name', function ($voucher) {
                return $voucher->student ? $voucher->student->name : 'N/A';
            })
            ->addColumn('student_roll_no', function ($voucher) {
                return $voucher->student ? $voucher->student->roll_no : 'N/A';
            })
            ->addColumn('status_badge', function ($voucher) {
                $statusClass = [
                    'generated' => 'secondary',
                    'issued' => 'primary',
                    'paid' => 'success',
                    'expired' => 'danger',
                    'cancelled' => 'warning'
                ];
                
                $class = $statusClass[$voucher->status] ?? 'secondary';
                return '<span class="badge badge-' . $class . '">' . ucfirst($voucher->status) . '</span>';
            })
            ->addColumn('actions', function ($voucher) {
                $actions = '';
                
                if (Gate::allows('FeeVouchers-view')) {
                    $actions .= '<a href="' . route('fee.challans.print', $voucher->id) . '" class="btn btn-sm btn-info" target="_blank" title="Print Challan">
                        <i class="fas fa-print"></i>
                    </a> ';
                    
                    $actions .= '<a href="' . route('fee.challans.download', $voucher->id) . '" class="btn btn-sm btn-success" title="Download PDF">
                        <i class="fas fa-download"></i>
                    </a> ';
                }
                
                return $actions;
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }
}