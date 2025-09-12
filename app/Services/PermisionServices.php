<?php

namespace App\Services;

use Config;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermisionServices
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Permission::select('id', 'name')->get();
    }

    public function apiindex()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Permission::all();
    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        dd('a');
        return permisions::where('main', 1)->get();
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Permission::select('id', 'name', 'main', 'parent_id')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("permissions.destroy", $row->id) . '"   id="Permission-' . $row->id . '"  method="POST"> ';
                if (Gate::allows('Permissions-edit'))
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm permission_edit"  data-permission-edit=\'' . $row . '\'>Edit</a>';
                if (Gate::allows('Permissions-delete'))
                    $btn = $btn . ' <button data-id="Permission-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';


                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $parrent_id = $request->parrent ?? 0;


        return Permission::create(['name' => $request->name, 'guard_name' => 'web', 'main' => $request->main, 'parent_id' => $parrent_id]);

    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Permission::findOrFail($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Permission = Permission::find($id);

        $Permission->name = $request->name;

        $Permission->save();

        return $Permission;

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Permission = Permission::findOrFail($id);

        if ($Permission)
            $Permission->delete();

    }
}
