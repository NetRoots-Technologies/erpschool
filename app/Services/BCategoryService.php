<?php

namespace App\Services;
use App\Models\BCategory;
use Illuminate\Support\Facades\Gate;

class BCategoryService
{

    public function store($validatedData)
    {
        
        if (!Gate::allows('students')) {
            return abort(503);
        }

        // dd($validatedData);
        return BCategory::create([
            'title' => $validatedData['title'],
            'description'=> $validatedData['description'],
            'parent_id' => $validatedData['parent_id'],
            'user_id' => auth()->user()->id,
        ]);

    }


    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BCategory::with('parent');
        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
            if ($row->parent_id == null && empty($row->parent->title)) {
                return $row->title;
            }else {
                return " -- " .''.$row->title;
            }
        })
            ->addColumn('action', function ($row) {
            $btn  = '<form class="delete_form d-inline" data-route="' . route("inventory.category.destroy", $row->id) . '" id="category-' . $row->id . '" method="POST">';
            $btn .= '<a href="' . route("inventory.category.edit", $row->id) . '"class="btn btn-primary me-2 btn-sm text-white">Edit</a>';
            $btn .= '<button data-id="category-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';
            $btn .= method_field('DELETE') . csrf_field();
            $btn .= '</form>';
                return $btn;
            })


            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($validatedData, $id)
    {
        
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BCategory::findOrFail($id);
        $data->update($validatedData);

        return true;
        
    }
    public function delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bcategory = BCategory::findOrFail($id);
        $bcategory->delete();
    }


}
