<?php

namespace App\Services;


use Exception;
use Carbon\Carbon;
use App\Models\Admin\Biling;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
// use App\Models\Fee\StudentFee; // Old model - replaced with FeeCollection
use App\Models\Admin\BilingData;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BillGenerationService
{
    protected $leagureService;

    public function __construct(LedgerService $leagureService)
    {
        $this->leagureService = $leagureService;
    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $data = $request->all();
            $yearMonth = substr($data['year_month'], 0, 7);
            $month_format = Carbon::createFromFormat('Y-m', $yearMonth);
            $feeCollections = FeeCollection::with('feeCollectionDetails.feeHead', 'AcademicClass')
                ->where('company_id', $data['company_id'])
                ->where('branch_id', $data['branch_id'])
                ->where('academic_session_id', $data['session_id'])
                ->whereIn('class_id', $data['class_id'])
                ->get();
                
            if ($feeCollections->isEmpty()) {
                throw new Exception('No fee collections found for the given criteria.');
            }

            foreach ($feeCollections as $feeCollection) {
                $voucherCheck = Biling::where('class_id', $feeCollection->AcademicClass->id)->where('student_id', $feeCollection->student_id)->first();
                $totalFeeForStudent = 0;

                foreach ($feeCollection->feeCollectionDetails as $feeDetail) {
                    if ($feeDetail->feeHead->dividable == 'yes') {
                        $fee = intval($feeDetail->final_amount);
                        $totalFeeForStudent += $fee;

                    }
                    if (!$voucherCheck && $feeDetail->feeHead->dividable == 'no') {
                        $totalFeeForStudent += $feeDetail->final_amount;
                    }

                }
                $arrears = 0;
                if ($request->has('arrears') && $request->input('arrears') == 0) {
                    $arrears = Biling::where('student_id', $feeCollection->student_id)
                        ->where('status', 0)
                        ->sum('fees');
                    $totalFeeForStudent += $arrears;
                }

                $amountType = Biling::where('student_id', $feeCollection->student_id)
                    ->where('status', 0)
                    ->first();
                if ($amountType && $amountType->amount_type != null && $amountType->amount_type == 'arrears') {
                    $totalFeeForStudent += $amountType->diff_amount;
                }


                $BillGenerate = Biling::create([
                    'class_id' => $feeCollection->AcademicClass->id,
                    'fees' => $totalFeeForStudent,
                    'student_id' => $feeCollection->student_id,
                    'bill_date' => $data['bill_date'],
                    'due_date' => $data['due_date'],
                    'valid_date' => $data['valid_date'],
                    'session_id' => $data['session_id'],
                    'charge_from' => $data['charge_from'],
                    'charge_to' => $data['charge_to'],
                    'ledger_date' => $data['ledger_date'],
                    'message' => $data['message'],
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'year_month' => $month_format,
                    'previous_amount' => $arrears,
                ]);
                $totalFeeForStudent = 0;

                foreach ($feeCollection->feeCollectionDetails as $feeDetail) {
                    if ($feeDetail->feeHead->dividable == 'yes') {
                        $fee = intval($feeDetail->final_amount);
                        $totalFeeForStudent += $fee;
                    }

                    if (!$voucherCheck && $feeDetail->feeHead->dividable == 'no') {
                        $totalFeeForStudent += $feeDetail->final_amount;
                    } elseif ($feeDetail->feeHead->dividable == 'no') {
                        $totalFeeForStudent = 0;
                    }
                    BilingData::create([
                        'fee_head_id' => $feeDetail->fee_head_id,
                        'bills_amount' => $totalFeeForStudent,
                        'ledger_id' => $feeDetail->ledger_id,
                        'bills_id' => $BillGenerate->id,
                    ]);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Biling::with('AcademicClass', 'student', 'branch')->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                $btn .= '<a href="' . route("admin.bill-generation.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.bill-generation.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';

                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('class', function ($row) {
                if ($row->AcademicClass) {
                    return $row->AcademicClass->name;

                } else {
                    return "N/A";
                }

            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }

            })->addColumn('status', function ($row) {
                if ($row->status == 0) {
                    return '<button class="change-status btn btn-danger btn-sm" data-id="' . $row->id . '" data-status="0">Unpaid</button>';
                } else {
                    return '<span style="color: green">Paid</span>';
                }
            })

            ->addColumn('student', function ($row) {
                if ($row->student) {
                    return $row->student->first_name . ' ' . $row->student->last_name;

                } else {
                    return "N/A";
                }
            })
            //            ->addColumn('billing', function ($row) {
//                if ($row->billing) {
//                    return $row->student->first_name . ' ' . $row->student->last_name;
//
//                } else {
//                    return "N/A";
//                }
//            })
            ->rawColumns(['action', 'branch', 'class', 'student', 'status'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $request->all();
        $bilLData = Biling::find($id);
        $bilLData->update([
            'bill_date' => $data['bill_date'],
            'due_date' => $data['due_date'],
            'valid_date' => $data['valid_date'],
        ]);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $billing = Biling::with('billingData')->find($id);
        if ($billing) {
            $billing->billingData()->delete();

            $billing->delete();

        }
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            DB::beginTransaction();
            $bill = Biling::with(['billingData', 'student'])->find($request->id);
            $amountPaid = $request->paid_amount;
            if (!$bill)
                return response()->json(['success' => false, 'message' => 'Billing data not found.']);

            $bill->status = $request->status == 1;

            if ($request->has('paid_date')) {
                $bill->paid_date = $request->paid_date;
            }

            if ($request->has('paid_amount')) {
                $bill->paid_amount = $amountPaid;
            }

            $remainingAmount = $bill->fees - $amountPaid;

            if ($remainingAmount > 0) {
                $bill->previous_amount += $remainingAmount;
            }
            $bill->save();

            $data['branch_id'] = $bill->branch_id;
            $data['amount'] = $bill->fees;
            $data['narration'] = "Student Paied the Fee " . $bill->student->fullName;
            $data['entry_type_id'] = 1;
            $entry = $this->leagureService->createEntry($data);
            
            foreach ($bill->billingData as $billData) {
                
                $data['amount'] = $billData->bills_amount;
                $data['ledger_id'] = $billData->ledger_id;
                $data['entry_id'] = $entry->id;
                $data['balanceType'] = "c";

                $this->leagureService->createEntryItems($data);
            }

            // 18 Cash In Hand Ledgers

            $data['ledger_id'] = 18;
            $data['entry_id'] = $entry->id;
            $data['balanceType'] = "d";

            $this->leagureService->createEntryItems($data);
            DB::commit();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => true, 'data' => "", "message" => $e->getMessage()]);
        }
    }

}

