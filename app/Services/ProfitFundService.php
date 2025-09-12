<?php

namespace App\Services;

use App\Models\HR\ProfitFund;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProfitFundService
{

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //dd($request->all());
        foreach ($request['employee_id'] as $key => $employee) {
            ProfitFund::create([
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
        ProfitFund::with('employee')->get();

        $data = DB::table('provident_funds')
            ->join('hrm_employees', 'provident_funds.employee_id', '=', 'hrm_employees.id')
            ->select('provident_funds.*', 'hrm_employees.name As employee_name')
            ->get();
        //        dd($data);
        return Datatables::of($data)->addIndexColumn()
            //            ->addColumn('action', function ($row) {
//                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.profit-funds.destroy", $row->id) . '"> ';
//
//                $btn = $btn . '<a href="' . route("hr.profit-funds.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
//
//                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
//                $btn = $btn . method_field('DELETE') . '' . csrf_field();
//                $btn = $btn . ' </form>';
//                return $btn;
//            })
            ->addColumn('employee', function ($row) {
                return $row->employee_name ?? 'N/A';
            })

            ->rawColumns(['action', 'employee'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $profit_fund = ProfitFund::find($id);

        $profitFund = [
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'total' => $request->total,
            'employee_percent' => $request->employee,
            'company' => $request->company,
        ];

        $profit_fund->update($profitFund);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $profitFund = ProfitFund::find($id);
        if ($profitFund) {
            $profitFund->delete();
        }
    }


}
