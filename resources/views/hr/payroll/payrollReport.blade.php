@extends('admin.layouts.main')

@section('title')
    Payroll | Report
@stop

@section('content')
    <div class="container-fluid ">
        <div class="row w-100">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Payroll Report </h3>
        </div>
        <div class="card">
            <form id="approvalForm" action="{{route('hr.payroll_report.search')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group col-md-3" id="from_date_div">
                        <label for="selectMonth"><b>Select Month/Year</b></label>
                        <input type="month" id="month_year"
                               name="month_year" class="form-control" value="{{ date('Y-m') }}">

                    </div>
                    <div>
                        <button id="search_button" type="submit"
                                class="btn  btn-md btn-flat btn-primary ms-4">
                            <b>Search </b></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row w-100 text-center">
            <div class="card">
                <div class="panel-body pad table-responsive">
                    <table class="table table-striped table-bordered table-responsive" id="tablewithextensions">
                        <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Basic Salary</th>
{{--                            <th>Per Minute Salary</th>--}}
                            <th>Committed Time(In Hours)</th>
                            <th>Actualized Time(In Hours)</th>
                            <th>Total Salary</th>
                            <th>Advance Installment</th>
                            {{--                                <th>Loan Amount</th>--}}
                            <th>Eobi Rupees</th>
                            <th>Provident Fund</th>
                            {{--                                <th>Employee Welfare Fund</th>--}}
                            <th>Total Fund Amount</th>
                            <th>Net Salary</th>
                            <th>Cash Handed Over</th>
                            <th>Bank Transfer</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($payrolls))
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
{{--                                    <td>{{$item->salary_per_minute ?? 'N/A'}}</td>--}}
                                    <td>{{$item['committedTime'] ?? 'N/A'}}</td>
                                    <td>{{$item->total_working_hours ?? 'N/A'}}</td>
                                    <td>{{$item->total_salary ?? 'N/A'}}</td>

                                    <td>{{$item->advance ?? 'N/A'}}</td>
                                    {{--                                    <td>{{$item->loan ?? 'N/A'}}</td>--}}
                                    <td>{{$fundValues['eobi_provident_fund'] ?? 'N/A'}}</td>
                                    <td>{{$fundValues['provident_fund'] ?? 'N/A'}}</td>
                                    {{--                                    <td>{{$fundValues['employee_welfare_fund'] ?? 'N/A'}}</td>--}}
                                    <td>{{$item->total_fund_amount ?? 'N/A'}}</td>
                                    <td>{{$item->net_salary ?? 'N/A'}}</td>
                                    <td>{{$item->cash_in_hand ?? 'N/A'}}</td>
                                    <td>{{$item->cash_in_bank ?? 'N/A'}}</td>

                                </tr>
                            @endforeach
                            <td><b>Total:</b></td>
                            <td colspan="2" style='font-weight: bold;'></td>
                            <td style='font-weight: bold;'>Time: {!! $payrolls->sum('committedTime') !!}</td>

                            <td style='font-weight: bold;'>Time: {!! $payrolls->sum('total_working_hours') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('total_salary') !!}</td>

                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('advance') !!}</td>

                            {{--                            <td style='font-weight: bold;'>Rs.{!! $fundValues->sum('eobi_provident_fund') !!}</td>--}}
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('advance') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('advance') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('total_fund_amount') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('net_salary') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('cash_in_hand') !!}</td>
                            <td style='font-weight: bold;'>Rs.{!! $payrolls->sum('cash_in_bank') !!}</td>
                        @else
                            <tr>
                                <td colspan="15" class="text-center">No Record Found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')
    @include('partials.datatables_extensions')
@endsection
