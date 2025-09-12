<?php

namespace App\Services;


use Exception;
use Carbon\Carbon;
use App\Models\Account\Entry;
use App\Models\Admin\FeeHead;
use App\Models\Account\Ledger;
use App\Models\Admin\Groups;
use App\Models\Fee\StudentFee;
use App\Models\Student\Students;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\StudentFeeData;
use Illuminate\Support\Facades\Gate;

class StudentFeeService
{
    protected $ledgerService;
    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $date = date('M-y');
            $fee_head_ids = $request->get('fee_head_id');
            $students = $request->get('student_checkbox');
            if ($students) {
                foreach ($students as $key => $studentId) {
                    logger()->debug("Processing student ID: $studentId");
                    $existingFeeRecord = StudentFee::where("student_id", $studentId)
                        ->where('generated_month', $request->get('generated_month'))
                        ->where("session_id", $request->get('session_id'))
                        ->where("company_id", $request->get('company_id'))
                        ->where("branch_id", $request->get('branch_id'))
                        ->where("class_id", $request->get('class_id'))
                        ->first();

                    $student = Students::where("id", $studentId)->first();

                    if ($existingFeeRecord) {
                        throw new Exception('Fee For this Class and Student already exists for this Month');
                    }

                    $entryData = [];

                    $discounts = $request->get('total_amount_of_discount');
                    $entryData['amount'] = (float) ($discounts[$studentId] ?? 0);
                    $entryData['group_id'] = 0;
                    $entryData['group_number'] = 0;
                    $entryData['branch_id'] = $request->branch_id;
                    $entryData['narration'] = "Student Fee Entry For $date $student->fullname";
                    $entryData['entry_type_id'] = 1;

                    $entry = $this->ledgerService->createEntry($entryData);

                    $studentFee = new StudentFee();
                    $studentFee->student_id = $studentId;
                    $studentFee->session_id = $request->get('session_id');
                    $studentFee->company_id = $request->get('company_id');
                    $studentFee->branch_id = $request->get('branch_id');
                    $studentFee->class_id = $request->get('class_id');
                    $studentFee->generated_month = $request->get('generated_month');

                    $studentFee->total_monthly_amount = $request->get('total_amount_of_month')[$studentId];
                    $studentFee->total_amount_after_discount = $request->get('total_amount_of_discount')[$studentId];
                    $studentFee->fee_factor_id = $request->get('fee_factor_id')[$key];

                    $studentFee->save();
                    $studentMonthlyAmount = $request->input('total_amount_of_month')[$studentId];
                    if ($studentMonthlyAmount) {
                        foreach ($studentMonthlyAmount as $index => $monthAmount) {
                            $StudentFeeData = new StudentFeeData();

                            $StudentFeeData->monthly_amount = $monthAmount;
                            $StudentFeeData->fee_head_id = $request->get('fee_head_id')[$studentId][$index];
                            $StudentFeeData->students_fee_id = $studentFee->id;
                            $StudentFeeData->discount_percent = $request->get('discount')[$studentId][$index];
                            $StudentFeeData->discount_rupees = $request->get('discount_rupees')[$studentId][$index];
                            $StudentFeeData->claim1 = $request->get('claim_1')[$studentId][$index];
                            $StudentFeeData->claim2 = $request->get('claim_2')[$studentId][$index];
                            $StudentFeeData->total_amount_after_discount = $request->input('total_amount_after_discount')[$studentId][$index];


                            $feeHeadId = (int) $fee_head_ids[$studentId][$index];
                            $parentHead = config('constants.FixedGroups.asset_heads');

                            $ledgers = Ledger::where('parent_type', FeeHead::class)
                                ->where("parent_type_id", $feeHeadId)
                                // ->whereIn("parent_id", $parentHead)
                                ->get();

                            foreach ($ledgers as $legder) {
                                $StudentFeeData->ledger_id = $legder->id;

                                $entryData['balanceType'] = $legder->account_type_id == 1 ? 'd' : "c";

                                $entryData['entry_id'] = $entry->id;
                                $entryData['ledger_id'] = $legder->id;
                                $entryData['parent_id'] = $legder->id;
                                $entryData['parent_type'] = Ledger::class;
                                $entryData['amount'] = $monthAmount;

                                $this->ledgerService->createEntryItems($entryData);
                            }
                            $StudentFeeData->save();

                        }
                    }
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
        $data = StudentFee::with('branch', 'company', 'AcademicClass', 'student')->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                $btn .= '<a href="' . route("admin.student-regular-fee.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.student-regular-fee.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('company', function ($row) {
                if ($row->company) {
                    return $row->company->name;

                } else {
                    return "N/A";
                }

            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }


            })->addColumn('AcademicClass', function ($row) {
                if ($row->AcademicClass) {
                    return $row->AcademicClass->name;

                } else {
                    return "N/A";
                }
            })->addColumn('student', function ($row) {
                if ($row->student) {
                    return $row->student->first_name . ' ' . $row->student->last_name;

                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'company', 'branch', 'AcademicClass', 'student'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $studentFee = StudentFee::find($id);

        if ($studentFee) {
            foreach ($studentFee->student_fee_data as $index => $item) {
                $item->monthly_amount = $request->input('monthly_amount')[$index];
                $item->discount_percent = $request->input('discount')[$index];
                $item->discount_rupees = $request->input('discount_rupees')[$index];
                $item->claim1 = $request->input('claim_1')[$index];
                $item->claim2 = $request->input('claim_2')[$index];
                $item->total_amount_after_discount = $request->input('total_amount_after_discount')[$index];
                $item->save();
            }
        }

    }


    public function destroy($studentFee)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $studentFee->student_fee_data()->delete();
        $studentFee->delete();
    }
}
