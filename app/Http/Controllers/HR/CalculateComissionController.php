<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Fee\PaidStudentFee;
use App\Models\Fee\StudentFee;
use App\Models\HR\Agent;
use App\Models\HR\AgentNewSaleIncentive;
use App\Models\HR\AgentNewSaleIncentiveStudents;
use App\Models\HR\AgentNewSaleRecovery;
use App\Models\HR\AgentNewSaleRecoveryFeeStudents;
use App\Models\Student\Students;
use App\Services\CalculateCommissionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CalculateComissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(CalculateCommissionServices $CalculateCommissionServices)
    {
        $this->CalculateCommissionServices = $CalculateCommissionServices;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->CalculateCommissionServices->index();
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
        return $this->CalculateCommissionServices->create();
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
        return $this->CalculateCommissionServices->store($request);
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
        return $this->CalculateCommissionServices->edit();
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
        return $this->CalculateCommissionServices->update();
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
        return $this->CalculateCommissionServices->destroy();
    }

    public function old_commission_getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->CalculateCommissionServices->old_commission_getdata();

    }

    public function get_agent_comission()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->CalculateCommissionServices->get_agent_comission();

    }

    public function new_incentive_post(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->CalculateCommissionServices->new_incentive_post($request);


    }

    public function new_incentive()
    {
        $agents = Agent::get();
        return view('hr.calculateComission.new_agent_commission', compact('agents'));

    }

    public function new_incentive_index()
    {

        return view('hr.calculateComission.new_sale_incentive_index');

    }

    public function get_agents_student(Request $request)
    {
        $html = null;

        $old_student_ids = AgentNewSaleIncentiveStudents::where('agent_id', $request->agent_id)->pluck('student_id');


        $students = Students::where('agent_id', $request->agent_id)
            ->whereNotIn('id', $old_student_ids)
            ->get();

        $studentIds = $students->pluck('id')->toArray();


        foreach ($students as $student) {

            $html = $html . '<tr> <td>' . $student->name . '</td><td>' . $student->email . '<input hidden name="student_id[]" value="' . $student->id . '"></td></tr>';
        }


        $data['count'] = $students->count();
        $data['fee'] = PaidStudentFee::whereIn('student_id', $studentIds)->where('type', '=', 'advance')->where('paid_status', '=', 'paid')->sum('installement_amount');
        $data['html'] = $html;


        return $data;

    }

    public function get_data_new_sales()
    {
        return $this->CalculateCommissionServices->get_data_new_sales();
    }

    public function new_incentive_print($id)
    {
        $new_sales = AgentNewSaleIncentive::with('agent')->where('id', $id)->first();
        return view('hr.calculateComission.new_sale_incentive_print', compact('new_sales'));
    }

    public function new_sale_recovery_index()
    {

        return view('hr.calculateComission.new_sale_recovery_index');

    }

    public function new_sale_recovery_create()
    {

        $agents = Agent::get();
        return view('hr.calculateComission.new_sale_recovery', compact('agents'));

    }

    public function get_agents_student_recovery(Request $request)
    {
        $html = null;


        $old_student_ids = AgentNewSaleRecoveryFeeStudents::where('agent_id', $request->agent_id)
            ->pluck('paid_fee_id');


        $from = date('Y-m-d', strtotime($request->start_date));
        $to = date('Y-m-d', strtotime($request->end_date));


        $students = Students::with('StudentFee', 'PaidStudentFee')->where('agent_id', $request->agent_id)
            ->get();

        $studentIds = $students->pluck('id')->toArray();
        $student_fee_ids = [];


        foreach ($students as $student) {
            $student_fee_ids[] = $student->StudentFee->id;
            $html = $html . '<tr> <td>' . $student->name . '</td><td>' . $student->email . '<input hidden name="student_id[]" value="' . $student->id . '"><input hidden name="student_fee_id[]" class="student_fee_id" value="' . $student->StudentFee->id . '"></td></tr>';
        }


        //        $data['count'] = $students->count();
        $PaidStudentFees = PaidStudentFee::whereBetween('paid_date', [$from, $to])->whereIn('student_fee_id', $student_fee_ids)->whereNotIn('id', $old_student_ids)->where('type', '=', 'installment')->where('paid_status', '=', 'paid')->get();


        $data['paid_intallment_fee'] = $PaidStudentFees->sum('installement_amount');

        $data['paid_ids'] = $PaidStudentFees->pluck('id');

        $data['student_fee'] = StudentFee::whereIn('student_id', $studentIds)->sum('student_fee');
        $data['recovered_percentage'] = $data['paid_intallment_fee'] * 100 / $data['student_fee'];

        $data['html'] = $html;

        //        dd($from, $to,$data['fee'], $data['student_fee'],$data['recovered_percentage']);
        return $data;

    }

    public function recovery_incentive_post(Request $request)
    {
        return $this->CalculateCommissionServices->recovery_incentive_post($request);


    }

    public function get_data_new_recovery()
    {
        return $this->CalculateCommissionServices->get_data_new_recovery();
    }

    public function new_recovery_print($id)
    {
        $new_recovery = AgentNewSaleRecovery::with('agent')->where('id', $id)->first();
        return view('hr.calculateComission.new_sale_recovery_print', compact('new_recovery'));
    }


}
