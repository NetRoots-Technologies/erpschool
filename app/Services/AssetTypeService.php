<?php

namespace App\Services;


use App\Models\HR\AssetType;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use App\Helper\Helpers;

class AssetTypeService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return AssetType::create([
            'name' => $request['name'],
            'depreciation' => $request['depreciation'],
            'abbreviation' => Helpers::makeAbbreviation($request['name']),
        ]);
    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AssetType::orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                $btn .= '<a href="' . route("hr.asset_type.edit", $row->id) . '" class="btn btn-primary btn-sm" style="margin-right: 4px;">Edit</a>';
                $btn .= '<form method="POST" onsubmit="return confirmDelete(event, this);" action="' . route("hr.asset_type.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                $btn .= '</div>';

                return $btn;

            })->addColumn('name', function ($row) {
                if ($row->name) {
                    return $row->name;

                } else {
                    return "N/A";
                }
            })
            ->addColumn('abbreviation', function ($row) {
                if ($row->abbreviation) {
                    return $row->abbreviation;

                } else {
                    return "N/A";
                }
            })
            ->addColumn('depreciation', function ($row) {
                return "$row->depreciation %"?? "N/A";
            })
            ->rawColumns(['action', 'branch'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $asset_type = AssetType::find($id);

        $asset_type_data = [
            'name' => $request['name'],
            'depreciation' => $request['depreciation'],
            'abbreviation' => Helpers::makeAbbreviation($request->get('name')),
        ];

        $asset_type->update($asset_type_data);
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $asset_type = AssetType::find($id);
        if ($asset_type) {
            $asset_type->delete();
        }
    }

}

