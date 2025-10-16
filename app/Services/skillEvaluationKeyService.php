<?php

namespace App\Services;


use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Exam\SkillEvaluationKey;

class skillEvaluationKeyService
{

    public function store($request)
    {
        
        SkillEvaluationKey::create(['key' => $request->key, 'abbr' => $request->abbr, 'user_id' => Auth::id()]);
    }

    public function getdata()
    {
        
        $data = SkillEvaluationKey::with('user')->orderby('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("exam.skill_evaluations_key.destroy", $row->id) . '"   id="skillEvaluation-' . $row->id . '"  method="POST"> ';
                if (Gate::allows('SkillEvaluationsKey-edit')){
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm skillEvaluationKey_edit"  data-skill_evaluation-edit=\'' . $row . '\'>Edit</a>';

                }

                if (Gate::allows('SkillEvaluationsKey-delete')){
                      $btn = $btn . ' <button data-id="skillEvaluation-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                }
              
                return $btn;
            })
            ->addColumn('user', function ($row) {
                if ($row->user) {
                    return $row->user->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function update($request, $id)
    {
        
        $skillEvaluation = SkillEvaluationKey::find($id);
        $skillEvaluation->abbr = $request->abbr;
        $skillEvaluation->key = $request->key;
        $skillEvaluation->user_id = Auth::id();

        $skillEvaluation->save();
    }

    public function destroy($id)
    {
        
        $skillEvaluation = SkillEvaluationKey::findOrFail($id);
        if ($skillEvaluation)
            $skillEvaluation->delete();
    }

    public function changeStatus($request)
    {
        
        $skillEvaluation = SkillEvaluationKey::find($request->id);
        if ($skillEvaluation) {
            $skillEvaluation->status = ($request->status == 'active') ? 1 : 0;
            $skillEvaluation->save();
            return $skillEvaluation;
        }
    }


}

