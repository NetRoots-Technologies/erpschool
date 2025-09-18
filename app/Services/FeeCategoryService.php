<?php

namespace App\Services;

use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeCategoryService
{
    public function store($request)
    {
        if (!Gate::allows('FeeCategory-create')) {
            return abort(403);
        }
        
        $feeCategory = FeeCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Fee category created successfully', 'data' => $feeCategory]);
    }


    public function getdata()
    {
        if (!Gate::allows('FeeCategory-list')) {
            return abort(403);
        }
        
        $query = FeeCategory::with(['company', 'branch', 'createdBy'])
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
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-categories.destroy", $row->id) . '" id="fee-category-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeCategory-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-category-edit" data-fee-category-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeCategory-delete')) {
                    $btn .= ' <button data-id="fee-category-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
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
        return FeeCategory::find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('FeeCategory-edit')) {
            return abort(403);
        }
        
        $feeCategory = FeeCategory::find($id);
        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $feeCategory->update($input);

        return response()->json(['message' => 'Fee category updated successfully', 'data' => $feeCategory]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeCategory-delete')) {
            return abort(403);
        }
        
        $feeCategory = FeeCategory::findOrFail($id);
        if ($feeCategory) {
            $feeCategory->delete();
        }

        return response()->json(['message' => 'Fee category deleted successfully']);
    }

    public function changeStatus($request)
    {
        $feeCategory = FeeCategory::find($request->id);
        if ($feeCategory) {
            $feeCategory->status = ($request->status == 'active') ? 1 : 0;
            $feeCategory->save();
            return $feeCategory;
        }
    }

}
