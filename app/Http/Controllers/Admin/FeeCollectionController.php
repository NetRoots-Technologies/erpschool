<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use Mpdf\Mpdf;
use App\Models\Group;
use App\Models\Session;
use App\Helpers\UserHelper;
use App\Models\Admin\Biling;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Admin\FeeTerm;
use App\Models\Admin\Ledgers;
use App\Models\FeeCollection;
use App\Models\Account\Ledger;
use App\Models\Fee\StudentFee;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin\BilingData;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\Student\AcademicSession;

class FeeCollectionController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        return view('fee.fee_collection.index', compact('companies', 'sessions'));
    }


    public function searchFeeCollection(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $session_id = $request->input('session_id');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');

        $studentsFee = Biling::where('session_id', $session_id)->where('company_id', $company_id)
            ->where('branch_id', $branch_id)->where('class_id', $class_id)->get();

            if ($studentsFee->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for the given criteria.',
                    'data' => []
                ], 404);
            }

        $data = [];
        foreach ($studentsFee as $studentFee) {
            $status = ($studentFee->status == '0') ? 'Unpaid' : 'Paid';

            $rowData = [
                'student_name' => $studentFee->student->first_name . ' ' . $studentFee->student->last_name ?? 'N/A',
                'student_class' => $studentFee->student->AcademicClass->name ?? 'N/A',
                'due_date' => $studentFee->due_date ?? 0,
                'valid_date' => $studentFee->valid_date ?? 0,
                'fees' => $studentFee->fees ?? 0,
                'voucher_number' => $studentFee->voucher_number . '-Installment' ?? 'N/A',
                'status' => $status,
                'action' => $btn = '<a href="' . route('admin.fee-collection-view', $studentFee->student->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">View</a>',
            ];

            $data[] = $rowData;
        }
        return DataTables::of($data)->make(true);
    }

    public function view($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $sessions = AcademicSession::all();
        $studentsFees = Biling::with('AcademicClass', 'student', 'billingData', 'branch', 'AcademicSession')->where('student_id', $id)->get();
        return view('fee.fee_collection.view', compact('studentsFees', 'sessions'));
    }

    public function make_installments(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();
        $fc_id = $data['fc_id'];
        $due_date = $data['due_date'];

        $fee = Biling::with('billingData')->find($fc_id);
        $student_id = $fee->student_id;
        $validity = date('Y-m-d', strtotime($due_date . ' + 5 days'));

        $existingVoucherCount = $fee->voucher_number;

        $voucher_number = $existingVoucherCount + 1;

        $totalFee = round($fee->fees / 2);

        $fee->update([
            'fees' => $totalFee,
            'installment_allow' => 0,
        ]);

        $feeInstallment = Biling::create([
            'class_id' => $fee->class_id,
            'fees' => $totalFee,
            'student_id' => $student_id,
            'fee_factor' => $fee->fee_factor,
            'bill_date' => $due_date,
            'due_date' => $due_date,
            'valid_date' => $validity,
            'session_id' => $fee->session_id,
            'charge_from' => $fee->charge_from,
            'charge_to' => $fee->charge_to,
            'ledger_date' => $fee->ledger_date,
            'message' => $fee->message,
            'company_id' => $fee->company_id,
            'branch_id' => $fee->branch_id,
            'year_month' => $fee->year_month,
            'bill_id' => $fc_id,
            'installment_allow' => 0,
            'voucher_number' => $voucher_number
        ]);

        $billsData = BilingData::where('bills_id', $fc_id)->get();

        foreach ($billsData as $billData) {
            $amount = round($billData->bills_amount / 2);

            $billData->update([
                'bills_amount' => $amount,
            ]);

            BilingData::create([
                'fee_head_id' => $billData->fee_head_id,
                'bills_id' => $feeInstallment->id,
                'bills_data_id' => $billData->id,
                'bills_amount' => $amount
            ]);
        }
        return redirect()->route('admin.fee-collection-view', ['id' => $student_id]);
    }

    public function print_voucher($id)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $studentsFee = Biling::with('AcademicClass', 'student', 'billingData.feeHead', 'branch', 'AcademicSession', 'company')->find($id);
        if (!$studentsFee) {
            abort(404);
        }
        $main_image = null;
        if ($studentsFee->company && $studentsFee->company->voucher_image) {
            $main_image = @$studentsFee->company->voucher_image;
        }

        $pdf = PDF::loadView('fee.fee_collection.print', ['studentsFee' => $studentsFee, 'main_image' => $main_image]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Voucher.pdf');
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        with('student','student_fee_data.feeHead')->
        $billing = Biling::with('billingData.feeHead', 'AcademicSession', 'company', 'branch', 'student')->find($id);
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();

        return view('fee.fee_collection.edit', compact('companies', 'sessions', 'billing'));

    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();
        $billing = Biling::with('billingData')->find($id);

        $totalDifference = intval($data['TotalDifference']);
        $diffAmount = $totalDifference !== 0 ? $totalDifference : null;

        $billingUpdates = [
            'fees' => $data['newAmount'],
            'diff_amount' => $diffAmount,
        ];

        if ($diffAmount !== null) {
            $billingUpdates['amount_type'] = $data['amount_type'];
        }

        $billing->update($billingUpdates);

        foreach ($data['fee_head_id'] as $key => $feeHeadId) {
            $billingData = $billing->billingData->where('fee_head_id', $feeHeadId)->first();
            if ($billingData) {
                $billingData->update([
                    'bills_amount' => $data['new_bill_amount'][$key]
                ]);
            }
        }

        return redirect()->back()->with('success', 'Billing information updated successfully');
    }

}
