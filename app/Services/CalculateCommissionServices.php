<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\HR\Agent;
use App\Models\HR\AgentType;
use App\Models\HRM\Employees;
use App\Models\Student\Students;
use App\Models\HRM\EmployeeTypes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\HR\AgentComissionPlan;
use App\Models\HR\CalculateComission;
use App\Models\HR\AgentNewSaleRecovery;
use Illuminate\Support\Facades\Request;
use App\Models\HR\AgentNewSaleIncentive;
use App\Models\HR\AgentNewSaleIncentiveStudents;
use App\Models\HR\AgentNewSaleRecoveryFeeStudents;


class CalculateCommissionServices
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('hr.calculateComission.index');
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agents = Agent::all();
        $agent_types = AgentType::all();
        $comission_types = AgentComissionPlan::all();
        return view('hr.calculateComission.generate_comission', compact('agents', 'agent_types', 'comission_types'));

    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //Sales Executive (Comission)
        $from = date('Y-m-d', strtotime($request->start_date));
        $to = date('Y-m-d', strtotime($request->end_date));
        foreach ($request->selection as $key => $value) {
            //            $comission = CalculateComission::where('start_date', '>=', $from)->where('end_date', '<=', $to)->first();
//            if (!$comission) {
            $comission = new CalculateComission();
            //            }
            $comission->start_date = date('Y-m-d', strtotime($request->start_date));
            $comission->end_date = date('Y-m-d', strtotime($request->end_date));
            $comission->agent_id = $value;

            $agent_type = Agent::find($value);
            if ($agent_type->agent_type_id == 1) {


                $comission->agent_type_id = $agent_type->agent_type_id;

                $students = Students::where('agent_id', $value)->get();
                $total_student = $students->count();
                $comission->no_of_student = $total_student;


                $slab_type = AgentComissionPlan::
                    where('min', '<=', $total_student)->where('max', '>=', $total_student)
                    ->where('agent_type_id', $agent_type->agent_type_id)
                    ->where('slab_type', '1')
                    ->first();


                $comission->slab_name = $slab_type->slab_name;

                if ($slab_type->slab_type == 1) {
                    $comission->slab_type = 'Comission';
                } elseif ($slab_type->slab_type == 2) {
                    $comission->slab_type = 'Recovery';
                }

                $fee = StudentFee::whereIn('student_id', $students->pluck('id'))->sum('student_fee');
                $comission_percentage = (int) $slab_type->comission;


                $percentage = $fee * $comission_percentage / 100;

                $comission->total_comission = $percentage;
                //
                $comission->save();

                //                return redirect()->to('hr/calculate_comission');
//                dd($comission);

                //Sales Executes (Recovery)
//            $students = Students::where('agent_id', $value)->where('remaining_amount', '!=', 0)->get();
//            $fee = StudentFee::whereIn('student_id', $students->pluck('id'))->where('remaining_amount', '!=', 0)->sum('student_fee');    // getting student total fee not paid
                $paid_fee = PaidStudentFee::whereBetween('paid_date', [$from, $to])->whereIn('student_id', $students->pluck('id'))->where('paid_status', 'paid')->sum('installement_amount');
                $total_fee_to_paid_fee_percentage = $paid_fee * 100 / $fee;

                $slab_type_recovery = AgentComissionPlan::
                    where('min', '<=', $total_fee_to_paid_fee_percentage)->where('max', '>=', $total_fee_to_paid_fee_percentage)
                    ->where('agent_type_id', $agent_type->agent_type_id)
                    ->where('slab_type', '2')
                    ->first();

                $comission_percentage_recovery = (int) $slab_type_recovery->comission;

                $recovery_amount_total = $paid_fee * $comission_percentage_recovery / 100;

                $recovery_amount = CalculateComission::where('start_date', '>=', $from)->where('end_date', '<=', $to)->first();
                if (!$recovery_amount) {
                    $recovery_amount = new CalculateComission();
                }
                $recovery_amount->start_date = date('Y-m-d', strtotime($request->start_date));
                $recovery_amount->end_date = date('Y-m-d', strtotime($request->end_date));
                $recovery_amount->agent_id = $value;
                $recovery_amount->agent_type_id = $agent_type->agent_type_id;
                $recovery_amount->slab_name = $slab_type_recovery->slab_name;
                if ($slab_type_recovery->slab_type == 1) {
                    $recovery_amount->slab_type = 'Comission';
                } elseif ($slab_type_recovery->slab_type == 2) {
                    $recovery_amount->slab_type = 'Recovery';
                }
                $recovery_amount->total_comission = $recovery_amount_total;

                $recovery_amount->save();
                //                dd($recovery_amount);
//                return redirect()->to('hr/calculate_comission');

            } elseif ($agent_type->agent_type_id == 2) {


                //Manager Agent Comission
//                $agent_comission = CalculateComission::where('agent_id', $value)->where('start_date', '>=', $from)->where('end_date', '<=', $to)->first();
//                if (!$agent_comission) {
                $agent_comission = new CalculateComission();
                //                }

                $agent_comission->start_date = date('Y-m-d', strtotime($request->start_date));
                $agent_comission->end_date = date('Y-m-d', strtotime($request->end_date));
                $agent_comission->agent_id = $value;

                $agent_comission->agent_type_id = $agent_type->agent_type_id;

                $agents = Agent::where('parent_id', $value)->get();
                $total_agents = $agents->count();
                $agent_ids = $agents->pluck('id');

                $agent_comission->no_of_agents = $total_agents;

                $agent_slab_type = AgentComissionPlan::
                    where('min', '<=', $total_agents)->where('max', '>=', $total_agents)
                    ->where('agent_type_id', $agent_type->agent_type_id)
                    ->where('slab_type', '1')
                    ->first();

                if (isset($agent_comission->slab_name)) {
                    $agent_comission->slab_name = $agent_slab_type->slab_name;

                }


                if ($agent_slab_type->slab_type == 1) {
                    $agent_comission->slab_type = 'Comission';
                } elseif ($agent_slab_type->slab_type == 2) {
                    $agent_comission->slab_type = 'Recovery';
                }

                $agents_student = Students::whereIn('agent_id', $agent_ids)->pluck('id');

                $agents_student_fee = StudentFee::whereIn('student_id', $agents_student)->sum('student_fee');

                $agent_comission_percentage = (int) $agent_slab_type->comission;

                $manager_agent_comission = $agents_student_fee * $agent_comission_percentage / 100;


                $agent_comission->total_comission = $manager_agent_comission;


                $agent_comission->save();
                ////
//                return redirect()->to('hr/calculate_comission');
//                dd($agent_comission);

                //Manager Agent Recovery

                //                $test_agent_paid_fee_comission = CalculateComission::where('agent_type_id', 1)->where('slab_type', 'Recovery')->sum('total_comission');
//
//                $test_total_fee_to_paid_fee_percentage_manager_agent = $test_agent_paid_fee_comission * 100 / $agents_student_fee;
//
//                $test_manager_slab_type_recovery = AgentComissionPlan::
//                where('min', '<=', $test_total_fee_to_paid_fee_percentage_manager_agent)->where('max', '>=', $test_total_fee_to_paid_fee_percentage_manager_agent)
//                    ->where('agent_type_id', $agent_type->agent_type_id)
//                    ->where('slab_type', '2')
//                    ->first();
//
//                $test_manager_comission_percentage_recovery = (int)$test_manager_slab_type_recovery->comission;
//
//                $test_recovery_amount_total = $test_agent_paid_fee_comission * $test_manager_comission_percentage_recovery / 100;


                //                dd($test_manager_slab_type_recovery);

                $agent_paid_fee = PaidStudentFee::whereBetween('paid_date', [$from, $to])->whereIn('student_id', $agents_student)->where('paid_status', 'paid')->sum('installement_amount');
                $total_fee_to_paid_fee_percentage_manager_agent = $agent_paid_fee * 100 / $agents_student_fee;

                //                dd($total_fee_to_paid_fee_percentage_manager_agent);

                $manager_slab_type_recovery = AgentComissionPlan::
                    where('min', '<=', $total_fee_to_paid_fee_percentage_manager_agent)->where('max', '>=', $total_fee_to_paid_fee_percentage_manager_agent)
                    ->where('agent_type_id', $agent_type->agent_type_id)
                    ->where('slab_type', '2')
                    ->first();


                $manager_comission_percentage_recovery = (int) $manager_slab_type_recovery->comission;

                $recovery_amount_total = $agent_paid_fee * $manager_comission_percentage_recovery / 100;


                //                dd($recovery_amount_total);

                //                dd($recovery_amount_total);

                //                $manager_recovery_amount = CalculateComission::where('start_date', '>=', $from)->where('end_date', '<=', $to)->first();
//                if (!$manager_recovery_amount) {
                $manager_recovery_amount = new CalculateComission();
                //                }
                $manager_recovery_amount->start_date = date('Y-m-d', strtotime($request->start_date));
                $manager_recovery_amount->end_date = date('Y-m-d', strtotime($request->end_date));
                $manager_recovery_amount->agent_id = $value;
                $manager_recovery_amount->agent_type_id = $agent_type->agent_type_id;
                $manager_recovery_amount->slab_name = $manager_slab_type_recovery->slab_name;
                if ($manager_slab_type_recovery->slab_type == 1) {
                    $manager_recovery_amount->slab_type = 'Comission';
                } elseif ($manager_slab_type_recovery->slab_type == 2) {
                    $manager_recovery_amount->slab_type = 'Recovery';
                }
                $manager_recovery_amount->total_comission = $recovery_amount_total;
                $manager_recovery_amount->save();
                //                dd($manager_recovery_amount);

                $manager_recovery_amount->save();
                //                dd($recovery_amount);
//                return redirect()->to('hr/calculate_comission');
            }

        }
        return redirect()->route('hr.calculate_comission.index');
    }


    public function old_commission_getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = CalculateComission::with('agent', 'agent_type', 'comission_type')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                //                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.calculate_comission.destroy", $row->id) . '"> ';
