<?php


namespace App\Services;


use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\Student\AcademicSession;

class AcademicSessionService
{


    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        dd($request->all());
        $academicSession = AcademicSession::create(['name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);
    }

    //,'company_id'=> $request->company_id,'school_id' => $request->school_type_id
    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AcademicSession::with(relations: 'company')->orderby('id', 'DESC');

        return Datatables::of($data)->addIndexColumn()

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("academic.academic-session.destroy", $row->id) . '" id="academic-' . $row->id . '" method="POST">';

                // Edit button
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm academic_session_edit" data-academic-session-edit=\'' . $row . '\'>Edit</a>';

                $btn .= ' ';
                // Active/Inactive button
                $statusButton = ($row->status == 0)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                $btn .= $statusButton;

                $btn .= ' ';

                // Delete button
                $btn .= '<button data-id="academic-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';

                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';

                return $btn;
            })
            ->addColumn('company', function ($row) {
                if ($row->company)
                    return $row->company->name;
                else
                    return "N/A";

            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AcademicSession::find($id);
        $data->update(['name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);

    }
    //,'company_id'=> $request->company_id,'school_id' => $request->school_type_id

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $academicSession = AcademicSession::findOrFail($id);
        if ($academicSession)
            $academicSession->delete();
    }



    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $academicSession = AcademicSession::find($request->id);
        if ($academicSession) {
            $academicSession->status = ($academicSession->status == 1) ? 0 : 1;
            $academicSession->save();
            return $academicSession;
        }
    }
}

