@extends('admin.layouts.main')

@section('title')
Payroll | Approval
@stop

@section('content')


<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form id="salaryForm">
                <div class="panel-body pad table-responsive">
                    <table class="table table-responsive table-striped table-bordered">
                        <tr>
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Basic Salary</th>
                            <th>Committed Time(In Hours)</th>
                            <th>Actualized Time(In Hours)</th>
                            <th>Total Salary</th>
                            <th>Advance Installment</th>
                            <th>Eobi Rupees</th>
                            <th>Provident Fund</th>
                            <th>Total Fund Amount</th>
                            <th>Medical Allowance</th>
                            <th>Net Salary</th>
                            <th>Cash Handed Over</th>
                            <th>Bank Transfer</th>
                        </tr>

                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($payrolls as $key => $item)

                            @php
                            $employee = \App\Models\HRM\Employees::where('id',$item->employee_id)->first();
                            $fundValues =$item->fund_values;
                            @endphp
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$employee->name ?? 'N/A'}}</td>
                                <td>{{$employee->salary ?? 'N/A'}}</td>
                                <td>{{$item['committedTime'] ?? 'N/A'}}</td>
                                <td>{{$item->total_working_hours ?? 'N/A'}}</td>
                                <td>{{$item->total_salary ?? 'N/A'}}</td>
                                <td>{{$item->advance ?? 'N/A'}}</td>
                                <td>{{$fundValues['eobi_provident_fund'] ?? 'N/A'}}</td>
                                <td>{{$fundValues['provident_fund'] ?? 'N/A'}}</td>
                                <td>{{$item->total_fund_amount ?? 'N/A'}}</td>
                                <td>{{$item->medicalAllowance ?? 'N/A'}}</td>
                                <td>{{$item->net_salary ?? 'N/A'}}</td>
                                <td>{{$item->cash_in_hand ?? 'N/A'}}</td>
                                <td>{{$item->cash_in_bank ?? 'N/A'}}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @php
    $lateJoin = $payrollApproval->payroll->contains('late_join','!=', 1);
    @endphp


    @if($payrollApproval->approved == 0)
    <a href="{{ route('hr.payroll.status.approve',$payrollApproval->id) }}" type="submit"
        class="btn btn-success">Approve</a>
    <a href="{{ route('hr.payroll.status.reject',$payrollApproval->id) }}" type="submit"
        class="btn btn-danger">Reject</a>
    @elseif($payrollApproval->approved == 2)
    <a href="{{ route('hr.payroll.status.approve',$payrollApproval->id) }}" type="submit"
        class="btn btn-success">Approve</a>
    @endif

</div>

@endsection