//
//                $btn = $btn . '<a href="' . route("hr.calculate_comission.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
//                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
//                $btn = $btn . method_field('DELETE') . '' . csrf_field();
//                $btn = $btn . ' </form>';
//                return $btn;
                return '';
            })->addColumn('agent', function ($row) {
                if ($row->agent) {
                    return $row->agent->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('agent_type', function ($row) {
                if ($row->agent_type) {
                    return $row->agent_type->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('comission_type', function ($row) {

                if ($row->comission_type) {
                    return $row->slab_name;
                } else {
                    return 'N/A';
                }
            })->addColumn('slab_type', function ($row) {
                if ($row->comission_type) {

                    return $row->slab_type;
                } else {
                    return 'N/A';
                }

            })
            ->rawColumns(['action', 'agent', 'agent_type', 'slab_type', 'comission_type'])
            ->make(true);
    }

    public function get_agent_comission()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Agent::with('agent_type')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('agent_type', function ($row) {
                if ($row->agent_type) {
                    return $row->agent_type->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('selection', function ($row) {

                return "<input type='checkbox' name='selection[]'  value='" . $row->id . "'/>";

            })
            ->rawColumns(['agent_type', 'selection'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $agents = Agent::all();
        $agent_types = AgentType::all();
        $comission_types = AgentComissionPlan::all();
        return view('hr.calculateComission.edit', compact('agents', 'agent_types', 'comission_types'));
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $calculate_comission = CalculateComission::find($id);
        $calculate_comission->agent_id = $request->agent_id;
        $calculate_comission->agent_type_id = $request->agent_type_id;
        $calculate_comission->slab_name = $request->slab_name;
        $calculate_comission->slab_type = $request->slab_type;
        $calculate_comission->total_comission = $request->total_comission;
        $calculate_comission->status = $request->status;
        $calculate_comission->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $calculate_comission = CalculateComission::findOrFail($id);
        if ($calculate_comission)
            $calculate_comission->delete();

        return redirect()->route('hr.calculateComission.index');
    }


    public function new_incentive_post($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        try {

            $new_sale = new AgentNewSaleIncentive();
            $new_sale->agent_id = $request->agent;
            $new_sale->count = $request->count;
            $new_sale->student_fee = $request->student_fee;
            $new_sale->percentage = $request->percentage;
            $new_sale->commission = $request->commission;
            $new_sale->save();

            $new_sale_id = $new_sale->id;
            foreach ($request->student_id as $item) {

                $student_id = new AgentNewSaleIncentiveStudents();
                $student_id->student_id = $item;
                $student_id->agent_id = $new_sale->agent_id;
                $student_id->agent_new_sale_incentive_id = $new_sale_id;
                $student_id->save();

            }

        } catch (\Exception $e) {

            return redirect()->route('hr.new_incentive_index')->with('status', 'No Student Found!');
        }



        //        $new_sale->student_ids = json_encode($request->student_id);


        return redirect()->route('hr.new_incentive_index');
    }

    public function get_data_new_sales()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AgentNewSaleIncentive::with('agent')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('agent', function ($row) {
                if ($row->agent) {
                    return $row->agent->name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn = $btn . '<a href="' . route("hr.new_incentive_print", $row->id) . '" class="btn btn-primary btn-sm">Incentive Slip</a>';
                return $btn;

            })
            ->rawColumns(['agent', 'action'])
            ->make(true);
    }


    public function recovery_incentive_post($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        dd($request);

        try {
            $paid_fee_ids = explode(",", $request->paid_fee_id);

            $new_recovery = new AgentNewSaleRecovery();
            $new_recovery->agent_id = $request->agent;
            $new_recovery->start_date = date('Y-m-d', strtotime($request->start_date));
            $new_recovery->end_date = date('Y-m-d', strtotime($request->end_date));
            $new_recovery->recovered_percentage = $request->recovered_percentage;
            $new_recovery->incentive_percentage = $request->incentive_percentage;
            $new_recovery->total_paid_installment = $request->total_paid_installment;
            $new_recovery->total_student_fee = $request->total_student_fee;
            $new_recovery->commission = $request->commission;
            $new_recovery->save();

            $new_sale_id = $new_recovery->id;
            foreach ($paid_fee_ids as $item) {

                $student_id = new AgentNewSaleRecoveryFeeStudents();
                //                $student_id->student_id = $item;
//                $student_id->student_fee_id = $request->student_fee_id;
                $student_id->agent_id = $new_recovery->agent_id;
                $student_id->agent_recovery_incentive_id = $new_sale_id;
                $student_id->start_date = $new_recovery->start_date;
                $student_id->paid_fee_id = $item;
                $student_id->end_date = $new_recovery->end_date;
                $student_id->save();

            }

        } catch (\Exception $e) {

            return redirect()->route('hr.new_sale_recovery_index')->with('status', 'No Student Found!');
        }



        //        $new_sale->student_ids = json_encode($request->student_id);


        return redirect()->route('hr.new_sale_recovery_index');
    }

    public function get_data_new_recovery()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = AgentNewSaleRecovery::with('agent')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('agent', function ($row) {
                if ($row->agent) {
                    return $row->agent->name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn = $btn . '<a href="' . route("hr.new_recovery_print", $row->id) . '" class="btn btn-primary btn-sm">Recovery Slip</a>';
                return $btn;

            })
            ->rawColumns(['agent', 'action'])
            ->make(true);
    }

}


