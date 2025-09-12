<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ToolsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(ToolsServices $ToolsServices)
    {
        $this->ToolsServices = $ToolsServices;
    }


    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.tools.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.tools.index');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $this->ToolsServices->store($request);
        return 'done';

    }

    public function assign_tools_post(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->ToolsServices->assign_tools_post($request);
        return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
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
        if (!Gate::allows('students')) {
            return abort(503);
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->validate($request, [

            'name' => 'required',

        ]);

        $this->ToolsServices->update($request, $id);
        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->ToolsServices->destroy($id);

    }

    public function assign_tools_delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->ToolsServices->assign_tools_delete($id);
        return redirect()->back();

    }


    public function assign_tools_get($student_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->ToolsServices->assign_tools_get($student_id);
        $student = $data['student'];
        $tools = $data['tools'];
        return view('admin.tools.assign_tools', compact('student', 'tools'));

    }

    //for tool index
    public function get_data_tools_tool()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $tool = $this->ToolsServices->getData();
        return $tool;
    }
    //for tool index

    public function get_data_old_assign_tools($student_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $tool = $this->ToolsServices->get_data_old_assign_tools($student_id);
        return $tool;
    }


}
