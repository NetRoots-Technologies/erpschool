<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Models\Admin\Groups;
use App\Models\Exam\ClassSubject;
use App\Models\Exam\ExamDetail;
use App\Models\Exam\SkillGroup;
use App\Models\Exam\Skills;
use App\Models\Exam\SkillType;
use App\Services\skillTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SkillTypeController extends Controller
{

    public function __construct(skillTypeService $skillTypeService)
    {
        $this->skillTypeService = $skillTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('exam.skill_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();
        $groups = SkillGroup::all();
        $skills = Skills::all();
        return view('exam.skill_type.create', compact('sessions', 'companies', 'groups', 'skills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->skillTypeService->store($request);
        return redirect()->route('exam.skill_types.index')->with('success', 'Skill Type created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $skillType = SkillType::find($id);

        if ($skillType) {
            $groups = SkillGroup::all();
            $sessions = UserHelper::session_name();
            $companies = Company::where('status', 1)->get();
            $skills = Skills::all();
            return view('exam.skill_type.edit', compact('sessions', 'companies', 'skillType', 'groups', 'skills'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->skillTypeService->update($request, $id);
        return redirect()->route('exam.skill_types.index')->with('success', 'Skill Type Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->skillTypeService->destroy($id);
        return redirect()->route('exam.skill_types.index')->with('success', 'Skill Type deleted successfully');
    }

    public function fetchExamSubject(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $classSubjects = ClassSubject::with('subject')->where('class_id', $request->class_id)->get();

        $subjects = $classSubjects->map(function ($classSubject) {
            return [
                'id' => $classSubject->subject->id,
                'name' => $classSubject->subject->name
            ];
        });

        return response()->json($subjects);
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->skillTypeService->getData();
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        //            dd($ids);
        foreach ($ids as $id) {
            SkillType::where('id', $id)->delete();
            return response()->json(['message' => 'Bulk  action Completed successfully']);
        }
    }
}

