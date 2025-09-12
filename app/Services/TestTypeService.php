<?php

namespace App\Services;
use App\Models\Exam\TestType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class TestTypeService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        TestType::create([
            'name' => $request->name
        ]);
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = TestType::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("exam.test_types.destroy", $row->id) . '"   id="testType-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm test_type_edit"  data-test-type-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="testType-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function update($request, $id, $image = null)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $testType = TestType::find($id);
        $testType->name = $request->name;

        $testType->save();
    }


    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $testType = TestType::findOrFail($id);
        if ($testType)
            $testType->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $testType = TestType::find($request->id);
        if ($testType) {
            $testType->status = ($request->status == 'active') ? 1 : 0;
            $testType->save();
            return $testType;
        }
    }

}
