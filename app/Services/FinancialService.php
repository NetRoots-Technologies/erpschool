<?php

namespace App\Services;


use App\Models\Financial;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class FinancialService
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }


    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $financial = Financial::create(['name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);
    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Financial::orderby('id', 'DESC');

        return Datatables::of($data)->addIndexColumn()

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("admin.financial-years.destroy", $row->id) . '" id="financial-' . $row->id . '" method="POST">';

                // Edit button
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm financial_edit" data-financial-edit=\'' . $row . '\'>Edit</a>';

                $btn .= ' ';
                // Active/Inactive button
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                $btn .= $statusButton;

                $btn .= ' ';

                // Delete button
                $btn .= '<button data-id="financial-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';

                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';

                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Financial::find($id);
        $input = $request->all();
        $data->update($input);
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Financial = Financial::findOrFail($id);
        if ($Financial)
            $Financial->delete();
    }



    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $financialYear = Financial::find($request->id);
        if ($financialYear) {
            $financialYear->status = ($request->status == 'active') ? 1 : 0;
            $financialYear->save();
            return $financialYear;
        }
    }

}

