<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Fee\FeeHead;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class FeeHeadService
{

    public function store($request)
    {
        if (!Gate::allows('FeeHead-create')) {
            return abort(403);
        }
        
        $feeHead = FeeHead::create([
            'name' => $request->name,
            'description' => $request->description,
            'fee_category_id' => $request->fee_category_id,
            'amount' => $request->amount,
            'is_compulsory' => $request->is_compulsory ?? 0,
            'is_refundable' => $request->is_refundable ?? 0,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Fee head created successfully', 'data' => $feeHead]);
    }


    public function getdata()
    {
        if (!Gate::allows('FeeHead-list')) {
            return abort(403);
        }
        
        $query = FeeHead::with(['feeCategory', 'company', 'branch', 'createdBy'])
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
            ->addColumn('category', function ($row) {
                return $row->feeCategory ? $row->feeCategory->name : "N/A";
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('compulsory', function ($row) {
                return $row->is_compulsory ? 'Yes' : 'No';
            })
            ->addColumn('refundable', function ($row) {
                return $row->is_refundable ? 'Yes' : 'No';
            })
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-heads.destroy", $row->id) . '" id="fee-head-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeHead-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-head-edit" data-fee-head-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeHead-delete')) {
                    $btn .= ' <button data-id="fee-head-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                }

                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('FeeHead-edit')) {
            return abort(403);
        }
        
        $feeHead = FeeHead::find($id);
        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $feeHead->update($input);

        return response()->json(['message' => 'Fee head updated successfully', 'data' => $feeHead]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeHead-delete')) {
            return abort(403);
        }
        
        $feeHead = FeeHead::findOrFail($id);
        if ($feeHead) {
            $feeHead->delete();
        }

        return response()->json(['message' => 'Fee head deleted successfully']);
    }

    public function changeStatus($request)
    {
        $feeHead = FeeHead::find($request->id);
        if ($feeHead) {
            $feeHead->status = ($request->status == 'active') ? 1 : 0;
            $feeHead->save();
            return $feeHead;
        }
    }
}
