<?php

namespace App\Services;


use App\Models\HR\Eobi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class EobiService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        foreach ($request['employee_id'] as $key => $employee) {
            $existingEobi = Eobi::where('employee_id', $employee)
                ->where('branch_id', $request['branch_id'])
                ->first();

            if ($existingEobi) {
                continue;
            }

            Eobi::create([
                'branch_id' => $request['branch_id'],
                'employee_id' => $employee,
                'total' => $request['total'][$key],
                'employee_percent' => $request['employee_percentage'][$key],
                'company' => $request['company'][$key],
            ]);
        }
    }




    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = DB::table('eobis')
            ->join('hrm_employees', 'eobis.employee_id', '=', 'hrm_employees.id')
            ->select('eobis.*', 'hrm_employees.name As employee_name')
            ->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.eobis.destroy", $row->id) . '"> ';

                // $btn = $btn . '<a href="' . route("hr.eobis.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
    
                // $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('employee', function ($row) {
                return $row->employee_name ?? 'N/A';
            })

            ->rawColumns(['employee', 'action'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $attendance = Eobi::find($id);

        $attendance_data = [
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'total' => $request->total,
            'employee_percent' => $request->employee,
            'company' => $request->company,
        ];

        $attendance->update($attendance_data);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $eobi = Eobi::find($id);
        if ($eobi) {
            $eobi->delete();
        }
    }


}
