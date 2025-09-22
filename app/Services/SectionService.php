<?php

namespace App\Services;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\SchoolType;

use App\Models\Academic\Section;
use Config;
use DataTables;
//use Illuminate\Contracts\Auth\Access\Gate;
use \Illuminate\Support\Facades\Gate;

class SectionService
{


    public function store($request)
    {

        //dd($request->all());
        $section = Section::create(['name' => $request->name, 'session_id' => $request->session_id, 'class_id' => $request->class_id, 'branch_id' => $request->branch_id, 'company_id' => $request->company_id, 'active_session_id' => $request->active_session_id]);


    }


    public function getdata()
    {

        $data = Section::with('academicClass', 'session', 'branch', 'company')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('class', function ($row) {
                if ($row->academicClass)
                    return $row->academicClass->name;
                else
                    return "N/A";

            })
            ->addColumn('company', function ($row) {
                if ($row->company)
                    return $row->company->name;
                else
                    return "N/A";

            })
            ->addColumn('session', function ($row) {
                if ($row->session)
                    return $row->session->name . ' ' . date('y', strtotime($row->session->start_date)) . '-' . date('y', strtotime($row->session->end_date));
                else
                    return "N/A";

            })
            ->addColumn('branch', function ($row) {
                if ($row->branch)
                    return $row->branch->name;
                else
                    return "N/A";

            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("academic.sections.destroy", $row->id) . '"   id="section-' . $row->id . '"  method="POST"> ';
                if (Gate::allows('Section-edit')) {
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm sections_edit"  data-section-edit=\'' . $row . '\'>Edit</a>';

                }
                if (Gate::allows('Section-delete')) {
                    $btn = $btn . ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm "" >Delete</button>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';
                }
                return $btn;
            })
            ->rawColumns(['action', 'status', 'session', 'class', 'branch', 'company'])
            ->make(true);
    }

    public function edit($id)
    {

        return Section::find($id);
    }


    public function update($request, $id)
    {

        $data = Section::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {

        $section = Section::findOrFail($id);
        if ($section)
            $section->delete();
    }

    public function changeStatus($request)
    {

        $section = Section::find($request->id);
        if ($section) {
            $section->status = ($request->status == 'active') ? 1 : 0;
            $section->save();
            return $section;
        }
    }
}

