<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Agent;
use App\Services\AgentTypeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AgentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AgentTypeServices;
    public function __construct(AgentTypeServices $AgentTypeServices)
    {
        $this->AgentTypeServices = $AgentTypeServices;
    }


    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.agent_type.index');
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
        return view('hr.agent_type.create');
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
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $this->AgentTypeServices->store($request);

        return redirect()->route('hr.agent_type.index')
            ->with('success', 'Agent Type created successfully');
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
        $agent = $this->AgentTypeServices->edit($id);
        return view('hr.agent_type.edit', compact('agent'));
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
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $this->AgentTypeServices->update($request, $id);

        return redirect()->route('hr.agent_type.index')
            ->with('success', 'Agent Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent = $this->AgentTypeServices->getdata();
        return $agent;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent = $this->AgentTypeServices->destroy($id);
        return redirect()->route('hr.agent_type.index')
            ->with('success', 'Agent Type deleted successfully');
    }
}

