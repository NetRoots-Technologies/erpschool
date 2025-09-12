<?php

namespace App\Services;

use App\Models\Exam\Component;
use App\Models\Exam\ComponentData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use function Maatwebsite\Excel\Cache\get;

class ComponentService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $component = Component::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'session_id' => $request->session_id,
        ]);
        foreach ($request->get('type_id') as $Key => $type) {
            ComponentData::create([
                'type_id' => $type,
                'component_id' => $component->id,
                'weightage' => $request->weightage[$Key],
                'total_marks' => $request->total_marks[$Key]
            ]);
        }

    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Component::with('user')->orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            //            ->addColumn('action', function ($row) {
//
//                $btn = ' <form class="delete_form" data-route="' . route("exam.components.destroy", $row->id) . '"   id="component-' . $row->id . '"  method="POST"> ';
//                // if (Gate::allows('company-edit'))
//                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm component_edit"  data-component-edit=\'' . $row . '\'>Edit</a>';
//
//                // if (Gate::allows('company-delete'))
//                $btn = $btn . ' <button data-id="component-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
//                $btn = $btn . method_field('DELETE') . '' . csrf_field();
//                $btn = $btn . ' </form>';
//                return $btn;
//            })
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("exam.components.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy'))
                {
                    $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.components.destroy", $row->id) . '">';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '<button type="button" class="btn btn-info clone-btn btn-sm" style="margin-right: 4px;" data-id=' . $row->id . '>Clone</button>';

                    $btn .= '</form>';
                }

                $btn .= '</div>';

                return $btn;

            })
            // ->addColumn('active', function ($row) {
            //     $statusButton = ($row->active == 1)
            //         ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
            //         : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

            //     return $statusButton;
            // })
            ->addColumn('user', function ($row) {
                if ($row->user) {
                    return $row->user->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'status', 'branch'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $component = Component::find($id);
        $component->name = $request->name;
        $component->user_id = Auth::id();
        $component->save();

        $existingComponentData = ComponentData::where('component_id', $component->id)->get()->keyBy('type_id');

        foreach ($request->get('type_id') as $key => $type) {
            $weightage = $request->weightage[$key];
            $total_marks = $request->total_marks[$key];

            if (isset($existingComponentData[$type])) {
                $existingComponentData[$type]->update([
                    'weightage' => $weightage,
                    'total_marks' => $total_marks
                ]);
            } else {
                ComponentData::create([
                    'component_id' => $component->id,
                    'type_id' => $type,
                    'weightage' => $weightage,
                    'total_marks' => $total_marks
                ]);
            }
        }
    }



    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $component = Component::with('componentData')->findOrFail($id);
        if ($component) {
            $component->delete();
        }
        foreach ($component->componentData as $componentData) {
            $componentData->delete();
        }
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $component = Component::find($request->id);
        if ($component) {
            $component->status = ($request->status == 'active') ? 1 : 0;
            $component->save();
            return $component;
        }
    }

}
