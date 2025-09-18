<?php

namespace App\Services;

use App\Models\Fee\FeeSection;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeSectionService
{

    public function store($request)
    {
        if (!Gate::allows('FeeSection-create')) {
            return abort(403);
        }
        
        $feeSection = FeeSection::create([
            'name' => $request->name,
            'description' => $request->description,
            'fee_category_id' => $request->fee_category_id,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Fee section created successfully', 'data' => $feeSection]);
    }


    public function getdata()
    {
        if (!Gate::allows('FeeSection-list')) {
            return abort(403);
        }
        
        $query = FeeSection::with(['feeCategory', 'company', 'branch', 'createdBy'])
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
            ->addColumn('fee_category', function ($row) {
                return $row->feeCategory ? $row->feeCategory->name : "N/A";
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('status', function ($row) {
                return ($row->is_active == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("admin.fee.fee-sections.destroy", $row->id) . '" id="fee-section-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeSection-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-section-edit" data-fee-section-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeSection-delete')) {
                    $btn .= ' <button data-id="fee-section-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
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
        if (!Gate::allows('FeeSection-edit')) {
            return abort(403);
        }
        
        $feeSection = FeeSection::findOrFail($id);
        
        $feeSection->update([
            'name' => $request->name,
            'description' => $request->description,
            'fee_category_id' => $request->fee_category_id,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Fee section updated successfully', 'data' => $feeSection]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeSection-delete')) {
            return abort(403);
        }
        
        $feeSection = FeeSection::findOrFail($id);
        $feeSection->delete();

        return response()->json(['message' => 'Fee section deleted successfully']);
    }

    public function changeStatus($id, $status)
    {
        if (!Gate::allows('FeeSection-edit')) {
            return abort(403);
        }
        
        $feeSection = FeeSection::findOrFail($id);
        $feeSection->is_active = ($status == 'active') ? 1 : 0;
        $feeSection->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

}
