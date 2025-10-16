<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CourseTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\CourseType;


class CourseTypeController extends Controller
{
    protected $CourseTypeService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(CourseTypeService $UserServise)
    {
        $this->CourseTypeService = $UserServise;
    }

    public function index()
    {
        if (!Gate::allows('SubjectType-list')) {
            return abort(503);
        }
        return view('acadmeic.course_type.index');
    }

    public function getData()
    {
        if (!Gate::allows('SubjectType-list')) {
            return abort(503);
        }
        $Users = $this->CourseTypeService->getdata();
        return $Users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if (!Gate::allows('SubjectType-create')) {
            return abort(503);
        }
        return view('admin.coursetype.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('SubjectType-create')) {
            return abort(503);
        }
        $this->CourseTypeService->store($request);

        //return redirect()->route('admin.coursetype.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('SubjectType-list')) {
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
        if (!Gate::allows('SubjectType-edit')) {
            return abort(503);
        }
        $data = CourseType::find($id);


        return view('admin.coursetype.edit', compact('data'));
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
        if (!Gate::allows('SubjectType-edit')) {
            return abort(503);
        }
        $this->validate($request, [

            'name' => 'required',
            'description' => 'required'
        ]);

        $this->CourseTypeService->update($request, $id);

        //        return redirect()->route('admin.coursetype.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('SubjectType-delete')) {
            return abort(503);
        }
        $this->CourseTypeService->destroy($id);

        //        return redirect()->route('admin.coursetype.index')
//            ->with('success', 'CourseType deleted successfully');
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('SubjectType-list')) {
            return abort(503);
        }
        return $coursetype = $this->CourseTypeService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('SubjectType-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            CourseType::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }
}

