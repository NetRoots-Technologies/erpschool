<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Admin\Department;
use App\Models\Admin\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.department.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = new Departments();
        $department->name = $request->name;
        $department->status = $request->status;
        $department->save();
        return redirect()->route('hr.department.index')
            ->with('success', 'Department created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = Departments::find($id);
        return view('hr.department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = Departments::find($id);
        $department->name = $request->name;
        $department->status = $request->status;
        $department->save();
        return redirect()->route('hr.department.index')
            ->with('success', 'Department created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = Departments::findOrFail($id);
        if ($department) {
            $department->delete();
        }
    }

    public function get_data_department()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Departments::get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.department.destroy", $row->id) . '"> ';
                $btn = $btn . '<a href="' . route("hr.department.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('status', function ($row) {

                if ($row->status == 1) {
                    return 'Active';
                }if ($row->status == 2) {
                    return 'DeActive';
                }

            })
            ->rawColumns(['action'])
            ->make(true);


    }

    public function changeStatus(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = Department::find($request->id);
        if ($department) {
            $department->status = ($request->status == 'active') ? 1 : 0;
            $department->save();
            return $department;
        }
    }


}
