<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;

use App\Models\HR\Advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AdvanceApprovalController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.advanceApproval.index');
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Advance::with('employee')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<form method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');" action="' . route("hr.advances.destroy", $row->id) . '"> ';
                $btn .= '<a href="' . route("hr.advances.edit", $row->id) . '" class="btn btn-primary  btn-sm">Edit</a>'; // Add space with &nbsp;
                $btn .= '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                if ($row->status == 0) {
                    $btn .= '&nbsp;';
                    $btn .= '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="1">Approve Advance</button>';
                } else {
                    $btn .= '<button type="button" class="btn btn-success btn-sm change-status approved" style="display: none;" data-id="' . $row->id . '" data-status="1">Approved</button>';
                }
                return $btn;
            })


            ->addColumn('employee', function ($row) {


                if ($row->employee) {
                    return $row->employee->name;

                } else {
                    return "N/A";
                }
            })->addColumn('status', function ($row) {
                if ($row->status == '1') {
                    return '<span style="color: green;">Approved</span>';


                } else {
                    return '<span style="color: red;">Not Approved</span>';

                }
            })
            ->rawColumns(['action', 'employee', 'status'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $advance = Advance::find($request->id);
        
        if (!$advance) {
            return response()->json(['success' => false], 404);
        }
        
        $advance->status = $request->status;
        $advance->save();
        return response()->json(['success' => true]);
    }


}
