<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Agent;
use App\Models\HR\AgentType;
use App\Models\User;
use App\Services\AgentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AgentServices;

    public function __construct(AgentServices $AgentServices)
    {
        $this->AgentServices = $AgentServices;
    }


    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent_type = AgentType::all();
        $agent = Agent::where('agent_type_id', 2)->get();

        return view('hr.agent.index', compact('agent_type', 'agent'));
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
        $agent_type = AgentType::all();
        return view('hr.agent.create', compact('agent_type'));
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
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'mobile' => 'required',
        ]);
        $this->AgentServices->store($request);
        return 'done';

        //        return redirect()->route('hr.agent.index')
//            ->with('success','Agent created successfully');
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
        $agent_type = AgentType::get();
        $agent = $this->AgentServices->edit($id);
        return view('hr.agent.edit', compact('agent', 'agent_type'));
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
        $user = User::where('agent_id', $id)->first();
        if ($user) {
            $validated = $request->validate([
                'name' => 'required',
                'email' => "required|email|unique:users,email,$user->id",
            ]);
        }
        $this->AgentServices->update($request, $id);


        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent = $this->AgentServices->getdata();
        return $agent;
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent = $this->AgentServices->destroy($id);
        return 'done';
    }
}
