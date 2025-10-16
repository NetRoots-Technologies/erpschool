<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\SchoolType;
use App\Models\Admin\Company;
use App\Services\SchoolTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SchoolTypeController extends Controller
{

    protected $SchoolTypeService;
    public function __construct(SchoolTypeService $schoolTypeService)
    {
        $this->SchoolTypeService = $schoolTypeService;

        $this->middleware('can:SchoolType-list')->only(['index', 'getData']);
        $this->middleware('can:SchoolType-create')->only(['create', 'store']);
        $this->middleware('can:SchoolType-edit')->only(['edit', 'update', 'changeStatus']);
        $this->middleware('can:SchoolType-delete')->only(['destroy', 'handleBulkAction']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $companies = Company::where('status', 1)->get();
        return view('acadmeic.school_types.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return $school = $this->SchoolTypeService->store($request);

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

        return $school = $this->SchoolTypeService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        return $this->SchoolTypeService->destroy($id);

    }

    public function getData()
    {

        $school = $this->SchoolTypeService->getdata();
        return $school;
    }


    public function changeStatus(Request $request)
    {

        return $company = $this->SchoolTypeService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {

        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $school = SchoolType::find($id);
            if ($school) {
                $school->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }
}

