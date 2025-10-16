<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Exam\Component;
use Yajra\DataTables\DataTables;
use App\Models\Exam\SubComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class subComponentService
{
    public function store($request)
    {
       
        foreach ($request->get('test_type_id') as $key => $testType)
            $subComponent = SubComponent::create([
                'test_type_id' => $testType,
                'comp_name' => $request->comp_name[$key],
                'comp_number' => $request->comp_number[$key],
                'component_id' => $request->component_id,
                'user_id' => Auth::id(),
            ]);
    }

    public function getdata()
    {
       
        $data = SubComponent::with('component', 'user', 'test_type')->orderby('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                               if (Gate::allows('SubComponents-edit')){
                $btn .= '<a href="' . route("exam.sub_components.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                               }

                if (Gate::allows('SubComponents-delete'))
                {
                    $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.sub_components.destroy", $row->id) . '">';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    // $btn .= '<button type="button" class="btn btn-info clone-btn btn-sm" style="margin-right: 4px;" data-id=' . $row->id . '>Clone</button>';
                    $btn .= '</form>';
                }

                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('user', function ($row) {
                if ($row->user) {
                    return $row->user->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('component', function ($row) {
                if ($row->component) {
                    return $row->component->name;
                } else {
                    return "N/A";
                }
            })->addColumn('test_type', function ($row) {
                if ($row->test_type) {
                    return $row->test_type->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'component', 'test_type', 'created_at'])
            ->make(true);
    }

    public function update($request, $componentId)
    {
        $subComponentIds = $request->sub_component_id ?? [];

        foreach ($request->test_type_id as $index => $testTypeId) {
            $subComponentId = $subComponentIds[$index] ?? null;

            if ($subComponentId) {
                // Update existing subcomponent
                $subComponent = SubComponent::find($subComponentId);
                if ($subComponent) {
                    $subComponent->update([
                        'test_type_id' => $testTypeId,
                        'comp_name' => $request->comp_name[$index],
                        'comp_number' => $request->comp_number[$index],
                        'user_id' => Auth::id(),
                    ]);
                }
            } else {
                // Create new subcomponent
                SubComponent::create([
                    'component_id' => $componentId,
                    'test_type_id' => $testTypeId,
                    'comp_name' => $request->comp_name[$index],
                    'comp_number' => $request->comp_number[$index],
                    'user_id' => Auth::id(),
                ]);
            }
        }
    }



    public function destroy($id)
    {
       
        $subComponent = SubComponent::findOrFail($id);
        if ($subComponent) {
            $subComponent->delete();
        }
    }

}

