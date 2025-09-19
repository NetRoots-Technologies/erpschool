<?php

namespace App\Http\Controllers\Academic;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Academic\AssignClass;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Models\Student\Students;
use App\Services\AssignClassService;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssignClassController extends Controller
{
    public function __construct(AssignClassService $assignClassService)
    {
        $this->AssignClassService = $assignClassService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (!Gate::allows('AssignClassSection-list')) {
            return abort(403);
        }
        return view('acadmeic.assign_class.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!Gate::allows('AssignClassSection-create')) {
            return abort(403);
        }
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();
        $students = Students::all();

        return view('acadmeic.assign_class.create', compact('companies', 'students', 'sessions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if (!Gate::allows('AssignClassSection-create')) {
            return abort(403);
        }
        try {
            $this->AssignClassService->store($request);
            return redirect()->route('academic.assign_class.index')->with('success', 'Class And Section Assign successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while assigning the Class And Section');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('AssignClassSection-edit')) {
            return abort(403);
        }
        $assignClass = AssignClass::find($id);
        $companies = Company::where('status', 1)->get();
        $sessions = UserHelper::session_name();

        $students = Students::all();

        return view('acadmeic.assign_class.edit', compact('companies', 'students', 'sessions', 'assignClass'));

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
       if (!Gate::allows('AssignClassSection-edit')) {
            return abort(403);
        }
        try {
            $this->AssignClassService->update($request, $id);
            return redirect()->route('academic.assign_class.index')->with('success', 'Class And Section Update successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while Updating the Class And Section');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (!Gate::allows('AssignClassSection-delete')) {
            return abort(403);
        }
        try {
            $this->AssignClassService->destroy($id);
            return redirect()->route('academic.assign_class.index')->with('success', 'Class And Section deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleted the Class And Section');
        }

    }

    public function getData()
    {
         if (!Gate::allows('AssignClassSection-list')) {
            return abort(403);
        }
        return $this->AssignClassService->getData();

    }
}

