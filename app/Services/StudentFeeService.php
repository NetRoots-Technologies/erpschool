<?php

namespace App\Services;


use Exception;
use Carbon\Carbon;
use App\Models\Account\Entry;
use App\Models\Admin\FeeHead;
use App\Models\Account\Ledger;
use App\Models\Admin\Groups;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
// use App\Models\Fee\StudentFee; // Old model - replaced with FeeCollection
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
                    $existingFeeRecord = FeeCollection::where("student_id", $studentId)
                        ->where('generated_month', $request->get('generated_month'))
                        ->where("academic_session_id", $request->get('session_id'))
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

                    $feeCollection = new FeeCollection();
                    $feeCollection->student_id = $studentId;
                    $feeCollection->academic_session_id = $request->get('session_id');
                    $feeCollection->company_id = $request->get('company_id');
                    $feeCollection->branch_id = $request->get('branch_id');
                    $feeCollection->class_id = $request->get('class_id');
                    $feeCollection->generated_month = $request->get('generated_month');
                    $feeCollection->total_amount = $request->get('total_amount_of_month')[$studentId];
                    $feeCollection->discount_amount = $request->get('total_amount_of_discount')[$studentId];
                    $feeCollection->paid_status = 'unpaid';
                    $feeCollection->save();
                    $studentMonthlyAmount = $request->input('total_amount_of_month')[$studentId];
                    if ($studentMonthlyAmount) {
                        foreach ($studentMonthlyAmount as $index => $monthAmount) {
                            $feeCollectionDetail = new FeeCollectionDetail();
                            $feeCollectionDetail->fee_collection_id = $feeCollection->id;
                            $feeCollectionDetail->fee_head_id = $request->get('fee_head_id')[$studentId][$index];
                            $feeCollectionDetail->amount = $monthAmount;
                            $feeCollectionDetail->discount_percent = $request->get('discount')[$studentId][$index];
                            $feeCollectionDetail->discount_amount = $request->get('discount_rupees')[$studentId][$index];
                            $feeCollectionDetail->final_amount = $request->input('total_amount_after_discount')[$studentId][$index];

                            $feeHeadId = (int) $fee_head_ids[$studentId][$index];
                            $parentHead = config('constants.FixedGroups.asset_heads');

                            $ledgers = Ledger::where('parent_type', FeeHead::class)
                                ->where("parent_type_id", $feeHeadId)
                                // ->whereIn("parent_id", $parentHead)
                                ->get();

                            foreach ($ledgers as $legder) {
                                $feeCollectionDetail->ledger_id = $legder->id;

                                $entryData['balanceType'] = $legder->account_type_id == 1 ? 'd' : "c";

                                $entryData['entry_id'] = $entry->id;
                                $entryData['ledger_id'] = $legder->id;
                                $entryData['parent_id'] = $legder->id;
                                $entryData['parent_type'] = Ledger::class;
                                $entryData['amount'] = $monthAmount;

                                $this->ledgerService->createEntryItems($entryData);
                            }
                            $feeCollectionDetail->save();

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
        $feeCollection = FeeCollection::find($id);

        if ($feeCollection) {
            foreach ($feeCollection->feeCollectionDetails as $index => $item) {
                $item->amount = $request->input('monthly_amount')[$index];
                $item->discount_percent = $request->input('discount')[$index];
                $item->discount_amount = $request->input('discount_rupees')[$index];
                $item->final_amount = $request->input('total_amount_after_discount')[$index];
                $item->save();
            }
        }

    }


    public function destroy($feeCollection)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeCollection->feeCollectionDetails()->delete();
        $feeCollection->delete();
    }
}
