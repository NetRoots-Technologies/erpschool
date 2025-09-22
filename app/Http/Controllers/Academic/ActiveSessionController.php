<?php

namespace App\Http\Controllers\Academic;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\ActiveSession;
use App\Models\Academic\SchoolType;
use App\Models\Academic\SchoolTypeBranch;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Services\ActiveSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActiveSessionController extends Controller
{
    public function __construct(ActiveSessionService $activeSessionService)
    {
        $this->ActiveSessionService = $activeSessionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('ActiveSessions-list')) {
        return abort(403); // 403 Forbidden is better than 503
    }
        return view('acadmeic.active_session.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!Gate::allows('ActiveSessions-create')) {
        return abort(403);
    }
        $sessions = UserHelper::session_name();
        $companies = Company::where('status', 1)->get();

        return view('acadmeic.active_session.create', compact('sessions', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('ActiveSessions-create')) {
        return abort(403);
    }
        $this->ActiveSessionService->store($request);

        return redirect()->route('academic.active_sessions.index')->with('success', 'Active Session created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (!Gate::allows('ActiveSessions-edit')) {
        return abort(403);
    }
        $activeSession = ActiveSession::with('academicSession', 'company')->find($id);
        $sessions = UserHelper::session_name();

        $companies = Company::where('status', 1)->get();
        return view('acadmeic.active_session.edit', compact('sessions', 'companies', 'activeSession'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('ActiveSessions-edit')) {
        return abort(403);
    }
        $this->ActiveSessionService->update($request, $id);

        return redirect()->route('academic.active_sessions.index')->with('success', 'Active Session updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('ActiveSessions-delete')) {
        return abort(403);
    }
        $this->ActiveSessionService->destroy($id);

        return redirect()->route('academic.active_sessions.index')->with('success', 'Active Session Deleted successfully');
    }

    public function getData()
    {
         if (!Gate::allows('ActiveSessions-list')) {
        return abort(403);
    }
        return $this->ActiveSessionService->getdata();
    }


    public function fetch_activeSession(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $session = ActiveSession::with('academicSession')->where('class_id', $request->class_id)->first();

        if ($session && $session->academicSession) {
            return response()->json($session->academicSession);
        } else {
            return response()->json(null);
        }
    }

    public function fetch_schoolType(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $branch = Branch::with('schoolBranch')->find($request->branch_id);
        $schoolTypes = [];

        foreach ($branch->schoolBranch as $schoolBranch) {
            $schoolType = SchoolType::find($schoolBranch->school_type_id);

            if ($schoolType) {
                $schoolTypes[] = $schoolType;
            }
        }

        return response()->json($schoolTypes);
    }






}

