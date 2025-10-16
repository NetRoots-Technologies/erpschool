<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\TestType;
use App\Services\TestTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestTypeController extends Controller
{
    protected $TestTypeService;
    public function __construct(TestTypeService $testTypeService)
    {
        $this->TestTypeService = $testTypeService;
    }

    public function getData()
    {
        if (!Gate::allows('TestTypes-list')) {
            return abort(503);
        }
        $testType = $this->TestTypeService->getdata();
        return $testType;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('TestTypes-list')) {
            return abort(503);
        }
        return view('exam.test_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('TestTypes-craete')) {
            return abort(503);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('TestTypes-craete')) {
            return abort(503);
        }
        return $this->TestTypeService->store($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       if (!Gate::allows('TestTypes-list')) {
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
       if (!Gate::allows('TestTypes-edit')) {
            return abort(503);
        }
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
        if (!Gate::allows('TestTypes-edit')) {
            return abort(503);
        }
        return $testType = $this->TestTypeService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('TestTypes-delete')) {
            return abort(503);
        }
        return $this->TestTypeService->destroy($id);
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('TestTypes-list')) {
            return abort(503);
        }
        return $testType = $this->TestTypeService->changeStatus($request);
    }
    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('TestTypes-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $testType = TestType::find($id);
            if ($testType) {
                $testType->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}

