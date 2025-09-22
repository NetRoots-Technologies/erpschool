<?php

namespace App\Services;


use App\Helpers\ImageHelper;
use App\Models\HR\Advance;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AdvanceService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $fileNameToStore = null;

        if ($request->has('image')) {
            $file = $request->file('image');
            $fileNameToStore = ImageHelper::uploadImage($file, $destinationPath = 'advances_files');
        }

        $advance = Advance::create([
            'employee_id' => $request->get('employee_id'),
            'name' => $request->get('name'),
            'amount' => $request->get('amount'),
            'duration' => $request->get('duration'),
            'effective_from' => $request->get('start_date'),
            'installmentAmount' => $request->get('installmentAmount'),
            'amount_to_pay' => $request->get('amount_to_pay'),
            'remaining_amount' => $request->get('amount_to_pay'),
            'image' => $fileNameToStore,
        ]);
        return $advance;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Advance::with('employee')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.advances.destroy", $row->id) . '"> ';
                if ($row->status == '0') {
                    $btn = $btn . '<a href="' . route("hr.advances.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-9 btn-sm">Edit</a>';
                }
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('employee', function ($row) {


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

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $advance = Advance::findOrFail($id);

        $fileNameToStore = $advance->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileNameToStore = ImageHelper::uploadImage($file, 'advances_files');
        }

        $advance->update([
            'employee_id' => $request->input('employee_id'),
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'duration' => $request->input('duration'),
            'effective_from' => $request->input('start_date'),
            'installmentAmount' => $request->input('installmentAmount'),
            'amount_to_pay' => $request->input('amount_to_pay'),
            'image' => $fileNameToStore,
        ]);

        return $advance;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $advance = Advance::find($id);
        if ($advance) {
            $advance->delete();
        }
    }
}

