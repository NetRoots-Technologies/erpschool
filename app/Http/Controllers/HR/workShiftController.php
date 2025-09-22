<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class workShiftController extends Controller
{

    public function __construct(WorkShiftService $workShiftService)
    {
        $this->WorkShiftService = $workShiftService;
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
        return view('hr.workshift.index');
    }

    public function getData()
    {

         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $WrokShift = $this->WorkShiftService->getdata();
        return $WrokShift;
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $WrokShift = $this->WorkShiftService->store($request);
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
        if (!Gate::allows('Dashboard-list')) {
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
         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $WrokShift = $this->WorkShiftService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->WorkShiftService->destroy($id);

    }

    public function changeStatus(Request $request)
    {
 if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $WorkShift = $this->WorkShiftService->changeStatus($request);


    }
}

