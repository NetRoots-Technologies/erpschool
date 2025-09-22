<?php

namespace App\Services;
use App\Models\BCategory;
use Illuminate\Support\Facades\Gate;

class BCategoryService
{

    public function store($validatedData)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // dd($validatedData);
        return BCategory::create([
            'title' => $validatedData['title'],
        ]);

    }


    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BCategory::all();

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('title', fn($row) => $row->title ?? 'N/A')

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("inventory.category.destroy", $row->id) . '" id="category-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary me-2 btn-sm text-white category_edit" data-category-edit=\'' . $row . '\'>Edit</a>';
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BCategory::findOrFail($id);
        return $data->update([
            'title' => $validatedData['title'],
        ]);
    }
    public function delete($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bcategory = BCategory::findOrFail($id);
        $bcategory->delete();
    }


}

