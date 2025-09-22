<?php

namespace App\Services;

use App\Models\Admin\Company;

use App\Models\HR\WorkShift;
use App\Models\ShiftDays;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class WorkShiftService
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $WorkShift = WorkShift::create(['name' => $request->name, 'start_time' => $request->start_time, 'end_time' => $request->end_time]);

        ShiftDays::create([
            'Mon' => $request->monday,
            'Tue' => $request->tuesday,
            'Wed' => $request->wednesday,
            'Thu' => $request->thursday,
            'Fri' => $request->friday,
            'Sat' => $request->saturday,
            'Sun' => $request->sunday,
            'work_shift_id' => $WorkShift->id,
        ]);



    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = WorkShift::with('workdays')->get();
        //        $data=ShiftDays::with('workDay')->get();
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                return ($row->status == 1) ? 'Active' : 'Deactive';
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("hr.work_shifts.destroy", $row->id) . '"   id="work_shift-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm workShift_edit" data-workshift-edit=\'' . $row . '\'>Edit</a>';

                $btn .= ' ';
                // Active/Inactive button
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                $btn .= $statusButton;

                $btn .= ' ';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="work_shift-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // dd(34);
        $workday = WorkShift::with('workDay')->find($id);

    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $workShift = WorkShift::findOrFail($id);
        $workShift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        $shiftDays = ShiftDays::where('work_shift_id', $id)->firstOrFail();
        //        dd($shiftDays);

        $shiftDays->update([
            'Mon' => $request->monday_edit,
            'Tue' => $request->tuesday_edit,
            'Wed' => $request->wednesday_edit,
            'Thu' => $request->thursday_edit,
            'Fri' => $request->friday_edit,
            'Sat' => $request->saturday_edit,
            'Sun' => $request->sunday_edit,
        ]);

        return response()->json(['message' => 'WorkShift updated successfully']);
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $WrokShift = Workshift::findOrFail($id);
        if ($WrokShift)
            $WrokShift->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $WorkShift = WorkShift::find($request->id);
        if ($WorkShift) {
            $WorkShift->status = ($request->status == 'active') ? 1 : 0;
            $WorkShift->save();
            return $WorkShift;
        }
    }

}

