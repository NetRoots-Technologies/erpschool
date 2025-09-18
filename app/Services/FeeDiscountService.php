<?php

namespace App\Services;

use App\Models\Fee\FeeDiscount;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Student;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeeDiscountService
{
    public function store($request)
    {
        if (!Gate::allows('FeeDiscount-create')) {
            return abort(403);
        }

        $input = $request->all();
        $input['created_by'] = Auth::id();
        $input['company_id'] = Auth::user()->company_id ?? $request->company_id;
        $input['branch_id'] = Auth::user()->branch_id ?? $request->branch_id;

        $feeDiscount = FeeDiscount::create($input);

        return response()->json(['message' => 'Fee discount created successfully', 'data' => $feeDiscount]);
    }

    public function getdata()
    {
        if (!Gate::allows('FeeDiscount-list')) {
            return abort(403);
        }
        
        $query = FeeDiscount::with(['student', 'feeCategory', 'company', 'branch', 'createdBy'])
            ->orderBy('created_at', 'desc');

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $query->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        return Datatables::of($query)->addIndexColumn()
            ->addColumn('student', function ($row) {
                return $row->student ? $row->student->name : "N/A";
            })
            ->addColumn('fee_category', function ($row) {
                return $row->feeCategory ? $row->feeCategory->name : "N/A";
            })
            ->addColumn('discount_type', function ($row) {
                return ucfirst($row->discount_type);
            })
            ->addColumn('discount_value', function ($row) {
                if ($row->discount_type == 'percentage') {
                    return $row->discount_value . '%';
                } else {
                    return number_format($row->discount_value, 2);
                }
            })
            ->addColumn('valid_from', function ($row) {
                return $row->valid_from ? $row->valid_from->format('Y-m-d') : "N/A";
            })
            ->addColumn('valid_to', function ($row) {
                return $row->valid_to ? $row->valid_to->format('Y-m-d') : "N/A";
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-discounts.destroy", $row->id) . '" id="fee-discount-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeDiscount-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-discount-edit" data-fee-discount-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeDiscount-delete')) {
                    $btn .= ' <button data-id="fee-discount-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                }

                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {
        return FeeDiscount::with(['student', 'feeCategory', 'company', 'branch'])->find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('FeeDiscount-edit')) {
            return abort(403);
        }
        
        $feeDiscount = FeeDiscount::find($id);
        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $feeDiscount->update($input);

        return response()->json(['message' => 'Fee discount updated successfully', 'data' => $feeDiscount]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeDiscount-delete')) {
            return abort(403);
        }
        
        $feeDiscount = FeeDiscount::findOrFail($id);
        if ($feeDiscount) {
            $feeDiscount->delete();
        }

        return response()->json(['message' => 'Fee discount deleted successfully']);
    }

    public function changeStatus($request)
    {
        $feeDiscount = FeeDiscount::find($request->id);
        if ($feeDiscount) {
            $feeDiscount->status = ($request->status == 'active') ? 1 : 0;
            $feeDiscount->save();
            return $feeDiscount;
        }
    }

    public function applyBulkDiscount($request)
    {
        if (!Gate::allows('FeeDiscount-create')) {
            return abort(403);
        }

        DB::beginTransaction();
        try {
            $studentIds = $request->student_ids;
            $discountData = [
                'fee_category_id' => $request->fee_category_id,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
                'reason' => $request->reason,
                'created_by' => Auth::id(),
                'company_id' => Auth::user()->company_id ?? $request->company_id,
                'branch_id' => Auth::user()->branch_id ?? $request->branch_id,
            ];

            foreach ($studentIds as $studentId) {
                $discountData['student_id'] = $studentId;
                FeeDiscount::create($discountData);
            }

            DB::commit();
            return response()->json(['message' => 'Bulk discount applied successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to apply bulk discount: ' . $e->getMessage()], 500);
        }
    }
}