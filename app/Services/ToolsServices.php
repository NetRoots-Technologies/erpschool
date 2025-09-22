<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Tool;
use App\Models\Admin\Course;
use App\Models\Admin\AssignTool;
use App\Models\Admin\VideoCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;


class ToolsServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Tool::all();
    }


    public function create()
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
        $tools = Tool::create(['name' => $request->name, 'description' => $request->description]);

    }

    public function assign_tools_post($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $assign_tools = new AssignTool();
        $assign_tools->student_id = $request->student_id;
        $assign_tools->session_id = $request->session_id;
        $assign_tools->course_id = $request->course_id;
        $assign_tools->tools = $request->tools;
        $assign_tools->student_fee_id = $request->student_fee_id;

        $student_tools_fee = StudentFee::find($request->student_fee_id);
        if ($student_tools_fee) {

            $student_tools_fee->tools_provided = 'Yes';
            $student_tools_fee->save();
        }


        $assign_tools->status = 0;

        $assign_tools->save();

    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Tool::find($id);
        $input = $request->all();
        $data->update($input);
    }


    //for tool index
    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Tool::get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("admin.tools.destroy", $row->id) . '"   id="tools-' . $row->id . '"  method="POST"> ';

                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm tools_edit"  data-tools_edit=\'' . $row . '\'>Edit</a>';


                $btn = $btn . ' <button data-id="tools-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    //for tool index

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $tools = Tool::findOrFail($id);
        if ($tools)
            $tools->delete();

    }

    public function assign_tools_delete($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $tools = AssignTool::findOrFail($id);
        if ($tools)
            $tools->delete();

    }


    public function assign_tools_get($student_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data['student'] = StudentFee::with('course', 'student', 'session')->where('id', $student_id)->first();
        $data['tools'] = Tool::get();

        return $data;

    }


    //specific student tools datatable
    public function get_data_old_assign_tools($student_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AssignTool::with('tool')->where('student_id', $student_id)->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.assign_tools_delete", $row->id) . '"> ';
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('tool', function ($row) {
                if (isset($row->tool)) {

                    $btn = $row->tool->name;
                    return $btn;
                } else {
                    $btn = 'N/A';
                    return $btn;
                }

                return $btn;
            })
            ->rawColumns(['tool', 'action'])
            ->make(true);

    }

    //specific student tools datatable

}


