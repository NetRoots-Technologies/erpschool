<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\AgentType;
use App\Services\AgentComissionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AgentCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AgentComissionServices;
    public function __construct(AgentComissionServices $AgentComissionServices)
    {
        $this->AgentComissionServices = $AgentComissionServices;
    }
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->AgentComissionServices->index();
        return $data;
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
        $agent_comission = $this->AgentComissionServices->create();
        return $agent_comission;
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
        $validated = $request->validate([
            'slab_name' => 'required',
            'comission' => 'required',
        ]);
        $this->AgentComissionServices->store($request);
        return redirect()->route('hr.agent_comission.index')
            ->with('success', 'Agent Comission created successfully');
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
        $agent_type = AgentType::all();
        $agent_comission = $this->AgentComissionServices->edit($id);
        return view('hr.agentcommissionplan.edit', compact('agent_comission', 'agent_type'));

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
        $agent_comission = $this->AgentComissionServices->update($id);
        return $agent_comission;
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
        $agent_comission = $this->AgentComissionServices->destroy($id);
        return $agent_comission;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agent_comission = $this->AgentComissionServices->getdata();
        return $agent_comission;
    }
}

