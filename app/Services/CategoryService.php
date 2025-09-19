<?php

namespace App\Services;

use App\Models\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class CategoryService
{

    public function getdata()
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
        $data = Category::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('action', function ($row) {

                // if (Gate::allows('company-edit'))
                $btn = '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm category_edit"  data-category-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
    
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
        $data = Category::find($id);
        $input = $request->all();
        $data->update($input);
    }




}

