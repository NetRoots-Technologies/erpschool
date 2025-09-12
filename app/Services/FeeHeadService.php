<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\FeeHead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class FeeHeadService
{

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeHead = new FeeHead();
        
        $feeHead->session_id = $request->get('session_id');
        $feeHead->company_id = $request->get('company_id');
        $feeHead->branch_id = $request->get('branch_id');
        $feeHead->class_id = $request->get('class_id');
        $feeHead->account_head_id = $request->get('account_head_id');
        $feeHead->fee_section_id = $request->get('fee_section_id');
        $feeHead->fee_head = $request->get('fee_head');
        $feeHead->details = $request->get('details');
        $feeHead->dividable = $request->get('dividable');
        $feeHead->save();

        return $feeHead;
    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
       $query = FeeHead::with('company', 'branch', 'AcademicClass', 'AccountHead', 'FeeSection')
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

        $data = $query->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("admin.fee-heads.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.fee-heads.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }
    
                $btn .= '</div>';

                return $btn;

            })->addColumn('company', function ($row) {


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


            })->addColumn('AccountHead', function ($row) {


                if ($row->AccountHead) {
                    return $row->AccountHead->name;

                } else {
                    return "N/A";
                }


            })->addColumn('FeeSection', function ($row) {


                if ($row->FeeSection) {
                    return $row->FeeSection->name;

                } else {
                    return "N/A";
                }


            })
            ->rawColumns(['action', 'company', 'branch', 'AccountHead', 'FeeSection'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = FeeHead::find($id);
        $data->update([
            'session_id' => $request->get('session_id'),
            'company_id' => $request->get('company_id'),
            'branch_id' => $request->get('branch_id'),
            'class_id' => $request->get('class_id'),
            'account_head_id' => $request->get('account_head_id'),
            'fee_section_id' => $request->get('fee_section_id'),
            'fee_head' => $request->get('fee_head'),
            'details' => $request->get('details'),
            'dividable' => $request->get('dividable'),
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeHead = FeeHead::findOrFail($id);
        if ($feeHead)
            $feeHead->delete();
    }
}
